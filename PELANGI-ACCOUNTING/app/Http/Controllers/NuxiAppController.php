<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class NuxiAppController extends Controller
{
    /**
     * Directory (relative to public/) that holds the built Nuxt app.
     */
    private const APP_DIR = 'user';

    /**
     * Serve the Nuxt static app.
     *
     * - Existing files under public/{APP_DIR}/ are returned verbatim with the
     *   correct Content-Type.
     * - Pre-rendered directory pages (public/{APP_DIR}/foo/index.html) are
     *   served for /user/foo and /user/foo/ requests.
     * - Anything else falls back to public/{APP_DIR}/index.html so client-side
     *   routing can take over.
     */
    public function serve(?string $any = null): Response
    {
        $base = public_path(self::APP_DIR);

        \Illuminate\Support\Facades\Log::info('nuxi.serve', ['any' => $any, 'base' => $base, 'is_dir' => is_dir($base)]);

        if (! is_dir($base)) {
            abort(404, sprintf('Nuxt app not built. Expected directory: %s', $base));
        }

        $relative = trim((string) $any, '/');

        \Illuminate\Support\Facades\Log::info('nuxi.relative', ['relative' => $relative, 'fallback_exists' => is_file($base.DIRECTORY_SEPARATOR.'index.html')]);

        // Reject any path-traversal attempts and NUL bytes.
        if ($relative === '' || $this->isSafe($relative)) {
            $candidates = $this->candidatesFor($relative, $base);

            foreach ($candidates as $candidate) {
                if (is_file($candidate)) {
                    return $this->fileResponse($candidate);
                }
            }
        }

        // SPA fallback: let Nuxt's client router handle the URL.
        $fallback = $base.DIRECTORY_SEPARATOR.'index.html';
        \Illuminate\Support\Facades\Log::info('nuxi.fallback', ['fallback' => $fallback, 'is_file' => is_file($fallback), 'realpath' => realpath($fallback)]);
        if (is_file($fallback)) {
            return $this->fileResponse($fallback);
        }

        abort(404, 'Nuxt app index.html missing.');
    }

    /**
     * Build the ordered list of filesystem paths to try for a given URL tail.
     *
     * @return list<string>
     */
    private function candidatesFor(string $relative, string $base): array
    {
        if ($relative === '') {
            return [$base.DIRECTORY_SEPARATOR.'index.html'];
        }

        return [
            $base.DIRECTORY_SEPARATOR.$relative,
            $base.DIRECTORY_SEPARATOR.$relative.DIRECTORY_SEPARATOR.'index.html',
        ];
    }

    /**
     * Reject anything that tries to escape the app directory.
     */
    private function isSafe(string $relative): bool
    {
        if ($relative === '' || $relative[0] === '/') {
            return false;
        }

        if (str_contains($relative, "\0")) {
            return false;
        }

        $segments = explode('/', $relative);
        foreach ($segments as $segment) {
            if ($segment === '..' || $segment === '.') {
                return false;
            }
        }

        return true;
    }

    private function fileResponse(string $absolute): Response
    {
        $mime = $this->mimeType($absolute);
        $name = basename($absolute);

        // For real files on disk, Symfony's BinaryFileResponse streams the
        // content with the right headers (Content-Length, ETag, etc.).
        \Illuminate\Support\Facades\Log::info('nuxi.fileResponse', ['absolute' => $absolute, 'name' => $name, 'readable' => is_readable($absolute), 'size' => @filesize($absolute)]);

        if (is_readable($absolute) && $this->shouldStream($name)) {
            $response = new BinaryFileResponse($absolute, 200, ['Content-Type' => $mime]);
        } else {
            $content = file_get_contents($absolute);
            \Illuminate\Support\Facades\Log::info('nuxi.content', ['len' => strlen($content ?? ''), 'mime' => $mime]);
            $response = new Response((string) $content, 200, ['Content-Type' => $mime]);
        }

        // Service workers must be served from the root scope and never cached
        // aggressively so updates are picked up quickly.
        if ($name === 'sw.js') {
            $response->headers->set('Service-Worker-Allowed', '/');
            $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
        } elseif (str_starts_with($name, 'workbox-') && str_ends_with($name, '.js')) {
            $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
        } else {
            $response->headers->set('Cache-Control', 'public, max-age=3600');
        }

        return $response;
    }

    /**
     * Whether Symfony's BinaryFileResponse is safe to use for this file. We
     * avoid it for the SPA shell so future tweaks (e.g. URL rewriting) stay
     * straightforward.
     */
    private function shouldStream(string $name): bool
    {
        return ! in_array($name, ['index.html', '200.html', '404.html'], true);
    }

    private function mimeType(string $path): string
    {
        static $map = [
            'html' => 'text/html; charset=UTF-8',
            'htm' => 'text/html; charset=UTF-8',
            'css' => 'text/css; charset=UTF-8',
            'js' => 'application/javascript; charset=UTF-8',
            'mjs' => 'application/javascript; charset=UTF-8',
            'json' => 'application/json; charset=UTF-8',
            'webmanifest' => 'application/manifest+json',
            'xml' => 'application/xml; charset=UTF-8',
            'txt' => 'text/plain; charset=UTF-8',
            'svg' => 'image/svg+xml',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'ico' => 'image/x-icon',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'otf' => 'font/otf',
            'eot' => 'application/vnd.ms-fontobject',
            'map' => 'application/json',
        ];

        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        return $map[$ext] ?? 'application/octet-stream';
    }
}
