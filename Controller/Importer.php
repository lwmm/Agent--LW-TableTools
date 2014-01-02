<?php

namespace AgentTableTools\Controller;

class Importer
{
    protected $config;
    protected $request;
    protected $response;
    protected $transporter;


    public function __construct($config, $response, $request)
    {
        $this->config = $config;
        $this->request = $request;
        $this->response = $response;
    }
    
    public function execute()
    {
        $this->transporter = new \lw_db_transporter();
        $array = array();
        
        if($this->request->getInt("import") == 1){
            $this->transporter->setDebug($this->request->getInt("debug"));
            $file = $this->request->getFileData("xmlFile");

            if($file["tmp_name"] && $file["type"] == "text/xml"){
                $xml = file_get_contents($file["tmp_name"]);
            }else if($this->request->getRaw("xmlText")){
                $xml = $this->request->getRaw("xmlText");
            }
            
            $str = $this->transporter->importXML($xml);
            
            $array["debug"] = $this->request->getInt("debug");
            $array["debugOutput"] = $str;
            $array["xml"] = $xml;
        }
        
        $view = new \AgentTableTools\Views\Importer();
        $this->response->setOutputByKey("AgentTableTools", $view->render($array));
    }
}