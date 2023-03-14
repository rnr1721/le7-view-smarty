<?php

use App\View\Smarty\SmartyConfigGeneric;
use App\View\Smarty\SmartyAdapter;

require_once 'vendor/autoload.php';
require_once __DIR__ . '/../vendor/autoload.php';

class ViewTest extends PHPUnit\Framework\TestCase
{

    private SmartyConfigGeneric $config;

    protected function setUp(): void
    {
        $this->config = new SmartyConfigGeneric();
    }

    public function testConfig()
    {

        $defConfig = [
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

        $currConfig = $this->config->getConfig();

        $this->assertEquals($defConfig, $currConfig);

        $this->config->setCacheDir('.');
        $this->config->setCaching(3600);
        $this->config->setCompiledDir('.');
        $this->config->setDelimiters('[[', ']]');
        $this->config->setErrorReporting(E_ALL);
        $this->config->setPluginAppDir('.');
        $this->config->setPluginSystemDir('.');
        $this->config->setTemplateDirs(['./dir1', './dir2']);
        $this->config->setTemplateDirs('./dir3');

        $this->assertEquals('.', $this->config->getCacheDir());
        $this->assertEquals(3600, $this->config->getCaching());
        $this->assertEquals('.', $this->config->getCompiledDir());
        $this->assertEquals('[[', $this->config->getLeftDelimiter());
        $this->assertEquals(']]', $this->config->getRightDelimiter());
        $this->assertEquals(E_ALL, $this->config->getErrorReporting());
        $this->assertEquals('.', $this->config->getPluginSystemDir());
        $this->assertEquals('.', $this->config->getPluginAppDir());
        $this->assertEquals(['./dir1', './dir2', './dir3'], $this->config->getTemplateDirs());
    }

    public function testView()
    {

        $testsDir = getcwd() . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR;

        $templatesDir = $testsDir . 'mock_templates';

        $compiledDir = $testsDir . 'smarty_compiled';

        if (!file_exists($compiledDir)) {
            mkdir($compiledDir, 0777, true);
        }

        $this->config->setCompiledDir($compiledDir);

        $this->config->setTemplateDirs($templatesDir);

        $smatyAdapter = new SmartyAdapter($this->config);
        $view = $smatyAdapter->getView();

        $result = $view->render('testlayout.tpl', ['one' => '17', 'two' => '21']);

        $this->assertEquals('1721', $result);
        
        $filesToDelete = glob($compiledDir . DIRECTORY_SEPARATOR . '*.tpl.php');
        
        foreach ($filesToDelete as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        
        if (file_exists($compiledDir)) {
            rmdir($compiledDir);
        }
        
    }
    
}
