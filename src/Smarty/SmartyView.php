<?php

namespace Core\View\Smarty;

use Core\Interfaces\View;
use Core\Interfaces\WebPage;
use Core\View\ViewTrait;
use Core\View\ViewException;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\SimpleCache\CacheInterface;
use \Smarty;
use \Throwable;

class SmartyView implements View
{

    use ViewTrait;

    private Smarty $smarty;
    private WebPage $webPage;

    public function __construct(
            Smarty $smarty,
            WebPage $webPage,
            ServerRequestInterface $request,
            ResponseInterface $response,
            CacheInterface $cache,
            EventDispatcherInterface $eventDispatcher
    )
    {
        $this->smarty = $smarty;
        $this->webPage = $webPage;
        $this->request = $request;
        $this->response = $response;
        $this->cache = $cache;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * 
     * @param string $layout
     * @param array<array-key, string> $vars
     * @return string
     */
    public function fetch(string $layout, array $vars = []): string
    {
        try {

            $this->assign($this->webPage->getWebpage());
            $this->assign($vars);
            $this->smarty->assign($this->vars);
            $this->clear();
            return $rendered = $this->smarty->fetch($layout);
        } catch (Throwable $e) {
            throw new ViewException($e->getMessage());
        }
        return '';
    }

}
