<?php
declare(strict_types=1);

namespace FlatLeap;

// Define a LeapRouter to handle route matching and controller/action extraction
class LeapRouter
{
    /**
     * Matches the current URI against a list of routes and returns the corresponding controller/action.
     * Supports both static and dynamic (parameterized) routes.
     * Supports middleware definitions in routes.
     *
     * @param array $routes Array of route patterns mapped to controller actions or arrays with middleware
     *                      Examples:
     *                      '/user' => 'UserController@index'
     *                      '/admin' => ['AdminController@index', 'auth', 'admin']
     * @return array|null The matched route information with controller, method, params, and middleware
     */
    public function getRoute(array $routes)
    {
        // Get the current request URI
        $uri = $_SERVER["REQUEST_URI"];
        //if it ends with a slash, remove it
        if (strlen($uri) > 1 && substr($uri, -1) == "/") {
            $uri = substr($uri, 0, -1);
        }

        // Sort routes in reverse key order to prioritize more specific routes
        krsort($routes);

        // Iterate through each route candidate
        foreach ($routes as $routecandidate => $routeDefinition) {
            // Parse route definition (could be string or array with middleware)
            $parsedRoute = $this->parseRouteDefinition($routeDefinition);
            $controllerAction = $parsedRoute['action'];
            $middleware = $parsedRoute['middleware'];

            // Extract controller and method from the action string
            $extractedControllerAction = $this->extractControllerAction($controllerAction);
            $extractedControllerAction['middleware'] = $middleware;

            // Check for an exact match
            if ($routecandidate == $uri) {
                return $extractedControllerAction;
            }

            // Check if the route contains parameters (indicated by curly braces)
            if (stripos($routecandidate, "{") !== false) {
                // Convert route pattern to a regex for matching parameters
                $routecandidate_regex = str_replace(["{","}"], ["(?<", ">[a-zA-Z0-9]+)"], $routecandidate);
                $routecandidate_regex = str_replace("/", "\/", $routecandidate_regex);
                // Attempt to match the URI against the regex
                $has_match = preg_match_all("/$routecandidate_regex/is", $uri, $out);

                if ($has_match) {
                    // Replace route parameters in the controller action string with matched values
                    foreach ($out as $key => $value) {
                        if (!is_numeric($key)) {
                            $controllerAction = str_replace("{".$key."}", $value[0], $controllerAction);
                            // Store matched parameters in the extracted controller action array
                            $extractedControllerAction['params'][$key] = $value[0];
                        }
                    }
                    return $extractedControllerAction;
                }
            }
        }

        // If no route matches, return null
        return null;
    }

    /**
     * Parse route definition to extract controller action and middleware.
     *
     * @param string|array $routeDefinition Route definition (string or array)
     * @return array Array with 'action' and 'middleware' keys
     */
    private function parseRouteDefinition($routeDefinition): array
    {
        // If route definition is a string, no middleware
        if (is_string($routeDefinition)) {
            return [
                'action' => $routeDefinition,
                'middleware' => []
            ];
        }

        // If route definition is an array, first element is action, rest is middleware
        if (is_array($routeDefinition)) {
            $action = $routeDefinition[0] ?? '';
            $middleware = array_slice($routeDefinition, 1);
            return [
                'action' => $action,
                'middleware' => $middleware
            ];
        }

        // Default fallback
        return [
            'action' => '',
            'middleware' => []
        ];
    }

    /**
     * Splits a controller action string into class and method parts.
     *
     * @param string $controllerAction The controller action string (e.g. 'UserController@show')
     * @return array Associative array with 'class' and 'method' keys
     */
    public function extractControllerAction(string $controllerAction): array
    {
        $parts = explode("@", $controllerAction);
        preg_match("/[a-zA-Z0-9]+/", $parts[0], $file);
        $file = $file[0].".php";
        return ['class' => $parts[0], 'method' => $parts[1], 'file' => $file, 'params' => []];
    }
}
