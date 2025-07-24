<?php

namespace HeadlessCMS\Core;

use HeadlessCMS\Core\Page;
use HeadlessCMS\Parsers\PageParser;

class ErrorHandler
{
    private string $errorsPath;
    private PageParser $parser;

    public function __construct(string $errorsPath, PageParser $parser)
    {
        $this->errorsPath = $errorsPath;
        $this->parser = $parser;
    }

    public function handleError(int $errorCode): Page
    {
        http_response_code($errorCode);

        $errorDirPath = $this->errorsPath . '/' . $errorCode . '/';

        if (is_file($errorDirPath . 'page.html')) {
            $rawContent = @file_get_contents($errorDirPath . 'page.html');
            if ($rawContent === false) {
                exit;
            }
            $parsed = $this->parser->parsePageContent($rawContent);
            return new Page($errorDirPath, $parsed['content'], $parsed['settings']);
        }

        return new Page($errorDirPath, "<p style='text-align:center;'>Error {$errorCode}</p>", null);
    }
}