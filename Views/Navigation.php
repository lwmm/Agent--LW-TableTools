<?php

namespace AgentTableTools\Views;

class Navigation
{
    public function render($module)
    {
        $view = new \lw_view(dirname(__FILE__) . '/Templates/Navigation.phtml');
        
        $view->module = $module;
        
        return $view->render();
    }
}