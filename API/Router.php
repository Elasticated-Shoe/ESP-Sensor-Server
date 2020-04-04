<?php
    require("Controllers/IController.php");

    interface IRouter {
        public function addUrlPath();
        public function addControllerPaths();
        public function addTargetPath();
        public function getRouteFromUrl();
    }
    class RouterBuilder {
        public static function build(IRouter $router) {
            $router->addUrlPath();
            $router->addControllerPaths();
            $router->addTargetPath();
        }
    }
    final class Router implements IRouter {
        private $controllersDir = "Controllers/";
        private $pathDir;
        private $class;
        private $url;
        private $directories = array();
        public $targetUser;

        public function __construct() {

        }

        public function addUrlPath() {
            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
            $this->url = parse_url($protocol . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");

            if(!$this->url) {
                throw new Exception("Url Could Not Be Parsed");
            }
        }
        public function addControllerPaths() {
            $controllersContents = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->controllersDir));

            foreach ($controllersContents as $file) {
                if (!$file->isDir()){ 
                    array_push($this->directories, $file->getPathname());
                }
            }
        }
        public function addTargetPath() {
            $path = $this->url["path"];
            $pathArray = explode("/", $path);

            foreach($GLOBALS["Config"]["Web"]["Alias"] as $alias) {
                $pathAlias = array_search($alias, $pathArray);
                if($pathAlias) {
                    unset($pathArray[$pathAlias]);
                }
            }
            $pathArray = array_filter($pathArray);

            if(array_key_exists(2, $pathArray)) {
                $this->targetUser = $pathArray[2];
                unset($pathArray[2]);
            }

            array_unshift($pathArray, "Controllers");

            $this->pathDir = implode(DIRECTORY_SEPARATOR, $pathArray);
            $this->class = end($pathArray);
        }
        public function getRouteFromUrl() {
            $classIndex = array_search($this->pathDir . ".php", $this->directories);

            if($classIndex === false) {
                return false;
            }

            $classFile = $this->directories[$classIndex];

            if(file_exists($classFile)) {
                include $classFile;

                if(class_exists($this->class)) {
                    $reflectionClass = new ReflectionClass($this->class);
                    
                    if($reflectionClass->isInstantiable() && array_key_exists("IController", class_implements($this->class))) {
                        return $this->class;
                    }
                }
            }
            return false;
        }
        
    }
?>