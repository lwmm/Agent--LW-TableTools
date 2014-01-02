<?php

namespace AgentTableTools\Views;

class Importer
{
    public function render($array)
    {
        $config = \lw_registry::getInstance()->getentry("config");
        
        if($config["general"]["HTTPSallowed"] == 1){
            if(!strpos(strtolower($config["url"]["client"]), "https")) {
                $config["url"]["client"] = str_replace("http", "https", $config["url"]["client"]);
            } 
        }
        
        $view = new \lw_view(dirname(__FILE__) . '/Templates/Importer.phtml');
        $view->url = $config["url"]["client"] . "admin.php?obj=tabletools&module=importer&import=1";
        
        if(isset($array["xml"])) { $view->xml = $array["xml"]; }
        if(isset($array["debugOutput"])) { $view->debugOutput = $array["debugOutput"]; }
        if(isset($array["debug"])) { $view->debug = $array["debug"]; }
        

        return $view->render();
    }
}