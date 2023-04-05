<?php

declare(strict_types=1);

namespace Core\Interfaces;

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
}