<?php

use Core\View\Smarty\SmartyAdapter;
use Core\Interfaces\SmartyConfig;
use Core\View\Smarty\SmartyConfigGeneric;
use Core\Interfaces\ViewTopology;
use Core\View\ViewTopologyGeneric;
use Core\Interfaces\ViewAdapter;
use Core\View\WebPageGeneric;
use Core\Testing\MegaFactory;
use Psr\SimpleCache\CacheInterface;

require_once 'vendor/autoload.php';
require_once __DIR__ . '/../vendor/autoload.php';

class ViewTest extends PHPUnit\Framework\TestCase
{

    private string $compiledDirectory;
    private string $testsDirectory;
    private MegaFactory $megaFactory;

    protected function setUp(): void
    {
        $this->testsDirectory = getcwd() . DIRECTORY_SEPARATOR . 'tests';
        $this->compiledDirectory = $this->testsDirectory . DIRECTORY_SEPARATOR . 'compiled';
        $this->megaFactory = new MegaFactory($this->testsDirectory);
        $this->megaFactory->mkdir($this->compiledDirectory);
    }

    public function testConfig()
    {

        $defConfig = [
            'leftDelimiter' => '{',
            'rightDelimiter' => '}',
            'compiledDir' => '',
            'errorReporting' => E_ALL,
            'plugins' => [],
        ];

        $config = new SmartyConfigGeneric();

        $this->assertEquals($defConfig, $config->getConfig());

        $config->setCompiledDir('.');
        $config->setDelimiters('[[', ']]');
        $config->setErrorReporting(E_ALL);
        $config->setPluginsDir('.');
        $config->setPluginsDir('..');

        $this->assertEquals('.', $config->getCompiledDir());
        $this->assertEquals('[[', $config->getLeftDelimiter());
        $this->assertEquals(']]', $config->getRightDelimiter());
        $this->assertEquals(E_ALL, $config->getErrorReporting());
        $this->assertEquals(['.', '..'], $config->getPluginsDir());
    }

    public function testSmarty()
    {
        $adapter = $this->getSmartyAdapter();
        $view = $adapter->getView();
        $view->assign('one', 'value1');
        $view->assign('two', 'value2');
        $fetched = $view->fetch('testlayout.tpl');
        $this->assertEquals('value1value2https://example.com/jshttps://example.com/theme', $fetched);
        $view->clear();
        $view->assign('one', 'value1_1');
        $view->assign('two', 'value2_2');
        $response = $view->render('testlayout.tpl', [], 201);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('value1_1value2_2https://example.com/jshttps://example.com/theme', (string) $response->getBody());
    }

    public function testSmartyWriteCache()
    {
        $cache = $this->megaFactory->getCache()->getFileCache();
        $adapter = $this->getSmartyAdapter($cache);
        $view = $adapter->getView();
        $view->assign('one', 'value1');
        $view->assign('two', 'value2');
        $response = $view->render('testlayout.tpl', [], 200, [], 0);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('value1value2https://example.com/jshttps://example.com/theme', (string) $response->getBody());
        return $cache;
    }

    /**
     * @depends testSmartyWriteCache
     */
    public function testSmartyGetCache(CacheInterface $cache)
    {
        $adapter = $this->getSmartyAdapter($cache);
        $view = $adapter->getView();
        $response = $view->renderFromCache();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('value1value2https://example.com/jshttps://example.com/theme', (string) $response->getBody());
    }

    public function getSmartyAdapter(CacheInterface $cache = null): ViewAdapter
    {
        if (empty($cache)) {
            $cache = $this->megaFactory->getCache()->getFileCache();
        }
        $logger = $this->megaFactory->getLogger(true, 'test.log');

        $config = $this->getSmartyConfig();
        $viewTopology = $this->getViewTopology();
        $webPage = new WebPageGeneric($viewTopology);
        $request = $this->megaFactory->getServer()->getServerRequest('https://example.com/page/open', 'GET');
        $responseFactory = $this->megaFactory->getServer()->getResponseFactory();

        return new SmartyAdapter($config, $viewTopology, $webPage, $request, $responseFactory, $cache, $logger);
    }

    public function getViewTopology(): ViewTopology
    {
        $viewTopology = new ViewTopologyGeneric();
        $viewTopology->setBaseUrl('https://example.com')
                ->setCssUrl('https://example.com/css')
                ->setFontsUrl('https://example.com/fonts')
                ->setImagesUrl('https://https://example.com/images')
                ->setJsUrl('https://example.com/js')
                ->setLibsUrl('https://example.com/libs')
                ->setThemeUrl('https://example.com/theme')
                ->setTemplatePath($this->testsDirectory . DIRECTORY_SEPARATOR . 'mock_templates');
        return $viewTopology;
    }

    public function getSmartyConfig(): SmartyConfig
    {
        $smartyConfig = new SmartyConfigGeneric();
        $smartyConfig->setCompiledDir($this->compiledDirectory);
        return $smartyConfig;
    }

}
