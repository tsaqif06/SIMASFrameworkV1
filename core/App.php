<?php
class App
{
    protected $controller = 'Home';
    protected $method = 'index';
    protected $params = [];

    public function __construct()
    {
        // controller
        $url = $this->getParseURL();
        $folder = "";
        if ($url == NULL) {
            $url = [$this->controller];
        }
        if (file_exists("../app/controllers/$url[0].php")) {
            $folder = "";
            $this->controller = $url[0];
            unset($url[0]);
        } else if (file_exists("../app/controllers/folder1/$url[0].php")) {  // ubah folder1, folder2, folder3 sesuai folder yang dibuat di app/controllers kalian
            $folder = "folder1/";
            $this->controller = $url[0];
            unset($url[0]);
        } else if (file_exists("../app/controllers/folder2/$url[0].php")) {
            $folder = "folder2/";
            $this->controller = $url[0];
            unset($url[0]);
        } else if (file_exists("../app/controllers/folder3/$url[0].php")) {
            $folder = "folder3/";
            $this->controller = $url[0];
            unset($url[0]);
        } else {
            $this->controller = 'Notfound';
            unset($url[0]);
        }

        require_once "../app/controllers/{$folder}{$this->controller}.php";
        $this->controller = new $this->controller;

        // method
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // params
        if (!empty($url)) {
            $this->params = array_values($url);
        }

        // unset the url
        unset($_GET['url']);

        // jalankan controller dan method, serta mengirim params jika ada
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function getParseURL() // mengubah url ke array
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode("/", $url);
            return $url;
        }
    }
}
