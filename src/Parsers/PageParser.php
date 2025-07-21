<?php

namespace HeadlessCMS\Parsers;

class PageParser
{
    public function parsePageContent(string $rawContent): array
    {
        list($hasSettings, $pageParts) = $this->parsePageContentParts($rawContent);
        $settings = null;
        $content = '';
        if ($hasSettings && count($pageParts) === 2) {
            $settings = $this->parseRawSettingsBlock($pageParts[0]);
            $content = trim($pageParts[1]);
        } else {
            $content = trim($pageParts[0]);
        }
        return ['settings' => $settings, 'content' => $content];
    }
    
    private function parsePageContentParts(string $content): array
    {
        $hasSettings = preg_match('/^.[=]+([\s]+)?$/m', $content) > 0;
        $split = preg_split('/^.[=]+([\s]+)?$/m', $content);
        return [$hasSettings, $split];
    }
    
    private function parseRawSettingsBlock(string $raw_settings_block): array {
    
        $raw_settings_block = preg_replace("/<!--(.*?)-->/", "", $raw_settings_block);
    
        $temp_settings = array();
    
        // Iterate over each new line
        foreach(preg_split("/((\r?\n)|(\r\n?))/", $raw_settings_block) as $line) {
            // Split the line on the first ':' character
            $parts = explode(':', $line);
    
            if(count($parts) == 1) {
                // Then set as key-only setting
                $keyName = strtolower(trim($parts[0]));
    
                // Then key name is invalid
                if($keyName === '') continue;
    
                $temp_settings[$keyName] = true;
                continue;
            }
    
            if(count($parts) !== 2) {
                // Then is malformed settings line
                continue;
            }
    
            // Extract the name (key) of the setting and its respective value
            $key = strtolower(trim($parts[0]));
            $value = trim($parts[1]);
    
            $temp_settings[$key] = $value;
    
        }
    
        return $temp_settings;
    }
}