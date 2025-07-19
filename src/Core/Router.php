<?php

namespace HeadlessCMS\Core;

class Router
{
    private string $webpagesPath;
    private string $errorsPath;
    
    public function __construct(string $webpagesPath = null, string $errorsPath = null)
    {
        $this->webpagesPath = $webpagesPath ?? __DIR__ . '/../../webpages';
        $this->errorsPath = $errorsPath ?? __DIR__ . '/../../errors';
    }
    
    public function route(): Page
    {
        $requestedPath = $this->getRequestedPath();
        $normalizedPath = $this->normalizePath($requestedPath);
        $pagePath = $this->webpagesPath . $normalizedPath;
        
        if (!$this->pageExists($pagePath)) {
            return $this->handleError(404);
        }
        
        return $this->loadPage($pagePath);
    }
    
    private function getRequestedPath(): string
    {
        return parse_url($_SERVER['REQUEST_URI'])['path'] ?? '/';
    }
    
    private function normalizePath(string $path): string
    {
        // Ensure there is a trailing slash
        if (!str_ends_with($path, '/')) {
            $path .= '/';
        }
        return $path;
    }
    
    private function pageExists(string $pagePath): bool
    {
        return is_file($pagePath . 'page.html');
    }
    
    private function loadPage(string $pagePath): Page
    {
        $rawContent = $this->getPageContent($pagePath);
        
        if ($rawContent === false) {
            return $this->handleError(500);
        }
        
        if ($rawContent === '') {
            return new Page($pagePath, '', null);
        }
        
        return $this->parsePageContent($pagePath, $rawContent);
    }
    
    private function getPageContent(string $pagePath): string|false
    {
        try {
            return file_get_contents($pagePath . 'page.html');
        } catch (\Throwable $th) {
            return false;
        }
    }
    
    private function parsePageContent(string $pagePath, string $rawContent): Page
    {
        // TODO: Move this logic to a separate PageParser class
        list($hasSettings, $pageParts) = $this->parsePageContentParts($rawContent);
        
        if ($hasSettings && count($pageParts) === 1) {
            return new Page($pagePath, '', $pageParts[0]);
        }
        
        if (count($pageParts) !== 2) {
            return new Page($pagePath, $pageParts[0], null);
        }
        
        return new Page($pagePath, $pageParts[1], $pageParts[0]);
    }
    
    private function parsePageContentParts(string $content): array
    {
        $hasSettings = preg_match('/^.[=]+([\s]+)?$/m', $content) > 0;
        $split = preg_split('/^.[=]+([\s]+)?$/m', $content);
        return [$hasSettings, $split];
    }
    
    private function handleError(int $errorCode): Page
    {
        http_response_code($errorCode);
        
        $errorDirPath = $this->errorsPath . '/' . $errorCode . '/';
        
        if ($this->pageExists($errorDirPath)) {
            $rawContent = $this->getPageContent($errorDirPath);
            
            if ($rawContent === false) {
                exit;
            }
            
            return $this->parsePageContent($errorDirPath, $rawContent);
        }
        
        return new Page($errorDirPath, "<p style='text-align:center;'>Error {$errorCode}</p>", null);
    }
}