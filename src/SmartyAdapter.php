<?php

declare(strict_types=1);

namespace Core\View\Smarty;

use Core\View\Interfaces\View;
use Core\View\Interfaces\ViewAdapter;
use \Smarty;

class SmartyAdapter implements ViewAdapter
{

    private SmartyConfig $config;

    public function __construct(SmartyConfig $smartyConfig)
    {
        $this->config = $smartyConfig;
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
        $smarty->setTemplateDir($this->config->getTemplateDirs());
        $smarty->setCacheDir($this->config->getCacheDir());
        $smarty->setCompileDir($this->config->getCompiledDir());
        $smarty->setCaching($this->config->getCaching());
        $smarty->setErrorReporting($this->config->getErrorReporting());
        $smarty->setPluginsDir($this->config->getPluginSystemDir());
        $smarty->addPluginsDir($this->config->getPluginAppDir());
        return new SmartyView($smarty);
    }

}
