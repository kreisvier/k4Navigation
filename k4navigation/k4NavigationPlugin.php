<?php
namespace Craft;


class k4NavigationPlugin extends BasePlugin
{
    public function init(){
        require_once "vendor/simple_html_dom.php";
    }
    
    public function getName()
    {
         return Craft::t('k4 Navigation');
    }

    public function getVersion()
    {
        return '1.0';
    }

    public function getDeveloper()
    {
        return 'kreisvier communications';
    }

    public function getDeveloperUrl()
    {
        return 'http://www.kreisvier.ch';
    }

    public function hasCpSection()
    {
        return false;
    }

    public function addTwigExtension()
    {
        Craft::import('plugins.k4navigation.twigextensions.k4NavigationTwigExtension');

        return new k4NavigationTwigExtension();
    }
}
