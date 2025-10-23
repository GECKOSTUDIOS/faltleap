
<?php

// Define a RouterClass to handle route matching and controller/action extraction
class RouterClass
{
    /**
     * Matches the current URI against a list of routes and returns the corresponding controller/action.
     * Supports both static and dynamic (parameterized) routes.
     *
     * @param array $routes Array of route patterns mapped to controller actions (e.g. '/user/{id}' => 'UserController@show')
     * @return string The matched controller action string, or the URI if no match is found
     */
    public function getRoute(array $routes)
    {
        // Get the current request URI
        $uri = $_SERVER["REQUEST_URI"];

        // Sort routes in reverse key order to prioritize more specific routes
        krsort($routes);

        // Iterate through each route candidate
        foreach($routes as $routecandidate => $controllerAction) {
            // Extract controller and method from the action string
            $extractedControllerAction = $this->extractControllerAction($controllerAction);

            // Check for an exact match
            if ($routecandidate == $uri) {
                return $extractedControllerAction;
            }

            // Check if the route contains parameters (indicated by curly braces)
            if (stripos($routecandidate, "{")!==FALSE){
                // Convert route pattern to a regex for matching parameters
                $routecandidate_regex = str_replace(["{","}"],["(?<", ">[a-zA-Z0-9]+)"], $routecandidate);
                $routecandidate_regex = str_replace("/", "\/", $routecandidate_regex);
                // Attempt to match the URI against the regex
                $has_match = preg_match_all("/$routecandidate_regex/is", $uri, $out);

                if ($has_match) {
                    // Replace route parameters in the controller action string with matched values
                    foreach($out as $key => $value) {
                        if(!is_numeric($key)){
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
     * Splits a controller action string into class and method parts.
     *
     * @param string $controllerAction The controller action string (e.g. 'UserController@show')
     * @return array Associative array with 'class' and 'method' keys
     */
    public function extractControllerAction(string $controllerAction): array
    {
        $parts = explode("@", $controllerAction);
        preg_match("/[a-zA-Z0-9]+/", $parts[0],$file);
        $file = $file[0].".php";
        return ['class'=>$parts[0], 'method'=> $parts[1], 'file'=>$file, 'params'=>[]];
    }
}
