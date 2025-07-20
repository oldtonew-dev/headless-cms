<?php

namespace HeadlessCMS\Core;

class Page
{
    public $content;
    public $settings;
    private $dir_path;

    public function __construct($dir_path, $page_content, $settings = null)
    {
        $this->content = $page_content;
        $this->dir_path = $dir_path;
        $this->settings = $settings;

        // Path to possible styles.css file
        $styles_path = $this->dir_path . 'styles.css';

        // If there is a styles.css file, then include the styles in the page
        if (is_file($styles_path)) {
            $this->content = "<style>\n\n" . file_get_contents($styles_path) . "\n</style>\n\n" . $this->content;
        }
    }

    public function get_property($property_name)
    {
        // Check this setting exists
        if (isset($this->settings[$property_name])) {
            switch ($property_name) {
                case 'title':
                    return "<title>{$this->settings[$property_name]}</title><meta property='og:title' content='{$this->settings[$property_name]}' />";
                case 'description':
                    return "<meta name='description' content='{$this->settings[$property_name]}'><meta name='og:description' content='{$this->settings[$property_name]}' >";
                case 'og-image':
                    return "<meta property='og:image' content='{$this->settings[$property_name]}' />";
                case 'og-url':
                    return "<meta property='og:url' content='{$this->settings[$property_name]}' />";
                case 'og-type':
                    return "<meta property='og:type' content='{$this->settings[$property_name]}' />";
                case 'favicon':
                    return "<link rel='shortcut icon' type='image' href='{$this->settings[$property_name]}' />";
            }
            return $this->settings[$property_name];
        }
        return '';
    }
}