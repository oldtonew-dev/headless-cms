<?php

require_once __DIR__ . '/../src/bootstrap.php';

use PHPUnit\Framework\TestCase;
use HeadlessCMS\Parsers\PageParser;

class PageParserTest extends TestCase
{
    public function testParsePageContentWithSettings()
    {
        $rawContent = "Title: Test Title\nDescription: Test Desc\n=================\n<h1>Hello</h1>";
        $parser = new PageParser();
        $result = $parser->parsePageContent($rawContent);
        $this->assertEquals('Test Title', $result['settings']['title']);
        $this->assertEquals('Test Desc', $result['settings']['description']);
        $this->assertEquals('<h1>Hello</h1>', $result['content']);
    }

    public function testParsePageContentWithoutSettings()
    {
        $rawContent = "<h1>Just Content</h1>";
        $parser = new PageParser();
        $result = $parser->parsePageContent($rawContent);
        $this->assertNull($result['settings']);
        $this->assertEquals('<h1>Just Content</h1>', $result['content']);
    }

    public function testParsePageContentWithMalformedSettings()
    {
        $rawContent = "Title Test Title\nDescription:Test Desc\n=================\n<h1>Hello</h1>";
        $parser = new PageParser();
        $result = $parser->parsePageContent($rawContent);
        // Title mal formé ne doit pas être pris en compte
        $this->assertArrayNotHasKey('title', $result['settings'] ?? []);
        $this->assertEquals('Test Desc', $result['settings']['description']);
        $this->assertEquals('<h1>Hello</h1>', $result['content']);
    }
} 