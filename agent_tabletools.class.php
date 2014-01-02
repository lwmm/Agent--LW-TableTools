<?php

class agent_tabletools extends lw_agent
{

    protected $config;
    protected $request;
    protected $response;

    public function __construct()
    {
        parent::__construct();
        $this->config = $this->conf;
        $this->className = "agent_piwik";
        $this->adminSurfacePath = $this->config['path']['agents'] . "adminSurface/templates/";

        $usage = new lw_usage($this->className, "0");
        $this->secondaryUser = $usage->executeUsage();

        include_once(dirname(__FILE__) . '/Services/Autoloader.php');
        $autoloader = new \AgentTableTools\Services\Autoloader();
    }

    protected function showEdit()
    {
        $response = new \AgentTableTools\Services\Response();
        switch ($this->request->getAlnum("module")) {
            case "importer":
                $moduleController = new \AgentTableTools\Controller\Importer($this->config, $response, $this->request);
                break;

            case "exporter":
            default:
                $moduleController = new \AgentTableTools\Controller\Exporter($this->config, $response, $this->request);
                break;
        }

        $moduleController->execute();
        return $response->getOutputByKey("AgentTableTools");
    }

    protected function buildNav()
    {
        if (!$this->request->getAlnum("module")) {
            $module = "exporter";
        } else {
            $module = $this->request->getAlnum("module");
        }

        $view = new \AgentTableTools\Views\Navigation();
        return $view->render($module);
    }

    protected function deleteAllowed()
    {
        return true;
    }

}
