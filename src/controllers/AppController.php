<?php

class AppController {

    private $request;

    public function _construct() {
        $this->request = $_SERVER["REQUEST_METHOD"];
    }

    protected function isGET(): bool {
        return $this->request==="GET";
    }

    protected function isPost(): bool {
        print_r($_SERVER["REQUEST_METHOD"]);
        return $_SERVER["REQUEST_METHOD"]==="POST";
    }

    protected function render(string $template = null, array $variables = []) {
        $templatePath = 'public/views/' . $template . '.php';
        $output = "File not found!";
        
        if(file_exists($templatePath)) {
            extract($variables);

            ob_start();
            include $templatePath;
            $output = ob_get_clean();
        }

        print $output;
    }
}