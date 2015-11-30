<?php 
namespace Craft;

use Twig_Extension;
use Twig_Filter_Method;

class k4NavigationTwigExtension extends \Twig_Extension
{
    public function getName()
    {
        return 'k4 Navigation Helper';
    }

    
    public function getFilters()
    {
        $returnArray = array();
        $methods = array(
            'k4NavigationGetActivePath', 
            'k4NavigationShowFromTo',
            'k4NavigationGetBreadcrumb',
            'k4NavigationGetSimpleNavigation',
            'k4NavigationGetNextSubmenu',
        );

        foreach ($methods as $methodName) {
            $returnArray[$methodName] = new \Twig_Filter_Method($this, $methodName);
        }
        return $returnArray;
    }    
    

    public function k4NavigationGetActivePath($content,$selectedPath)
    {
        $html = str_get_html($content);
       
        $selectedFilter = 'a[href="'.$selectedPath.'"]';

        //set selected link 
        $selectElemArr = $html->find($selectedFilter);

        
        if (is_array($selectElemArr) AND !empty($selectElemArr[0])){

            $selectElem = $selectElemArr[0];
           // Element wurde gefunden - Active Klassen setzen
            for ($i = 1; $i <= 20; $i++) {
                $selectElem->setAttribute("class","active ".  $selectElem->class);
                 $selectElem =  $selectElem->parent();
                
                if (!is_object($selectElem)){
                    break;
                }
            }    
        } else
        {
            $html = $content . "<!-- k4Navigation no active path -->";
        }
    
        return $html;//$this->k4NavigationShowFromTo($html,0,1);
               
    }
    
    public function k4NavigationGetBreadcrumb($content,$selectedPath,$dividerText = " > ")
    {
       
 
        $html = str_get_html($this->k4NavigationGetActivePath($content,$selectedPath));
        
        $breadcrumb = "";
           foreach($html->find('li[class="active"]') as $element){
              $elementLink = $element->find("a")[0];
                if ($elementLink->class == "active"){
                    $breadcrumb = $breadcrumb. $elementLink->innertext;
                }else{
                    $breadcrumb = $breadcrumb . $elementLink->outertext . $dividerText;
                }
        }
        return $breadcrumb;         
    }
    
    public function k4NavigationGetSimpleNavigation($content,$selectedPath)
    {

        $html = str_get_html($this->k4NavigationGetActivePath($content,$selectedPath));
        
        $filter = 'ul li[class!="active"] ul,ul li[!class] ul';
        foreach($html->find($filter) as $element){
                  $element->outertext = "";
        }
        return $html;        
    } 
	
	public function k4NavigationShowFromTo($content,$levelFrom,$levelTo)
    {	

        $html = str_get_html($content);
		$html2 = "";
		
        if ($levelFrom > 0 AND !empty($html)){
            $filter = '.level'.$levelFrom;
			foreach($html->find($filter) as $element){
					  $html2 = $element->outertext;
			}
		}
        
        $html = str_get_html($html2);
		if ($levelTo > 0 AND !empty($html)){
			$filter = '.level'.$levelTo;
			foreach($html->find($filter) as $element){
					  $element->outertext = "";
			}
		}
        return $html;        
    }
    
    public function k4NavigationGetNextSubmenu($content,$selectedPath)
    {	
        $html2 = "";
        
        $html = str_get_html($content);
       
        $selectedFilter = 'a[href="'.$selectedPath.'"]';

        //set selected link 
        $selectElemArr = $html->find($selectedFilter);//->parent();
        if (is_array($selectElemArr) AND !empty($selectElemArr[0])){        
            $html2 = $selectElemArr[0]->next_sibling();
            if (!empty($html2)){
                foreach($html2->find("ul") as $element){
                          $element->outertext = "";
                }
                $html2->outertext;    
            }
        }
        return $html2;
    } 
}