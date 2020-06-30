<?php

namespace components\base;

class View 
{

    protected $view;
    protected $title;
    protected $params;
    protected $layout;
    public static $Errors = [];

    public function __construct($view, $params = null, $title = null) 
    {

        $this->params = $params;
        $this->view = $view;
        if ($title === null) {
            $this->title = ucfirst($view);
        } else {
            $this->title = $title;
        }
    
    }

    protected function contant() 
    {

        if ($this->options != null) {
            foreach ($this->options as $key => $value) {
                $$key = $value;
            }
        }

        include(ROOT_DIR . 'views/'.$this->view.'.php');
    }

    public function render()
    {

        $this->layout = ROOT_DIR . 'views/layout.php';
        include ROOT_DIR . 'views/layout.php';

    }

    public function header()
    {

        echo "<title>{$this->title}</title>";

        $path = ROOT_DIR.'assets/css.php';
        if (file_exists($path)) {
            $css = include($path);
            foreach ($css as $cssPath) {
                echo "<link rel=\"stylesheet\" href={$cssPath}>";
            }
        }
    
    }

    public function beginBody()
    {

        if (!empty(self::$Errors[0])) {
            echo "<div class=\"errors\"><div class=\"error_text\">".self::$Errors[0]."</div></div>";
        }
    
    }

    public function endBody()
    {

        $path = ROOT_DIR.'assets/js.php';
        if (file_exists($path)) {
            $js = include($path);
            foreach ($js as $jsPath) {
                echo "<script src=\"{$jsPath}\"></script>";
            }
        }       
    
    }
}