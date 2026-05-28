<?php 

namespace App\Services;

use Pgvector\Laravel\Vector;

class GeminiService
{
    public static function generateFaceVectorWithVertexAI(string $imagePath)
    {
        $credentialFile = env('GOOGLE_SERVICE_ACCOUNT_JSON');

        if (empty($credentialFile)) {
            throw new \Exception('GOOGLE_SERVICE_ACCOUNT_JSON is not set in .env');
        }

        // Resolve path relative to project root (cert/...)
        $jsonPath = base_path('cert/' . $credentialFile);

        if (! file_exists($jsonPath)) {
            throw new \Exception('Google Service Account JSON file not found at: ' . $jsonPath);
        }

        $config = json_decode(file_get_contents($jsonPath), true);
        $projectId = $config['project_id'];
        $clientEmail = $config['client_email'];
        $privateKey = $config['private_key'];
        $region = 'asia-southeast1'; // Sesuaikan region GCP Anda jika berbeda

        // -------------------------------------------------------------------------
        // LANGKAH 1: MEMBUAT ACCESS TOKEN GOOGLE VIA OAUTH2 (JWT RS256)
        // -------------------------------------------------------------------------
        $header = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode(['alg' => 'RS256', 'typ' => 'JWT'])));
        $payload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode([
            'iss' => $clientEmail,
            'scope' => 'https://www.googleapis.com/auth/cloud-platform',
            'aud' => 'https://oauth2.googleapis.com/token',
            'exp' => time() + 3600,
            'iat' => time()
        ])));

        // Tanda tangani JWT dengan OpenSSL internal PHP
        openssl_sign("$header.$payload", $signature, $privateKey, OPENSSL_ALGO_SHA256);
        $signedJwt = "$header.$payload." . str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        // Tukar JWT dengan Access Token menggunakan cURL
        $ch = curl_init('https://oauth2.googleapis.com/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $signedJwt,
        ]));
        
        $tokenResponse = json_decode(curl_exec($ch), true);
        curl_close($ch);

        $accessToken = $tokenResponse['access_token'] ?? null;

        if (!$accessToken) {
            throw new Exception("Gagal mendapatkan Access Token Google: " . json_encode($tokenResponse));
        }

        // -------------------------------------------------------------------------
        // LANGKAH 2: MENGUBAH FOTO KE BASE64 & TEMBAK VERTEX AI EMBEDDINGS
        // -------------------------------------------------------------------------
        if (!file_exists($imagePath)) {
            throw new Exception("File foto tidak ditemukan di path: " . $imagePath);
        }
        
        $imageBase64 = base64_encode(file_get_contents($imagePath));

        // Endpoint URL resmi Vertex AI Multimodal Embeddings
        $vertexUrl = "https://{$region}-aiplatform.googleapis.com/v1/projects/{$projectId}/locations/{$region}/publishers/google/models/multimodalembedding@001:predict";

        // Siapkan payload JSON sesuai standard Google Cloud
        $payloadData = json_encode([
            'instances' => [
                [
                    'image' => [
                        'bytesBase64Encoded' => $imageBase64
                    ]
                ]
            ]
        ]);

        // Eksekusi HTTP Request ke Vertex AI menggunakan cURL
        $ch = curl_init($vertexUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payloadData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json',
        ]);

        $vertexResponse = curl_exec($ch);
        
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            throw new \Exception("Vertex AI API Error (Status {$httpCode}): " . $vertexResponse);
        }

        $responseData = json_decode($vertexResponse, true);
        
        // Ambil nilai array vector hasil ekstraksi wajah
        $vectorArray = $responseData['predictions'][0]['imageEmbedding'] ?? null;

        if (!$vectorArray) {
            throw new \Exception("Response Vertex AI tidak mengandung 'imageEmbedding': " . $vertexResponse);
        }

        return $vectorArray; // Mengembalikan array angka desimal (1408 dimensi)
    }
}