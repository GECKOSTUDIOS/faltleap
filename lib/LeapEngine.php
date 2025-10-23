<?php

class LeapEngine
{
    var $server;
    public function __construct()
    {
        //if called on the cli, start the websocket server
        if(PHP_SAPI=='cli'){
            $this->server = new LeapWebSocketServer();
            $this->server->start();
        }
    }

    public function start($routes = array())
    {
        $route = new RouterClass();
        $currentRoute = $route->getRoute($routes);
        //laod the file
        if(!$currentRoute){
            echo "Route not found";
            return;
        }
        if(!array_key_exists("file",$currentRoute)){
            echo "Controller (file) not found";
            return;
        }
        $filename = "../app/".$currentRoute['file'];
        if(!file_exists($filename)){
            echo "Controller (file) not found";
            return;
        }
        require_once($filename);
        if (class_exists($currentRoute['class'])) {
            $instance = new $currentRoute['class']();
            if (method_exists($instance, $currentRoute['method'])) {
                $params = array_values($currentRoute['params']);
                $result = call_user_func_array([$instance, $currentRoute['method']], $params);
                // Use $result as needed
            } else {
                // Handle method not found
                echo "Method not found";
            }
        } else {
            // Handle class not found
            echo "Class not found";
        }
    }
}

?>
