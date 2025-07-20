<?php

require_once __DIR__ . '/../src/bootstrap.php';

use PHPUnit\Framework\TestCase;
use HeadlessCMS\Core\Page;

class PageTest extends TestCase
{
    public function testPageContentIsSet()
    {
        $page = new Page('/fake/path/', '<h1>Hello</h1>', null);
        $this->assertEquals('<h1>Hello</h1>', $page->content);
    }

    public function testPageSettingsAreParsed()
    {
        $settings = ['title' => 'Test Title', 'description' => 'Test Desc'];
        $page = new Page('/fake/path/', '<h1>Hello</h1>', $settings);
        $this->assertEquals('Test Title', $page->settings['title']);
        $this->assertEquals('Test Desc', $page->settings['description']);
    }

    public function testGetPropertyReturnsTitleTag()
    {
        $settings = ['title' => 'Test Title'];
        $page = new Page('/fake/path/', '<h1>Hello</h1>', $settings);
        $this->assertStringContainsString('<title>Test Title</title>', $page->get_property('title'));
    }
}