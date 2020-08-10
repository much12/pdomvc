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

class Siswa_model extends Model
{
    protected $table = 'siswa';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * getDataSiswa
     * 
     * @param array $where
     * @return mixed
     */
    public function getDataSiswa($where = array())
    {
        if (is_array($where) && count($where) > 0) {
            $this->where($where);
        }

        return $this->select('a.*, b.kelas')
            ->from($this->table, 'a')
            ->join('kelas', 'b', 'b.id = a.id_kelas')
            ->get();
    }
}
