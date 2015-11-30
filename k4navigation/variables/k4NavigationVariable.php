<?php
namespace Craft;

class k4NavigationVariable
{
    public function getNavigation($sectionHandle)
    {      
        $oldPath = craft()->path->getTemplatesPath();
        $newPath = craft()->path->getPluginsPath().'k4navigation/templates';
        craft()->path->setTemplatesPath($newPath);
        $html = craft()->templates->render('k4Navigation',["sectionHandle"=>$sectionHandle]);
        craft()->path->setTemplatesPath($oldPath);
        return $html;
    }
}