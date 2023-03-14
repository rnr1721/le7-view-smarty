<?php

namespace App\View\Smarty;

use App\View\Interfaces\View;
use \Smarty;
use \SmartyException;
use \Exception;
use function error_log;

class SmartyView implements View
{

    private Smarty $smarty;

    public function __construct(Smarty $smarty)
    {
        $this->smarty = $smarty;
    }

    public function render(string $layout, array $vars = []): string
    {
        try {
            $this->smarty->assign($vars);
            return $rendered = $this->smarty->fetch($layout);
        } catch (SmartyException | Exception $e) {
            error_log((string) $e);
        }
        return '';
    }

}
