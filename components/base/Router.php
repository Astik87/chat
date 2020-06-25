<?php

class Router {

    private $routes;

    public function __construct() {
        $this->routes = include(ROOT_DIR . 'config/routes.php');
        
    }

    /**
     * Return request string
     * 
     * @return string 
     */
    private function getURI() {
        $uri = $_SERVER['REQUEST_URI'];

        if ($uri[-1] == '/' and $uri != '/') {
            $uri = substr($uri, 0, -1);
        }

        return $uri;
    }


    public function run() {

        // Getting the query string
        $uri = $this->getURI();

        // Checking for a query in routes.php 
        foreach ($this->routes as $uriPattern => $path) {

            if (preg_match("~$uriPattern~", $uri)) {
                
                $internalRoute = preg_replace("~$uriPattern~", $path, $uri);

                $internalRoute = trim($internalRoute, '/');

                $params = explode('/', $internalRoute);

                $controllerName = 'controllers\\'. ucfirst(array_shift($params)) .'Controller';
                $actionName = 'action'. ucfirst(array_shift($params));

                $controllerObject = new $controllerName;

                if (method_exists($controllerObject, $actionName)) {
                    $result = call_user_func_array([$controllerObject, $actionName], $params);
                    break;
                }   
            }

        }
        
    }

}