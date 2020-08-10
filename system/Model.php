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

/**
 * @property PDO
 */
class Model
{
    // Database Handler
    /**
     * @return PDO
     */
    private $_dbh;

    // Untuk menyimpan data array konfigurasi
    private $_config;

    // Atribut untuk mengecek apakah driver tersebut ada atau tidak
    private $_hasDriver = false;

    /**
     * Query Builder
     */

    // Untuk menyimpan data select
    private $select;

    // Untuk menyimpan data from
    protected $table;
    private $jointable = array();
    private $type_join = array();
    private $statement_join = array();

    // Untuk menyimpan data query execute function
    private $query;

    // Untuk menyimpan data array where
    private $where = array();

    // Untuk menyimpan data array or where
    private $or_where = array();

    public function __construct($config = array())
    {
        // Memberikan nilai ke variabel global _config dari function database_config
        $this->_config = database_config();

        // Mengecek apakah parameter $config memiliki data lebih dari 0
        if (count($config) > 0) {
            $this->initialize($config);
        } else {
            $this->initialize();
        }
    }

    /**
     * initialize
     * 
     * @param array $config
     * @return PDO
     */
    public function initialize($config = array())
    {
        try {
            // Mengecek apakah parameter $config memiliki data lebih dari 0
            if (count($config) == 0) {
                $config = $this->_config;
            }

            if (!isset($config['driver']) || empty($config['driver'])) {
                throw new Exception("Konfigurasi driver belum diisi");
            }

            if (!isset($config['host']) || empty($config['host'])) {
                throw new Exception("Konfigurasi host belum diisi");
            }

            if (!isset($config['database']) || empty($config['database'])) {
                throw new Exception("Konfigurasi database belum diisi");
            }

            if (!isset($config['username']) || empty($config['username'])) {
                throw new Exception("Konfigurasi username belum diisi");
            }

            if (!isset($config['password'])) {
                throw new Exception("Konfigurasi password belum diisi");
            }

            // Looping untuk mengecek driver yang tersedia pada PDO
            foreach (PDO::getAvailableDrivers() as $key => $value) {
                if ($this->_hasDriver) continue;

                if (strtolower($value) == strtolower($config['driver'])) {
                    $this->_hasDriver = true;
                }
            }

            // Jika tidak ditemukan driver yang sesuai konfigurasi
            if (!$this->_hasDriver) {
                throw new Exception("Driver tidak ditemukan : " . $config['driver']);
            }

            // Deklarasi variabel untuk mempermudah pemanggilan
            $driver = $config['driver'];
            $host = $config['host'];
            $database = $config['database'];
            $username = $config['username'];
            $password = $config['password'];

            // Instansi PDO ke variabel global _dbh
            $this->_dbh = new PDO("$driver:host=$host;dbname=$database", $username, $password);

            // Mengembalikan nilai dari variabel global _dbh
            return $this->_dbh;
        } catch (PDOException $ex) {
            throw new Exception($ex->getMessage());
        }
    }

    /**
     * getDatabaseHandler
     * 
     * @return object|mixed
     */
    public function getDatabaseHandler()
    {
        return $this->_dbh;
    }

    /**
     * select
     * 
     * @param string|array|mixed $select
     * @return object $this
     */
    public function select($select = '*')
    {
        if (is_array($select)) {
            $this->select = implode(',', $select);
        }

        $this->select = $select;
        return $this;
    }

    /**
     * from
     * 
     * @param string $from
     * @param string $alias
     * @return object $this
     */
    public function from($from, $alias = null)
    {
        if ($alias !== null) {
            $this->table = $from . ' ' . $alias;
        } else {
            $this->table = $from;
        }

        return $this;
    }

    /**
     * get_compiled_select
     * 
     * @return string
     */
    public function get_compiled_select()
    {
        $where = null;

        if (is_array($this->where) && count($this->where) > 0) {
            foreach ($this->where as $key => $value) {
                $set_val = null;

                if (!is_numeric($value)) {
                    $set_val = "'$value'";
                } else {
                    $set_val = $value;
                }

                if ($key == 0) {
                    $where .= "WHERE $key = $set_val ";
                } else {
                    $where .= "AND $key = $set_val ";
                }
            }
        }

        if (is_array($this->or_where) && count($this->or_where) > 0) {
            foreach ($this->or_where as $key => $value) {
                $set_val = null;

                if (!is_numeric($value)) {
                    $set_val = "'$value'";
                } else {
                    $set_val = $value;
                }

                if ($key == 0 && count($this->where) == 0) {
                    $where .= "WHERE $key = $set_val ";
                } else {
                    $where .= "OR $key = $set_val ";
                }
            }
        }

        $jointable = null;

        if (is_array($this->jointable) && count($this->jointable) > 0) {
            foreach ($this->jointable as $key => $value) {
                $type_join = $this->type_join[$key];
                $statement = $this->statement_join[$key];
                $aliases = $this->jointable[$key];

                $jointable .= "$type_join JOIN $key $aliases ON $statement";
            }
        }

        return "SELECT $this->select FROM $this->table $jointable $where";
    }

    /**
     * get
     * 
     * @return object $this
     */
    public function get()
    {
        try {
            $this->query = $this->_dbh->query($this->get_compiled_select());

            return $this;
        } catch (PDOException $ex) {
            throw new Exception($ex->getMessage());
        }
    }

    /**
     * result
     * 
     * @return PDO
     */
    public function result()
    {
        try {
            if ($this->query !== false) {
                return $this->query->fetchAll(PDO::FETCH_OBJ);
            }

            return null;
        } catch (PDOException $ex) {
            throw new Exception($ex->getMessage());
        }
    }

    /**
     * row
     * 
     * @return PDO
     */
    public function row()
    {
        try {
            if ($this->query !== false) {
                return $this->query->fetch(PDO::FETCH_OBJ);
            }

            return null;
        } catch (PDOException $ex) {
            throw new Exception($ex->getMessage());
        }
    }

    /**
     * where
     * 
     * @param string|array $key
     * @param string $value
     * @param object $this
     */
    public function where($key, $value = null)
    {
        if (!is_array($key)) {
            $this->where[$key] = $value;
        } else {
            $this->where = $key;
        }

        return $this;
    }

    /**
     * or_where
     * 
     * @param string|array $key
     * @param string $value
     * @param object $this
     */
    public function or_where($key, $value = null)
    {
        if (!is_array($key)) {
            $this->or_where[$key] = $value;
        } else {
            $this->or_where = $key;
        }

        return $this;
    }

    /**
     * get_compiled_select
     * 
     * @param array $data
     * @return string
     */
    public function get_compiled_insert($data = array())
    {
        $column = array();
        $values = array();
        foreach ($data as $key => $value) {
            $column[] = $key;

            if (is_numeric($value)) {
                $values[] = $value;
            } else {
                $values[] = "'$value'";
            }
        }

        $build_column = implode(',', $column);
        $build_values = implode(',', $values);

        return "INSERT INTO $this->table ($build_column) VALUES ($build_values)";
    }

    /**
     * get_compiled_update
     * 
     * @param array $where
     * @param array $data
     * @return string
     */
    public function get_compiled_update($where = array(), $data = array())
    {
        $this->where($where);

        $where = null;

        if (is_array($this->where) && count($this->where) > 0) {
            foreach ($this->where as $key => $value) {
                $set_val = null;

                if (!is_numeric($value)) {
                    $set_val = "'$value'";
                } else {
                    $set_val = $value;
                }

                if ($key == 0) {
                    $where .= "WHERE $key = $set_val ";
                } else {
                    $where .= "AND $key = $set_val ";
                }
            }
        }

        $set = null;
        $last_set = 0;
        foreach ($data as $key => $value) {
            $set_val = null;

            if (!is_numeric($value)) {
                $set_val = "'$value'";
            } else {
                $set_val = $value;
            }

            if ($last_set != count($data) - 1) {
                $set .= "$key = $set_val, ";
            } else {
                $set .= "$key = $set_val ";
            }

            $last_set++;
        }

        return "UPDATE $this->table SET $set $where";
    }

    /**
     * get_compiled_delete
     * 
     * @return string
     */
    public function get_compiled_delete()
    {
        $where = null;

        if (is_array($this->where) && count($this->where) > 0) {
            foreach ($this->where as $key => $value) {
                $set_val = null;

                if (is_numeric($set_val)) {
                    $set_val = $value;
                } else {
                    $set_val = "'$value'";
                }

                if ($key == 0) {
                    $where .= "WHERE $key = $set_val ";
                } else {
                    $where .= "AND $key = $set_val ";
                }
            }
        }

        return "DELETE FROM $this->table $where";
    }

    /**
     * insert
     * 
     * @param array $data
     * @return object|PDO
     */
    public function insert($data = array())
    {
        try {
            return $this->_dbh->query($this->get_compiled_insert($data));
        } catch (PDOException $ex) {
            throw new Exception($ex->getMessage());
        }
    }

    /**
     * num_rows
     * 
     * @return int
     */
    public function num_rows()
    {
        try {
            $query = $this->_dbh->query($this->get_compiled_select());
            if ($query !== false) {
                return $query->fetchColumn();
            }

            return 0;
        } catch (PDOException $ex) {
            throw new Exception($ex->getMessage());
        }
    }

    /**
     * delete
     * 
     * @return mixed|PDO
     */
    public function delete()
    {
        try {
            return $this->_dbh->query($this->get_compiled_delete());
        } catch (PDOException $ex) {
            throw new Exception($ex->getMessage());
        }
    }

    /**
     * update
     * 
     * @param array $data
     * @param array|mixed $where
     * @return mixed|PDO
     */
    public function update($where = array(), $data = array())
    {
        try {
            return $this->_dbh->query($this->get_compiled_update($where, $data));
        } catch (PDOException $ex) {
            throw new Exception($ex->getMessage());
        }
    }

    /**
     * join
     * 
     * @param string $table
     * @param string $alias
     * @param string $statement
     * @param string $type
     * @return object|mixed
     */
    public function join($table, $alias = null, $statement, $type = null)
    {
        $this->jointable[$table] = $alias;
        $this->statement_join[$table] = $statement;
        $this->type_join[$table] = $type;

        return $this;
    }
}
