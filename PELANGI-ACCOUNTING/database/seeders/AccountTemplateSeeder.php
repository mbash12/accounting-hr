<?php

namespace Database\Seeders;

use App\Models\AccountTemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Load accounts from CSV file
        $csvFile = database_path('seeders/data/accounts.csv');
        
        if (!file_exists($csvFile)) {
            $this->command->error("Accounts CSV file not found at: {$csvFile}");
            return;
        }
        
        $accountsData = $this->parseCsv($csvFile);
        
        if (empty($accountsData)) {
            $this->command->error("No data found in CSV file");
            return;
        }
        
        $this->command->info("Starting to seed " . count($accountsData) . " account templates...");
        
        $templateName = 'Standard Indonesian COA';
        
        DB::beginTransaction();
        
        try {
            // Clear existing templates with the same name
            AccountTemplate::where('template_name', $templateName)->delete();
            
            foreach ($accountsData as $accountData) {
                AccountTemplate::create([
                    'code' => $accountData['code'],
                    'name' => $accountData['name'],
                    'description' => $accountData['description'],
                    'classification_type' => $this->mapClassificationType($accountData['classification_type']),
                    'account_type' => $this->mapToAccountType($this->mapClassificationType($accountData['classification_type']), $this->isHeader($accountData['is_header'])),
                    'is_header' => $this->isHeader($accountData['is_header']),
                    'is_cash_bank' => $accountData['is_cash_bank'] === 'true',
                    'is_active' => $accountData['is_active'] === 'true',
                    'cash_flow' => $this->getCashFlowType($accountData['code'], $this->mapClassificationType($accountData['classification_type'])),
                    'parent_code' => $accountData['parent_code'] ?: null,
                    'level' => $this->calculateLevel($accountData['code']),
                    'template_name' => $templateName,
                    'notes' => 'Standard Indonesian Chart of Accounts template',
                ]);
            }
            
            DB::commit();
            
            $this->command->info("✓ Successfully seeded " . count($accountsData) . " account templates as '{$templateName}'");
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error("Error seeding account templates: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Map classification type to account type
     */
    private function mapToAccountType(string $classificationType, bool $isHeader): string
    {
        if ($isHeader) {
            return match($classificationType) {
                'asset' => 'current_asset',
                'liability' => 'current_liability',
                'equity' => 'equity',
                'revenue' => 'revenue',
                'expense' => 'expense',
                default => 'current_asset',
            };
        }
        
        return match($classificationType) {
            'asset' => 'current_asset',
            'liability' => 'current_liability',
            'equity' => 'equity',
            'revenue' => 'revenue',
            'expense' => 'expense',
            default => 'current_asset',
        };
    }
    
    /**
     * Determine cash flow type based on account code and classification
     */
    private function getCashFlowType(string $code, string $classificationType): string
    {
        // Cash and cash equivalents
        if (in_array(substr($code, 0, 1), ['1']) && 
            (str_contains($code, '1111') || str_contains($code, '1112'))) {
            return 'operating';
        }
        
        // Operating activities
        if (in_array(substr($code, 0, 1), ['1', '2', '5', '6'])) {
            return 'operating';
        }
        
        // Investing activities
        if (in_array(substr($code, 0, 1), ['1']) && 
            (str_contains($code, '12') || str_contains($code, '13') || str_contains($code, '14') || str_contains($code, '15'))) {
            return 'investing';
        }
        
        // Financing activities
        if (in_array(substr($code, 0, 1), ['2']) && 
            (str_contains($code, '21') || str_contains($code, '22'))) {
            return 'financing';
        }
        
        return 'undefined';
    }
    
    /**
     * Parse CSV file and return array of data
     */
    private function parseCsv(string $filePath): array
    {
        $data = [];
        $handle = fopen($filePath, 'r');
        
        if (!$handle) {
            return $data;
        }
        
        // Skip header row
        $header = fgetcsv($handle);
        
        while (($row = fgetcsv($handle)) !== false) {
            if (count($header) === count($row)) {
                $data[] = array_combine($header, $row);
            }
        }
        
        fclose($handle);
        return $data;
    }
    
    /**
     * Map classification code to classification type
     */
    private function mapClassificationType(string $classificationCode): string
    {
        return match($classificationCode) {
            '1' => 'asset',
            '2' => 'liability',
            '3' => 'equity',
            '4' => 'revenue',
            '5' => 'expense',
            '6' => 'expense',
            '7' => 'expense',
            '8' => 'revenue',
            '9' => 'expense',
            default => 'asset',
        };
    }
    
    /**
     * Determine if account is a header based on Header field from CSV
     */
    private function isHeader(string $headerValue): bool
    {
        // Use the Header field from CSV instead of code pattern
        return $headerValue === 'true';
    }
    
    /**
     * Calculate account level based on code structure
     */
    private function calculateLevel(string $code): int
    {
        if (preg_match('/^(1|2|3|4|5|6|7|8|9)$/', $code)) {
            return 1;
        }
        if (preg_match('/\d{9}000$/', $code)) {
            return 2;
        }
        if (preg_match('/\d{6}000$/', $code)) {
            return 3;
        }
        if (preg_match('/\d{3}000$/', $code)) {
            return 4;
        }
        return 5;
    }
}
