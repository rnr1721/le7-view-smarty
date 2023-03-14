<?php

declare(strict_types=1);

namespace App\View\Smarty;

use function is_string;
use function is_array;

class SmartyConfigGeneric implements SmartyConfig
{

    private array $config = [
        'leftDelimiter' => '{',
        'rightDelimiter' => '}',
        'templateDirs' => [],
        'cacheDir' => '',
        'compiledDir' => '',
        'caching' => 0,
        'errorReporting' => E_ALL,
        'pluginSystemDir' => '',
        'pluginAppDir' => ''
    ];

    public function setDelimiters(string $left, string $right): self
    {
        $this->config['leftDelimiter'] = $left;
        $this->config['rightDelimiter'] = $right;
        return $this;
    }

    public function setTemplateDirs(string|array $dir): self
    {
        if (is_string($dir)) {
            $this->config['templateDirs'][] = $dir;
        }
        if (is_array($dir)) {
            foreach ($dir as $item) {
                if (is_string($item)) {
                    $this->config['templateDirs'][] = $item;
                }
            }
        }
        return $this;
    }

    public function setCacheDir(string $cacheDir): self
    {
        $this->config['cacheDir'] = $cacheDir;
        return $this;
    }

    public function setCompiledDir(string $compiledDir): self
    {
        $this->config['compiledDir'] = $compiledDir;
        return $this;
    }

    public function setCaching(int $value): self
    {
        $this->config['caching'] = $value;
        return $this;
    }

    public function setErrorReporting(int $param = E_ALL): self
    {
        $this->config['errorReporting'] = $param;
        return $this;
    }

    public function setPluginSystemDir(string $dir): self
    {
        $this->config['pluginSystemDir'] = $dir;
        return $this;
    }

    public function setPluginAppDir(string $dir): self
    {
        $this->config['pluginAppDir'] = $dir;
        return $this;
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    public function getLeftDelimiter(): string
    {
        return $this->config['leftDelimiter'];
    }

    public function getRightDelimiter(): string
    {
        return $this->config['rightDelimiter'];
    }

    public function getTemplateDirs(): array
    {
        return $this->config['templateDirs'];
    }

    public function getCacheDir(): string
    {
        return $this->config['cacheDir'];
    }

    public function getCompiledDir(): string
    {
        return $this->config['compiledDir'];
    }

    public function getCaching(): int
    {
        return $this->config['caching'];
    }

    public function getErrorReporting(): int
    {
        return $this->config['errorReporting'];
    }

    public function getPluginSystemDir(): string
    {
        return $this->config['pluginSystemDir'];
    }

    public function getPluginAppDir(): string
    {
        return $this->config['pluginAppDir'];
    }

}
