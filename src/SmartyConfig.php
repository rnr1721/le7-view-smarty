<?php

declare(strict_types=1);

namespace App\View\Smarty;

interface SmartyConfig
{

    /**
     * Set both left and right delimiters for Smarty
     * @param string $left Left delimiter
     * @param string $right Right delimiter
     * @return self
     */
    public function setDelimiters(string $left, string $right): self;

    /**
     * Template directories
     * @param string|array $dir Directory with templates
     * @return self
     */
    public function setTemplateDirs(string|array $dir): self;

    /**
     * Directory for store cached files
     * @param string $cacheDir Directory
     * @return self
     */
    public function setCacheDir(string $cacheDir): self;

    /**
     * Directory for Smarty compiled files
     * @param string $compiledDir
     * @return self
     */
    public function setCompiledDir(string $compiledDir): self;

    /**
     * Smarty cache lifetime in seconds
     * @param int $value Cache Lifetime
     * @return self
     */
    public function setCaching(int $value): self;

    /**
     * Set error reporting level
     * @param int $param Error reporting level
     * @return self
     */
    public function setErrorReporting(int $param = E_ALL): self;

    /**
     * Set system plugins directory
     * @param string $dir Directory
     * @return self
     */
    public function setPluginSystemDir(string $dir): self;

    /**
     * Set application plugins directory
     * @param string $dir Directory
     * @return self
     */
    public function setPluginAppDir(string $dir): self;

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
     * Get array of paths with template directories
     * @return array
     */
    public function getTemplateDirs(): array;

    /**
     * Get directory to store cache files
     * @return string
     */
    public function getCacheDir(): string;

    /**
     * Get directory to store compiled files
     * @return string
     */
    public function getCompiledDir(): string;

    /**
     * Get cache lifetime
     * @return int
     */
    public function getCaching(): int;

    /**
     * Get error reporting level
     * @return int
     */
    public function getErrorReporting(): int;

    /**
     * Get system plugins directory
     * @return string
     */
    public function getPluginSystemDir(): string;

    /**
     * Get application plugins directory
     * @return string
     */
    public function getPluginAppDir(): string;
}
