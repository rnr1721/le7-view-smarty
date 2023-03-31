# Simple Smarty view class for le7 framework or any PSR PHP project

## Requirements

- PHP 8.1 or higher.
- Composer 2.0 or higher.

## What it can?

- Render Smarty templates using Smarty template engine
- Use PSR SimpleCache for caching page

## Installation

```shell
composer require rnr1721/le7-view-smarty
```

## Testing

```shell
composer test
```

## How it works?

```php
use Core\Interfaces\ViewTopology;
use Core\Interfaces\ViewAdapter;
use Core\Interfaces\SmartyConfig;

use Core\View\WebPageGeneric;
use Core\View\ViewTopologyGeneric;
use Core\View\Smarty\SmartyAdapter;
use Core\View\Smarty\SmartyConfigGeneric;

use Psr\SimpleCache\CacheInterface;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;

        // At first we need create or have this:
        // ServerRequestInterface
        // $request = ...
        // ResponseInterface
        // $response = ...
        // CacheInterface
        // $cache = ...
        // LoggerInterface
        // $logger = ...

        $smartyConfig = new SmartyConfigGeneric();
        $smartyConfig->setCompiledDir($this->compiledDirectory);
        // $smartyConfig->.... Set other Smarty settings here
        
        $viewTopology = new ViewTopologyGeneric();
        $viewTopology->setBaseUrl('https://example.com')
                // Set urls for access with {$js}, {$css}, {$fonts}, {$theme} etc
                ->setCssUrl('https://example.com/css')
                ->setFontsUrl('https://example.com/fonts')
                ->setImagesUrl('https://https://example.com/images')
                ->setJsUrl('https://example.com/js')
                ->setLibsUrl('https://example.com/libs')
                ->setThemeUrl('https://example.com/theme')
                // Set template directories
                ->setTemplatePath([
                                '/home/www/mysite/templates',
                                '/home/www/mysite/templates2'
                            ]}
        
        // WebPage object what epresents web page
        $webPage = new WebPageGeneric($viewTopology);
        // Set style from CDN
        $webPage->setStyleCdn("https://cdn.example.com/style.css");
        // Set style from /libs
        $webPage->setStyleLibs("mystyle.css");
        // Set style from theme folder
        $webPage->setStyle('mystyle.css');

        // Set script from CDN
        $webpage->setScriptCdn("https://cdn.example.com/script.js");
        // Set script from libs folder
        $webpage->setScriptLibs("myscript.js");
        // Set script from theme folder
        $webPage->setScript("jquert/jquery.min.js");
        // Set script from theme folder for footer
        $webPage->setScript("jquert/jquery.min.js", false);

        $webPage->setPageTitle("My page Title");
        $webPage->setKeywords(["one","two","three"]);
        $webPage->setKeywords("four");
        $webPage->setKeywords("six,seven");

        // Why? Now we will can use in our Smarty template that variables:
        // {$base},{$js},{$css},{$fonts},{$images},{$theme},{$libs} - URL Path for folders
        // {$scripts_header}, {$scripts_footer}, {$styles}, {importmap}
        // {$title}, {$keywords}, {$header}, {$description} etc...

        // Get the Smarty adapter (Core\Interfaces\ViewAdapter)
        $viewAdapter = new SmartyAdapter($smartyConfig, $viewTopology, $webPage, $request, $response, $cache, $logger);

        // Get the view (Core\Interfaces\View)
        $view = $view->getView();

        // Now we can use View
        $vars = [
            'one' => 'one var',
            'two' => 'two var'
            ];

        $view->assign('three', 'three var');

        // Set the layout, variables, response code, headers, cache ttl in sec
        // $response is Psr\Http\Message\ResponseInterface
        $response = $view->render("layout.tpl", $vars, 200, [], 0);

        // Now we can use $response
        $response->getStatusCode();
        $response->getBody()

```
