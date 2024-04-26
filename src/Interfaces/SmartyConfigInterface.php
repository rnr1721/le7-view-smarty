<?php

declare(strict_types=1);

namespace Core\Interfaces;

interface SmartyConfigInterface
{

    /**
     * Set both left and right delimiters for Smarty
     * @param string $left Left delimiter
     * @param string $right Right delimiter
     * @return self
     */
    public function setDelimiters(string $left, string $right): self;

    /**
     * Directory for Smarty compiled files
     * @param string $compiledDir
     * @return self
     */
    public function setCompiledDir(string $compiledDir): self;

    /**
     * Set error reporting level
     * @param int $param Error reporting level
     * @return self
     */
    public function setErrorReporting(int $param = E_ALL): self;

    /**
     * Set system plugins directory
     * @param string|array $dir Directory
     * @return self
     */
    public function setPluginsDir(string|array $dir): self;

    /**
     * Register plugin in smarty
     * 
     * @param string $type Type of smarty plugin
     * @param string $name Name of plugin
     * @param callable $callback Plugin code
     * @param bool $cacheable
     * @return self
     */
    public function registerPlugin(
            string $type,
            string $name,
            $callback,
            bool $cacheable = true
    ): self;

    /**
     * Get config as single array
     * @return array
     */
    public function getConfig(): array;

    /**
     * Get Smarty left delimiter
     * @return string
     */
    public function getLeftDelimiter(): string;

    /**
     * Get Smarty right delimiter
     * @return string
     */
    public function getRightDelimiter(): string;

    /**
     * Get directory to store compiled files
     * @return string
     */
    public function getCompiledDir(): string;

    /**
     * Get error reporting level
     * @return int
     */
    public function getErrorReporting(): int;

    /**
     * Get application plugins directory
     * @return array
     */
    public function getPluginsDir(): array;

    /**
     * Get all registered plugins
     * 
     * @return array All registered plugins
     */
    public function getRegisteredPlugins(): array;
}
