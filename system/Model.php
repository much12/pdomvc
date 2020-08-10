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
    private $_statement;

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
    private $table_withoutalias = null;

    private $jointable = array();
    private $type_join = array();
    private $statement_join = array();

    // Untuk menyimpan data array where
    private $where = array();

    // Untuk menyimpan data array or where
    private $or_where = array();

    public function __construct()
    {
        // Memberikan nilai ke variabel global _config dari function database_config
        $this->_config = database_config();
        $this->initialize();
    }

    /**
     * initialize
     * 
     * @param array $config
     * @return PDO
     */
    public function initialize()
    {
        try {
            $config = $this->_config;

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

            // Deklarasi Option
            $option = array(
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            );

            // Instansi PDO ke variabel global _dbh
            $this->_dbh = new PDO("$driver:host=$host;dbname=$database", $username, $password, $option);
        } catch (PDOException $ex) {
            throw new Exception($ex->getMessage());
        }
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
            $this->table_withoutalias = $from;
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
                $val_key = str_replace('.', '_', $key);

                if ($key == 0) {
                    $where .= "WHERE $key = :$val_key ";
                } else {
                    $where .= "AND $key = :$val_key ";
                }
            }
        }

        if (is_array($this->or_where) && count($this->or_where) > 0) {
            foreach ($this->or_where as $key => $value) {
                if ($key == 0 && count($this->where) == 0) {
                    $where .= "WHERE $key = :$key ";
                } else {
                    $where .= "OR $key = :$key ";
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

        $this->query("SELECT $this->select FROM $this->table $jointable $where");

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
            $this->get_compiled_select();

            if (is_array($this->where) && count($this->where) > 0) {
                foreach ($this->where as $key => $value) {
                    $key = str_replace('.', '_', $key);

                    $this->bind($key, $value);
                }
            }

            if (is_array($this->or_where) && count($this->or_where) > 0) {
                foreach ($this->or_where as $key => $value) {
                    $key = str_replace('.', '_', $key);

                    $this->bind($key, $value);
                }
            }

            $this->execute();

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
            return $this->_statement->fetchAll(PDO::FETCH_OBJ);
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
            return $this->_statement->fetch(PDO::FETCH_OBJ);
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
            $values[] = ":$key";
        }

        $build_column = implode(',', $column);
        $build_values = implode(',', $values);

        if ($this->table_withoutalias !== null) {
            $table = $this->table_withoutalias;
        } else {
            $table = $this->table;
        }

        $this->query("INSERT INTO $table ($build_column) VALUES ($build_values)");

        return "INSERT INTO $table ($build_column) VALUES ($build_values)";
    }

    /**
     * get_compiled_update
     * 
     * @param array $where
     * @param array $data
     * @return string
     */
    public function get_compiled_update($data = array())
    {
        $str_where = null;

        if (is_array($this->where) && count($this->where) > 0) {
            foreach ($this->where as $key => $value) {
                if ($key == 0) {
                    $str_where .= "WHERE $key = :$key ";
                } else {
                    $str_where .= "AND $key = :$key ";
                }
            }
        }

        $set = array();
        foreach ($data as $key => $value) {
            $set[] = "$key = :$key ";
        }
        $set = implode(',', $set);

        if ($this->table_withoutalias !== null) {
            $table = $this->table_withoutalias;
        } else {
            $table = $this->table;
        }

        $this->query("UPDATE $table SET $set $str_where");

        return "UPDATE $table SET $set $str_where";
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
                if ($key == 0) {
                    $where .= "WHERE $key = :$key ";
                } else {
                    $where .= "AND $key = :$key ";
                }
            }
        }

        if ($this->table_withoutalias !== null) {
            $table = $this->table_withoutalias;
        } else {
            $table = $this->table;
        }

        $this->query("DELETE FROM $table $where");

        return "DELETE FROM $table $where";
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
            $this->get_compiled_insert($data);

            foreach ($data as $key => $value) {
                $this->bind($key, $value);
            }

            return $this->execute();
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
            return $this->_statement->rowCount();
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
            $this->get_compiled_delete();

            if (is_array($this->where) && count($this->where) > 0) {
                foreach ($this->where as $key => $value) {
                    $key = str_replace('.', '_', $key);

                    $this->bind($key, $value);
                }
            }

            if (is_array($this->or_where) && count($this->or_where) > 0) {
                foreach ($this->or_where as $key => $value) {
                    $key = str_replace('.', '_', $key);

                    $this->bind($key, $value);
                }
            }

            return $this->execute();
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
    public function update($data = array())
    {
        try {
            $this->get_compiled_update($data);

            foreach ($this->where as $key => $value) {
                $this->bind($key, $value);
            }

            foreach ($data as $key => $value) {
                $this->bind($key, $value);
            }

            return $this->execute();
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

    /**
     * query
     * 
     * @param string
     * @return object $this
     */
    private function query($query)
    {
        $this->_statement = $this->_dbh->prepare($query);
        return $this;
    }

    /**
     * bind
     * 
     * @param string $param
     * @param string $value
     * @return object $this
     */
    private function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;

                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;

                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;

                default:
                    $type = PDO::PARAM_STR;
            }
        }

        $this->_statement->bindValue($param, $value, $type);
        return $this;
    }

    /**
     * execute
     * 
     * @property PDOStatement
     * @return bool
     */
    private function execute()
    {
        return $this->_statement->execute();
    }
}
