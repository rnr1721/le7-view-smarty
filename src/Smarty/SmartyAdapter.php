<?php

declare(strict_types=1);

namespace Core\View\Smarty;

use Core\Interfaces\View;
use Core\Interfaces\ViewAdapter;
use Core\Interfaces\WebPage;
use Core\Interfaces\ViewTopology;
use Core\Interfaces\SmartyConfig;
use Psr\SimpleCache\CacheInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use \Smarty;

class SmartyAdapter implements ViewAdapter
{

    private SmartyConfig $config;
    private ViewTopology $viewTopology;
    private WebPage $webPage;
    private ServerRequestInterface $request;
    private ResponseInterface $response;
    private CacheInterface $cache;
    private LoggerInterface $logger;

    public function __construct(
            SmartyConfig $smartyConfig,
            ViewTopology $viewTopology,
            WebPage $webPage,
            ServerRequestInterface $request,
            ResponseInterface $response,
            CacheInterface $cache,
            LoggerInterface $logger
    )
    {
        $this->config = $smartyConfig;
        $this->viewTopology = $viewTopology;
        $this->webPage = $webPage;
        $this->request = $request;
        $this->response = $response;
        $this->cache = $cache;
        $this->logger = $logger;
    }

    /**
     * Get configured instance of View interface, that have a render() method
     * @return View
     */
    public function getView(): View
    {

        $smarty = new Smarty();
        $smarty->setLeftDelimiter($this->config->getLeftDelimiter());
        $smarty->setRightDelimiter($this->config->getRightDelimiter());
        $smarty->setTemplateDir($this->viewTopology->getTemplatePath());
        $smarty->setCompileDir($this->config->getCompiledDir());
        $smarty->setErrorReporting($this->config->getErrorReporting());
        $smarty->setPluginsDir($this->config->getPluginsDir());
        // It will be not native smarty cache
        $smarty->setCaching(0);
        return new SmartyView(
                $smarty,
                $this->webPage,
                $this->request,
                $this->response,
                $this->cache,
                $this->logger
        );
    }

}
