<?php

namespace AgentTableTools\Model\Exporter;

class QueryHandler
{
    protected $db;
    
    public function __construct($db)
    {
        $this->db = $db;
    }
    
    public function getLineCountByTableName($name)
    {
        $this->db->setStatement("SELECT COUNT(*) FROM :table ");
        $this->db->bindParameter("table", "t", $name);
        
        $result = $this->db->pselect1();
        
        return $result["COUNT(*)"];
    }
}