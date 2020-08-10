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

class Controller
{
    // Untuk menyimpan nama file dengan tipe data string, default = null
    private $_file = null;

    // Untuk menyimpan nama folder dengan tipe data array
    private $_folder = array();

    /**
     * view
     * 
     * @return mixed
     */
    public function view($view, $data = array())
    {
        // Mengecek apakah file tersebut ada pada folder views
        if (file_exists('app/views/' . $view . '.php')) {
            require_once 'app/views/' . $view . '.php';
        } else {
            // Memecah string view menjadi array dengan delimiter slash '/'
            $exploding = explode('/', $view);

            // Looping data array dari variabel exploding
            foreach ($exploding as $key => $value) {
                // Jika key terakhir otomatis menganggap string tersebut adalah file
                if ($key == count($exploding) - 1) {
                    // Memberikan nilai string ke variabel global _file
                    $this->_file = $value;
                } else {
                    // Memberikan nilai array ke variabel global _folder
                    $this->_folder[] = $value;
                }
            }

            // Mengabungkan semua array dari variabel global _folder menjadi string dengan memberikan simbol slash '/' pada setiap string array terakhirnya
            $directory = implode('/', $this->_folder);

            throw new Exception("Tidak terdapat file pada folder $directory dengan nama file $this->_file.php");
        }
    }

    /**
     * model
     * 
     * @param string $model
     * @param array $config
     * @return object
     */
    public function model($model)
    {
        if (file_exists('app/models/' . $model . '.php')) {
            include 'app/models/' . $model . '.php';

            return new $model;
        } else {
            throw new Exception("Tidak terdapat file pada folder models dengan nama file $model.php");
        }
    }

    /**
     * post
     * 
     * @param string $index
     * @param string $default
     * @return string|array|mixed
     */
    public function post($index, $default = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST[$index])) {
                return $_POST[$index];
            }
        }

        return $default;
    }

    /**
     * get
     * 
     * @param string $index
     * @param string $default
     * @return string|array|mixed
     */
    public function get($index, $default = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            if (isset($_GET[$index])) {
                return $_GET[$index];
            }
        }

        return $default;
    }
}
