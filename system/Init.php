<?php

/***
 *      __  __            _                     _          _   ______              _   __  ____      _______ 
 *     |  \/  |          | |         /\        (_)        | | |  ____|            (_) |  \/  \ \    / / ____|
 *     | \  / | ___   ___| |__      /  \   _ __ _ ______ _| | | |__ __ _ _   _ _____  | \  / |\ \  / / |     
 *     | |\/| |/ _ \ / __| '_ \    / /\ \ | '__| |_  / _` | | |  __/ _` | | | |_  / | | |\/| | \ \/ /| |     
 *     | |  | | (_) | (__| | | |  / ____ \| |  | |/ / (_| | | | | | (_| | |_| |/ /| | | |  | |  \  / | |____ 
 *     |_|  |_|\___/ \___|_| |_| /_/    \_\_|  |_/___\__,_|_| |_|  \__,_|\__,_/___|_| |_|  |_|   \/   \_____|
 *                                                                                                           
 *                                                                                                           
 */

defined('BASEPATH') or die('No direct script access allowed!');

class Init
{
    // Digunakan untuk menyimpan parameter
    public $params = array();

    // Default controller yang dijalankan
    protected $controller = null;

    // Default method yang dijalankan
    protected $method = 'index';

    // Digunakan untuk menyimpan data pada url
    private $_url = null;
    private $_isCustom404 = true;

    /**
     * Constructor
     */
    public function __construct()
    {
        // Load variabel dari file routes
        require_once 'app/config/routes.php';

        // Mengecek apakah ada index 'default_controller' pada variabel $route
        if (isset($route['default_controller'])) {
            // Mengecek apakah index 'default_controller' memiliki value / nilai
            if (!empty($route['default_controller'])) {
                // Memecah value string pada variabel route dengan nama index 'default_controller' dengan delimiter slash '/'
                $exploding = explode('/', $route['default_controller']);

                // Mengambil value string dari array variabel exploding dengan index 0 yaitu sebagai controller
                $default_controller = $exploding[0];

                // Memberikan nilai kepada global variabel controller dengan value dari variabel local controller
                $this->controller = $default_controller;

                // Mengecek apakah string tersebut memiliki index 1 yaitu sebagai method
                if (isset($exploding[1])) {
                    $default_method = $exploding[1];
                    $this->method = $default_method;
                }
            } else {
                throw new Exception("Default Controller tidak boleh kosong");
            }
        } else {
            throw new Exception("Tidak ditemukan index 'default_controller' pada variabel route");
        }

        // Untuk memberikan nilai kepada variabel global $_url
        $this->_url = $this->parseURL();

        if (isset($this->_url[0])) {
            $this->controller = $this->_url[0];
        }

        if (isset($this->_url[1])) {
            $this->method = $this->_url[1];
        }

        // Controller
        if (file_exists('app/controllers/' . $this->controller . '.php')) {
            unset($this->_url[0]);
        }

        // Custom 404 Page
        if (isset($route['404_override'])) {
            if ($route['404_override'] == 404) {
                $this->_isCustom404 = false;
            } else {
                $exploding = explode('/', $route['404_override']);

                $this->controller = $exploding[0];

                if (isset($exploding[1])) {
                    $this->method = $exploding[1];
                }

                $this->_isCustom404 = true;
            }
        }

        // Instance
        if (file_exists('app/controllers/' . $this->controller . '.php')) {
            require_once 'app/controllers/' . $this->controller . '.php';
            $this->controller = new $this->controller;
        } else {
            return show_404();
        }

        // Method
        if (method_exists($this->controller, $this->method)) {
            if ($this->controller !== 'default_controller') {
                unset($this->_url[1]);
            }
        } else {
            throw new Exception("Method tidak ditemukan : $this->method");
        }

        // Parameter
        if (!empty($this->_url)) {
            /**
             * @var array $url
             * 
             * Biar tidak muncul error, ini adalah php document
             */
            $url = $this->_url;
            $this->params = array_values($url);
        }

        // Run Controller
        return call_user_func_array([$this->controller, $this->method], $this->params);
    }

    /**
     * parseURL
     * 
     * @return string
     */
    private function parseURL()
    {
        // Mengecek apakah ada index dengan nama 'url' pada url
        if (isset($_GET['url'])) {
            // Membersihkan slash pada akhir url
            $url = rtrim($_GET['url'], '/');

            // Memfilter url agar tidak terjadi hacking
            $url = filter_var($url, FILTER_SANITIZE_URL);

            // Memecah string url menjadi array dengan delimiter slash '/'
            $url = explode('/', $url);

            // Mengembalikan nilai dari variabel $url
            return $url;
        }

        // Jika tidak ditemukan index dengan nama 'url' maka akan mengembalikan nilai null
        return null;
    }
}
