<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/***
 * File: (Codeigniterapp)/libraries/Controllerlist.php
 * 
 * A simple library to list all your controllers with their methods.
 * This library will return an array with controllers and methods
 * 
 * The library will scan the "controller" directory and (in case of) one (1) subdirectory level deep
 * for controllers
 * 
 * Usage in one of your controllers:
 * 
 * $this->load->library('controllerlist');
 * print_r($this->controllerlist->getControllers());
 * 
 * @author Peter Prins 
 */

class Controllerlist {

    /**
     * Codeigniter reference 
     */
    private $CI;
    private $EXT;

    /**
     * Array that will hold the controller names and methods
     */
    private $aControllers;
    private $routes;

    // Construct
    function __construct() {
        // Get Codeigniter instance 
        $this->CI = get_instance();
        $this->CI->EXT = ".php";

        // Get all controllers 
        // $this->setControllers();
        // $this->setModuleControllers();

        // Get all routes
        $this->setAppRoutes();
    }

    /**
     * Return all controllers and their methods
     * @return array
     */
    public function getControllers() {
        return $this->aControllers;
    }

    /**
     * return all routes from routes.php
     * @return [type] [description]
     */
    public function getAppRoutes() {
        return array_unique($this->routes);
    }

    /**
     * Set the array holding the controller name and methods
     */
    public function setControllerMethods($p_sControllerName, $p_aControllerMethods) {
        $this->aControllers[$p_sControllerName] = $p_aControllerMethods;
    }

    /**
     * Search and set controller and methods.
     */
    private function setControllers() {
        // Loop through the controller directory
        foreach(glob(APPPATH . 'controllers/*') as $controller) {
            # Only get class with controller's suffix
            if (strpos($controller, 'Controller.php') === false) {
                continue;
            }

            // if the value in the loop is a directory loop through that directory
            if(is_dir($controller)) {
                // Get name of directory
                $dirname = basename($controller, $this->CI->EXT);

                // Loop through the subdirectory
                foreach(glob(APPPATH . 'controllers/'.$dirname.'/*') as $subdircontroller) {
                    // Get the name of the subdir
                    $subdircontrollername = basename($subdircontroller, $this->CI->EXT);

                    // Load the controller file in memory if it's not load already
                    if(!class_exists($subdircontrollername)) {
                        $this->CI->load->file($subdircontroller);
                    }
                    // Add the controllername to the array with its methods
                    $aMethods = get_class_methods($subdircontrollername);
                    $aUserMethods = array();
                    foreach($aMethods as $method) {
                        if($method != '__construct' && $method != 'get_instance' && $method != $subdircontrollername) {
                            # Only get class with action's prefix
                            if (strpos($controller, 'action') === false) {
                                continue;
                            }

                            $aUserMethods[] = $method;
                        }
                    }

                    $this->setControllerMethods($subdircontrollername, $aUserMethods);                                      
                }
            } elseif (pathinfo($controller, PATHINFO_EXTENSION) == "php") {
                // value is no directory get controller name                
                $controllername = basename($controller, $this->CI->EXT);

                // Load the class in memory (if it's not loaded already)
                if(!class_exists($controllername)) {
                    $this->CI->load->file($controller);
                }

                // Add controller and methods to the array
                $aMethods = get_class_methods($controllername);
                $aUserMethods = array();
                if(is_array($aMethods)){
                    foreach($aMethods as $method) {
                        if($method != '__construct' && $method != 'get_instance' && $method != $controllername) {
                            # Only get class with action's prefix
                            if (strpos($method, 'action') === false) {
                                continue;
                            }

                            $aUserMethods[] = $method;
                        }
                    }
                }

                $this->setControllerMethods($controllername, $aUserMethods);                                
            }
        }   
    }

    /**
     * Search and set controller and methods.
     */
    private function setModuleControllers() {
        $path_module = APPPATH . 'modules';
        foreach(scandir($path_module) as $module) {
            if (in_array($module, ['.', '..'])) {
                continue;
            }

            // Loop through the controller directory
            foreach(glob(APPPATH . 'modules/' . $module . '/controllers/*') as $controller) {
                # Only get class with controller's suffix
                if (strpos($controller, 'Controller.php') === false) {
                    continue;
                }

                // if the value in the loop is a directory loop through that directory
                if(is_dir($controller)) {
                    // Get name of directory
                    $dirname = basename($controller, $this->CI->EXT);

                    // Loop through the subdirectory
                    foreach(glob(APPPATH . 'controllers/'.$dirname.'/*') as $subdircontroller) {
                        // Get the name of the subdir
                        $subdircontrollername = basename($subdircontroller, $this->CI->EXT);

                        // Load the controller file in memory if it's not load already
                        if(!class_exists($subdircontrollername)) {
                            $this->CI->load->file($subdircontroller);
                        }
                        // Add the controllername to the array with its methods
                        $aMethods = get_class_methods($subdircontrollername);
                        $aUserMethods = array();
                        foreach($aMethods as $method) {
                            if($method != '__construct' && $method != 'get_instance' && $method != $subdircontrollername) {
                                # Only get class with action's prefix
                                if (strpos($controller, 'action') === false) {
                                    continue;
                                }

                                $aUserMethods[] = $method;
                            }
                        }

                        $this->setControllerMethods($module .'__'. $subdircontrollername, $aUserMethods);                                      
                    }
                } elseif (pathinfo($controller, PATHINFO_EXTENSION) == "php") {
                    // value is no directory get controller name                
                    $controllername = basename($controller, $this->CI->EXT);

                    // Load the class in memory (if it's not loaded already)
                    if(!class_exists($controllername)) {
                        $this->CI->load->file($controller);
                    }

                    // Add controller and methods to the array
                    $aMethods = get_class_methods($controllername);
                    $aUserMethods = array();
                    if(is_array($aMethods)){
                        foreach($aMethods as $method) {
                            if($method != '__construct' && $method != 'get_instance' && $method != $controllername) {
                                # Only get class with action's prefix
                                if (strpos($method, 'action') === false) {
                                    continue;
                                }

                                $aUserMethods[] = $method;
                            }
                        }
                    }

                    $this->setControllerMethods($module .'__'. $controllername, $aUserMethods);                                
                }
            }
        }
    }

    private function setAppRoutes()
    {
        $routes = $this->CI->router->routes;
        $routes = array_keys(array_filter($routes));
        $collected_routes = [];

        foreach ($routes as $key => $route) {
            $unslashed = explode('/', $route);

            if (count($unslashed) > 1) {
                $without_param = preg_replace("/\([^)]+\)/","", $route);

                $collected_routes[] = rtrim($without_param, "/");
            }
        }

        $this->routes = $collected_routes;
    }

}

/* End of file Controllerlist.php */
/* Location: ./application/libraries/Controllerlist.php */
