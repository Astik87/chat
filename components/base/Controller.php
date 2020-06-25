<?php

namespace components\base;


class Controller
{

    /**
     * Displays the view
     */
    protected function render($view, $params = null) {
        
        $view = new View($view, $params);
        $view->render();

    }

    protected function redirect($uri) {
        header('Location: /'.$uri);
    }
}