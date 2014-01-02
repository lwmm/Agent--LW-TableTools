<?php

namespace AgentTableTools\Controller;

class Exporter
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
        
        $array = array(
            "tables" => $this->prefixFilter($this->transporter->getAllTables(), $this->request->getRaw("prefix_filter")),
            "filter" => $this->request->getRaw("prefix_filter"),
            "selectedTables" => $this->request->getRaw("tables"),
            "tableLines" => $this->getLineCountForEachTable()
        );

        if ($this->request->getInt("export") == 1) {
            $array["xml"] = $this->getXML();
            
            if($this->request->getInt("download") && count($this->request->getRaw("tables")) > 0){
                $this->xmlDownload($array["xml"]);
            }
        }

        $view = new \AgentTableTools\Views\Exporter();
        $this->response->setOutputByKey("AgentTableTools", $view->render($array));
    }

    private function prefixFilter($tables, $prefix_filter)
    {
        if ($prefix_filter) {
            $temp = $tables;
            foreach ($temp as $key => $value) {
                $prefix = substr($value, 0, strlen($prefix_filter));
                if ($prefix != $prefix_filter) {
                    unset($tables[$key]);
                }
            }
        }
        return $tables;
    }
    
    private function getLineCountForEachTable()
    {
        $queryHandler = new \AgentTableTools\Model\Exporter\QueryHandler(\lw_registry::getInstance()->getentry("db"));
        
        $tables = $this->transporter->getAllTables();
        $array = array();
        
        foreach($tables as $table){
            $array[$table] = $queryHandler->getLineCountByTableName($table);
        }
        
        return $array;
    }
    

    private function getXML()
    {
        if (count($this->request->getRaw("tables")) > 0) {
            if ($this->request->getAlnum("type") == 'data') {
                $xml = $this->transporter->exportData($this->request->getRaw("tables"));
            } else {
                $xml = $this->transporter->exportTables($this->request->getRaw("tables"));
            }

            return $xml;
        }
    }
    
    private function xmlDownload($xml)
    {
        if ($this->request->getAlnum("type") == 'data') {
            $filename = date('YmdHis')."_exportData.xml";
        }
        else {
            $filename = date('YmdHis')."_exportStructure.xml";
        }
        
        $mimeType = \lw_io::getMimeType("xml");
        if (strlen($mimeType)<1) {
            $mimeType = "application/octet-stream";
        }            
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: ".$mimeType);
        header("Content-disposition: attachment; filename=\"".  $filename."\"");
        die($xml);
        exit();
    }

}
