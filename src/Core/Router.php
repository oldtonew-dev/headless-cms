<?php

namespace HeadlessCMS\Core;

use HeadlessCMS\Parsers\PageParser;
use HeadlessCMS\Core\ErrorHandler;
use HeadlessCMS\Core\Page;

class Router
{
    private string $webpagesPath;
    private string $errorsPath;
    private PageParser $parser;
    private ErrorHandler $errorHandler;
    
    public function __construct(
        ?string $webpagesPath = null, 
        ?string $errorsPath = null, 
        ?PageParser $parser = null, 
        ?ErrorHandler $errorHandler = null
    ){
        $this->webpagesPath = $webpagesPath ?? __DIR__ . '/../../webpages';
        $this->errorsPath = $errorsPath ?? __DIR__ . '/../../errors';
        $this->parser = $parser ?? new PageParser();
        $this->errorHandler = $errorHandler ?? new ErrorHandler($this->errorsPath, $this->parser);
    }
    
    public function route(): Page
    {
        $requestedPath = $this->getRequestedPath();
        $normalizedPath = $this->normalizePath($requestedPath);
        $pagePath = $this->webpagesPath . $normalizedPath;
        
        if (!$this->pageExists($pagePath)) {
            return $this->errorHandler->handleError(404);
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
            return $this->errorHandler->handleError(500);
        }
        
        if ($rawContent === '') {
            return new Page($pagePath, '', null);
        }
        
        $parsed = $this->parser->parsePageContent($rawContent);
        return new Page($pagePath, $parsed['content'], $parsed['settings']);
    }
    
    private function getPageContent(string $pagePath): string|false
    {
        try {
            return file_get_contents($pagePath . 'page.html');
        } catch (\Throwable $th) {
            return false;
        }
    }
}