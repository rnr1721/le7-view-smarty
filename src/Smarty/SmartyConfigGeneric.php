<?php

declare(strict_types=1);

namespace Core\View\Smarty;

use Core\Interfaces\SmartyConfigInterface;

class SmartyConfigGeneric implements SmartyConfigInterface
{

    private array $config = [
        'leftDelimiter' => '{',
        'rightDelimiter' => '}',
        'compiledDir' => '',
        'errorReporting' => E_ALL,
        'plugins' => [],
        'registered_plugins' => []
    ];

    public function setDelimiters(string $left, string $right): self
    {
        $this->config['leftDelimiter'] = $left;
        $this->config['rightDelimiter'] = $right;
        return $this;
    }

    public function setCompiledDir(string $compiledDir): self
    {
        $this->config['compiledDir'] = $compiledDir;
        return $this;
    }

    public function setErrorReporting(int $param = E_ALL): self
    {
        $this->config['errorReporting'] = $param;
        return $this;
    }

    public function setPluginsDir(string|array $dir): self
    {
        if (is_array($dir)) {
            foreach ($dir as $item) {
                $this->addPluginsDir($item);
            }
        } else {
            $this->addPluginsDir($dir);
        }
        return $this;
    }

    private function addPluginsDir(string $dir): void
    {
        $this->config['plugins'][] = $dir;
    }

    public function registerPlugin(
            string $type,
            string $name,
            $callback,
            bool $cacheable = true
    ): self
    {
        $this->config['registered_plugins'][] = [
            'type' => $type,
            'name' => $name,
            'callback' => $callback,
            'cacheable' => $cacheable
        ];
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

    public function getCompiledDir(): string
    {
        return $this->config['compiledDir'];
    }

    public function getErrorReporting(): int
    {
        return $this->config['errorReporting'];
    }

    public function getPluginsDir(): array
    {
        return $this->config['plugins'];
    }

    public function getRegisteredPlugins(): array
    {
        return $this->config['registered_plugins'];
    }
}
