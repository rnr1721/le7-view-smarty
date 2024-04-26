<?php

use Core\Interfaces\ViewAdapterInterface;
use Core\Interfaces\ViewTopologyInterface;
use Core\Interfaces\SmartyConfigInterface;
use Core\View\Smarty\SmartyAdapter;
use Core\View\Smarty\SmartyConfigGeneric;
use Core\View\ViewTopologyGeneric;
use Core\View\AssetsCollectionGeneric;
use Core\View\WebPageGeneric;
use Core\Testing\MegaFactory;
use DI\ContainerBuilder;
use Core\EventDispatcher\Providers\ListenerProviderDefault;
use Core\EventDispatcher\EventDispatcher;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
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
            'registered_plugins' => []
        ];

        $config = new SmartyConfigGeneric();

        $this->assertEquals($defConfig, $config->getConfig());

        $config->setCompiledDir('.');
        $config->setDelimiters('[[', ']]');
        $config->setErrorReporting(E_ALL);
        $config->setPluginsDir('.');
        $config->setPluginsDir('..');
        $config->registerPlugin('modifier', '_', '_');

        $this->assertEquals('.', $config->getCompiledDir());
        $this->assertEquals('[[', $config->getLeftDelimiter());
        $this->assertEquals(']]', $config->getRightDelimiter());
        $this->assertEquals(E_ALL, $config->getErrorReporting());
        $this->assertEquals(['.', '..'], $config->getPluginsDir());
        $this->assertEquals([
            [
                'type' => 'modifier',
                'name' => '_',
                'callback' => '_',
                'cacheable' => true
            ]
                ], $config->getRegisteredPlugins());
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

    public function getSmartyAdapter(CacheInterface $cache = null): ViewAdapterInterface
    {
        if (empty($cache)) {
            $cache = $this->megaFactory->getCache()->getFileCache();
        }

        $config = $this->getSmartyConfig();
        $viewTopology = $this->getViewTopology();

        $ac = new AssetsCollectionGeneric();
        $webPage = new WebPageGeneric($viewTopology, $ac);
        $request = $this->megaFactory->getServer()->getServerRequest('https://example.com/page/open', 'GET');
        $responseFactory = $this->megaFactory->getServer()->getResponseFactory();
        $eventDispatcher = $this->getEventDispatcher();

        return new SmartyAdapter($config, $viewTopology, $webPage, $request, $responseFactory, $cache, $eventDispatcher);
    }

    public function getViewTopology(): ViewTopologyInterface
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

    public function getSmartyConfig(): SmartyConfigInterface
    {
        $smartyConfig = new SmartyConfigGeneric();
        $smartyConfig->setCompiledDir($this->compiledDirectory);
        return $smartyConfig;
    }

    public function getEventDispatcher(): EventDispatcherInterface
    {
        $lp = new ListenerProviderDefault();
        return new EventDispatcher($lp, $this->getContainer());
    }

    public function getContainer(): ContainerInterface
    {
        $cb = new ContainerBuilder();
        $cb->addDefinitions();
        return $cb->build();
    }
}
