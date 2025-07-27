<?php

require_once __DIR__ . '/../src/bootstrap.php';

use PHPUnit\Framework\TestCase;
use HeadlessCMS\Core\ErrorHandler;
use HeadlessCMS\Parsers\PageParser;
use HeadlessCMS\Core\Page;

class ErrorHandlerTest extends TestCase
{
    private $errorsPath;

    protected function setUp(): void
    {
        // Create temporary directory for tests
        $this->errorsPath = __DIR__ . '/test_errors/';
        @mkdir($this->errorsPath, 0777, true);
    }

    protected function tearDown(): void
    {
        // Clean up temporary directories
        $this->rrmdir($this->errorsPath);
    }

    private function rrmdir($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    $path = $dir . DIRECTORY_SEPARATOR . $object;
                    if (is_dir($path))
                        $this->rrmdir($path);
                    else
                        unlink($path);
                }
            }
            rmdir($dir);
        }
    }

    public function testHandleErrorWithCustom404Page()
    {
        // Create a custom 404 error page
        $errorDir = $this->errorsPath . '404/';
        @mkdir($errorDir, 0777, true);
        file_put_contents($errorDir . 'page.html', '<h1>Page Not Found</h1>');

        $parser = new PageParser();
        $errorHandler = new ErrorHandler($this->errorsPath, $parser);
        $page = $errorHandler->handleError(404);

        $this->assertInstanceOf(Page::class, $page);
        $this->assertStringContainsString('Page Not Found', $page->content);
    }

    public function testHandleErrorWithCustom500Page()
    {
        // Create a custom 500 error page
        $errorDir = $this->errorsPath . '500/';
        @mkdir($errorDir, 0777, true);
        file_put_contents($errorDir . 'page.html', '<h1>Server Error</h1>');

        $parser = new PageParser();
        $errorHandler = new ErrorHandler($this->errorsPath, $parser);
        $page = $errorHandler->handleError(500);

        $this->assertInstanceOf(Page::class, $page);
        $this->assertStringContainsString('Server Error', $page->content);
    }

    public function testHandleErrorWithCustomPageAndSettings()
    {
        // Create an error page with settings
        $errorDir = $this->errorsPath . '404/';
        @mkdir($errorDir, 0777, true);
        $content = "Title: 404 Error\nDescription: Page not found\n=================\n<h1>Custom 404</h1>";
        file_put_contents($errorDir . 'page.html', $content);

        $parser = new PageParser();
        $errorHandler = new ErrorHandler($this->errorsPath, $parser);
        $page = $errorHandler->handleError(404);

        $this->assertInstanceOf(Page::class, $page);
        $this->assertStringContainsString('Custom 404', $page->content);
        $this->assertEquals('404 Error', $page->settings['title']);
        $this->assertEquals('Page not found', $page->settings['description']);
    }

    public function testHandleErrorWithDefaultFallback()
    {
        // Do not create a custom error page
        $parser = new PageParser();
        $errorHandler = new ErrorHandler($this->errorsPath, $parser);
        $page = $errorHandler->handleError(404);

        $this->assertInstanceOf(Page::class, $page);
        $this->assertStringContainsString('Error 404', $page->content);
    }

    public function testHandleErrorWithEmptyCustomPage()
    {
        // Create an empty error page
        $errorDir = $this->errorsPath . '404/';
        @mkdir($errorDir, 0777, true);
        file_put_contents($errorDir . 'page.html', '');

        $parser = new PageParser();
        $errorHandler = new ErrorHandler($this->errorsPath, $parser);
        $page = $errorHandler->handleError(404);

        $this->assertInstanceOf(Page::class, $page);
        $this->assertEquals('', $page->content);
    }
} 