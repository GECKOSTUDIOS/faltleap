<?php
declare(strict_types=1);

namespace FlatLeap;

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
        $route = new LeapRouter();
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
        // No need to manually require files anymore - autoloader handles it
        $fullClassName = "App\\Controllers\\" . $currentRoute['class'];
        if (class_exists($fullClassName)) {
            $instance = new $fullClassName();
            if (method_exists($instance, $currentRoute['method'])) {
                // Collect middleware from route and controller
                $middleware = $this->collectMiddleware($currentRoute, $instance);

                // Execute middleware stack with controller method as final action
                $params = array_values($currentRoute['params']);
                $finalAction = function() use ($instance, $currentRoute, $params) {
                    return call_user_func_array([$instance, $currentRoute['method']], $params);
                };

                // Create middleware stack and execute
                $middlewareStack = new LeapMiddlewareStack(
                    new LeapDB(),
                    new LeapRequest(),
                    new LeapSession()
                );

                $result = $middlewareStack->execute($middleware, $finalAction);
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

    /**
     * Collect middleware from route definition and controller.
     *
     * @param array $currentRoute The current route information
     * @param object $controllerInstance The controller instance
     * @return array Array of middleware aliases/class names
     */
    private function collectMiddleware(array $currentRoute, object $controllerInstance): array
    {
        $middleware = [];

        // Get middleware from route definition
        if (isset($currentRoute['middleware']) && is_array($currentRoute['middleware'])) {
            $middleware = array_merge($middleware, $currentRoute['middleware']);
        }

        // Get middleware from controller property
        if (property_exists($controllerInstance, 'middleware')) {
            $controllerMiddleware = $controllerInstance->middleware;
            if (is_array($controllerMiddleware)) {
                $middleware = array_merge($middleware, $controllerMiddleware);
            } elseif (is_string($controllerMiddleware)) {
                $middleware[] = $controllerMiddleware;
            }
        }

        return $middleware;
    }
}
