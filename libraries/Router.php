<?php

class Router
{
    private $url = null;
    private $action = null;
    private $id = null;
    private $page = null;
    private $controller = 'main';
    private $routes = [];

    public function __construct() {
        $routes = [];
        require_once  CONFIG . 'routes.conf.php';
        $this->routes = $routes;
        $url = explode('?', $_SERVER['REQUEST_URI']);
        $this->url = explode('/', $url[0]);
        $this->action = empty($this->url[BASE_URL_PART + 1]) ? 'home' : $this->url[BASE_URL_PART + 1];
        $this->page = empty($this->url[BASE_URL_PART + 2]) ? null : $this->url[BASE_URL_PART + 2];
        $this->res();
    }


    public function req() {

    }

    private function res() {
        if (!empty($this->routes[$this->action])) {
            $this->controller = $this->routes[$this->action];
        } else {
            $this->set404();
        }
    }

    private function set404() {
        $this->controller = 'main';
        $this->action = '404';
    }

    public function getController() {
        return $this->controller;
    }

    public function getAction() {
        return $this->action;
    }

    public function getPage() {
        return $this->page;
    }

    public function getUrl() {
        return $this->url;
    }

}