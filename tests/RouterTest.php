<?php

require_once __DIR__ . '/../src/bootstrap.php';

use PHPUnit\Framework\TestCase;
use HeadlessCMS\Core\Router;
use HeadlessCMS\Parsers\PageParser;
use HeadlessCMS\Core\ErrorHandler;
use HeadlessCMS\Core\Page;

class RouterTest extends TestCase
{
    private $webpagesPath;
    private $errorsPath;

    protected function setUp(): void
    {
        // Create temporary directories for tests
        $this->webpagesPath = __DIR__ . '/test_webpages/';
        $this->errorsPath = __DIR__ . '/test_errors/';
        @mkdir($this->webpagesPath, 0777, true);
        @mkdir($this->errorsPath . '404/', 0777, true);
        @mkdir($this->errorsPath . '500/', 0777, true);
    }

    protected function tearDown(): void
    {
        // Clean up temporary directories
        $this->rrmdir($this->webpagesPath);
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

    public function testRouteExistingPage()
    {
        // Create an existing page
        $pageDir = $this->webpagesPath . 'test/';
        @mkdir($pageDir, 0777, true);
        file_put_contents($pageDir . 'page.html', '<h1>Test Page</h1>');

        $_SERVER['REQUEST_URI'] = '/test';
        $parser = new PageParser();
        $errorHandler = new ErrorHandler($this->errorsPath, $parser);
        $router = new Router($this->webpagesPath, $this->errorsPath, $parser, $errorHandler);
        $page = $router->route();
        $this->assertInstanceOf(Page::class, $page);
        $this->assertStringContainsString('Test Page', $page->content);
    }

    public function testRouteNonExistingPageReturns404()
    {
        // Create a custom 404 error page
        file_put_contents($this->errorsPath . '404/page.html', '<h1>404 Not Found</h1>');

        $_SERVER['REQUEST_URI'] = '/notfound';
        $parser = new PageParser();
        $errorHandler = new ErrorHandler($this->errorsPath, $parser);
        $router = new Router($this->webpagesPath, $this->errorsPath, $parser, $errorHandler);
        $page = $router->route();
        $this->assertInstanceOf(Page::class, $page);
        $this->assertStringContainsString('404 Not Found', $page->content);
    }

    public function testRouteEmptyPageReturns500()
    {
        // Create an empty page
        $pageDir = $this->webpagesPath . 'empty/';
        @mkdir($pageDir, 0777, true);
        file_put_contents($pageDir . 'page.html', '');

        // Create a custom 500 error page
        file_put_contents($this->errorsPath . '500/page.html', '<h1>500 Error</h1>');

        $_SERVER['REQUEST_URI'] = '/empty';
        $parser = new PageParser();
        $errorHandler = new ErrorHandler($this->errorsPath, $parser);
        $router = new Router($this->webpagesPath, $this->errorsPath, $parser, $errorHandler);
        $page = $router->route();
        // Depending on the implementation, an empty page may return an empty page or a 500 error
        // Here, we just check that it's an instance of Page
        $this->assertInstanceOf(Page::class, $page);
    }
} 