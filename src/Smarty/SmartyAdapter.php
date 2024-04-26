<?php

declare(strict_types=1);

namespace Core\View\Smarty;

use Core\Interfaces\ViewInterface;
use Core\Interfaces\ViewAdapterInterface;
use Core\Interfaces\WebPageInterface;
use Core\Interfaces\ViewTopologyInterface;
use Core\Interfaces\SmartyConfigInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\SimpleCache\CacheInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use \Smarty;

class SmartyAdapter implements ViewAdapterInterface
{

    private SmartyConfigInterface $config;
    private ViewTopologyInterface $viewTopology;
    private WebPageInterface $webPage;
    private ServerRequestInterface $request;
    private ResponseFactoryInterface $responseFactory;
    private CacheInterface $cache;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
            SmartyConfigInterface $smartyConfig,
            ViewTopologyInterface $viewTopology,
            WebPageInterface $webPage,
            ServerRequestInterface $request,
            ResponseFactoryInterface $responseFactory,
            CacheInterface $cache,
            EventDispatcherInterface $eventDispatcher
    )
    {
        $this->config = $smartyConfig;
        $this->viewTopology = $viewTopology;
        $this->webPage = $webPage;
        $this->request = $request;
        $this->responseFactory = $responseFactory;
        $this->cache = $cache;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Get configured instance of View interface, that have a render() method
     * @param array|string|null $templatePath
     * @param ResponseInterface|null $response
     * @return ViewInterface
     */
    public function getView(array|string|null $templatePath = null, ?ResponseInterface $response = null): ViewInterface
    {

        if ($response === null) {
            $response = $this->responseFactory->createResponse(404);
        }

        if ($templatePath === null) {
            $templatePath = $this->viewTopology->getTemplatePath();
        }

        $smarty = new Smarty();
        $smarty->setLeftDelimiter($this->config->getLeftDelimiter());
        $smarty->setRightDelimiter($this->config->getRightDelimiter());
        $smarty->setTemplateDir($templatePath);
        $smarty->setCompileDir($this->config->getCompiledDir());
        $smarty->setErrorReporting($this->config->getErrorReporting());
        $smarty->setPluginsDir($this->config->getPluginsDir());

        $registeredPlugins = $this->config->getRegisteredPlugins();
        foreach ($registeredPlugins as $registeredPlugin) {
            $smarty->registerPlugin(
                    $registeredPlugin['type'],
                    $registeredPlugin['name'],
                    $registeredPlugin['callback'],
                    $registeredPlugin['cacheable']
            );
        }
        // It will be not native smarty cache
        $smarty->setCaching(0);
        return new SmartyView(
                $smarty,
                $this->webPage,
                $this->request,
                $response,
                $this->cache,
                $this->eventDispatcher
        );
    }
}
