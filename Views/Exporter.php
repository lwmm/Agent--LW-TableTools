<?php

namespace AgentTableTools\Views;

class Exporter
{
    public function render($array)
    {
        $config = \lw_registry::getInstance()->getentry("config");
        
        if($config["general"]["HTTPSallowed"] == 1){
            if(!strpos(strtolower($config["url"]["client"]), "https")) {
                $config["url"]["client"] = str_replace("http", "https", $config["url"]["client"]);
            } 
        }
        
        $view = new \lw_view(dirname(__FILE__) . '/Templates/Exporter.phtml');
        $view->prefixUrl = $config["url"]["client"] . "admin.php?obj=tabletools&module=exporter";
        $view->exportUrl = $config["url"]["client"] . "admin.php?obj=tabletools&module=exporter&export=1";
        
        $view->tables = $array["tables"];
        $view->tableLines = $array["tableLines"];
        $view->filter = $array["filter"];
        $view->selectedTables = $array["selectedTables"];
        
        if(isset($array["xml"])){
            $view->showXml = 1;
            $view->xml = htmlspecialchars($array["xml"]);
        }
        
        return $view->render();
    }
}