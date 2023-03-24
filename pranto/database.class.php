<?php
namespace Pranto;
use Exception;
use mysqli;

class Database
{
    private $_db;	// Database Connection
    private static $_instance;	// Class Instance

    /**
     * Database constructor.
     * @param $host
     * @param $user
     * @param $password
     * @param $database
     * @throws Exception
     */
    private function __construct($host, $user, $password, $database)
    {
        $this->_db = new mysqli($host, $user, $password, $database);

        // Deal with Errors
        if ($this->_db->connect_errno) {
            throw new Exception("Database Connection Error!");
        }
    }

    /**
     * @param $host
     * @param $user
     * @param $password
     * @param $database
     * @return Database
     */
    public static function getInstance($host, $user, $password, $database)
    {
        // Only One Instance
        if (!self::$_instance) {
            self::$_instance = new self($host, $user, $password, $database);
        }

        return self::$_instance;
    }

    /**
     * @return mysqli
     */
    public function getConnection()
    {
        return $this->_db;
    }

    /**
     * @param $table
     * @param string $fields
     * @param array $where
     * @param string $order
     * @param string $limit
     * @return mixed
     */
    public function select($table, $fields = "*", array $where, $order = "", $limit = "")
    {
        // MySQL Query
        $query = "SELECT $fields FROM $table";

        // WHERE Array
        if (!empty($where)) {
            $Where = array();

            foreach ($where as $key => $value) {
                $Where[] = "$key='$value'";
            }

            if (count($Where) > 1) {
                $query .= " WHERE ".implode(" AND ", $Where);
            } else {
                $query .= " WHERE ".$Where[0];
            }
        }

        // ORDER BY
        if ($order) {
            $query .= " ORDER BY $order";
        }

        // LIMIT
        if($limit){
            $query .= " LIMIT $limit";
        }

        return $this->_db->query($query);
    }

    /**
     * @param $table
     * @param array $inserts
     * @return mixed
     */
    public function insert($table, array $inserts)
    {
        // MySQL Query
        $query = "INSERT INTO $table (";

        // Column Name
        $column = array();

        // Values
        $values = array();

        // Loop Through
        foreach ($inserts as $key => $value) {
            $column[] = $key;
            $values[] = "'$value'";
        }

        // More than one insert
        if (count($column) > 1) {
            $query .= implode(",", $column).") VALUES (".implode(",", $values).")";
        } else {
            $query .= $column[0].") VALUES (".$values[0].")";
        }

        return $this->_db->query($query);
    }

    /**
     * @param $table
     * @param array $set
     * @param array $where
     * @return mixed
     */
    public function update($table, array $set, array $where)
    {
        // MySQL Query
        $query = "UPDATE $table SET";

        // New Data Set
        $Set = array();

        // Loop through set array
        foreach ($set as $key => $value) {
            $Set[] = "$key='$value'";
        }

        // Multiple update
        if (count($Set) > 1) {
            $query .= " ".implode(",",$Set);
        } else {
            $query .= " ".$Set[0];
        }

        // WHERE Array
        if (is_array($where)) {
            $Where = array();

            foreach ($where as $key => $value) {
                $Where[] = "$key='$value'";
            }

            if (count($Where) > 1) {
                $query .= " WHERE ".implode(" AND ",$Where);
            } else {
                $query .= " WHERE ".$Where[0];
            }
        }

        return $this->_db->query($query);
    }

    /**
     * @param $table
     * @param array $where
     * @return bool
     */
    public function delete($table, array $where)
    {
        // MySQL Query
        $query = "DELETE FROM $table";

        // WHERE
        $Where = array();

        foreach ($where as $key => $value) {
            $Where[] = "$key='$value'";
        }

        if (count($Where) > 1) {
            $query .= " WHERE ".implode(" AND ",$Where);
        } else {
            $query .= " WHERE ".$Where[0];
        }

        // Run Query
        if ($this->_db->query($query)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $table
     * @param string $fields
     * @param array $where
     * @return mixed
     */
    public function countRow($table, $fields = "*", array $where)
    {
        // MySQL Query
        $query = "SELECT COUNT($fields) FROM $table";

        // WHERE Array
        if (is_array($where)) {
            $Where = array();

            foreach ($where as $key => $value) {
                $Where[] = "$key='$value'";
            }

            if (count($Where) > 1) {
                $query .= " WHERE ".implode(" AND ", $Where);
            } else {
                $query .= " WHERE ".$Where[0];
            }
        }

        // Get Rows
        $rows = $this->_db->query($query);
        $row = $rows->fetch_row();

        return $row[0];
    }

    /**
     * @param $db
     */
    public function changeDb($db)
    {
        $this->_db->select_db($db);
    }

    /**
     * Destruct
     */
    public function __destruct()
    {
        $this->_db->close();
        $this->_db = null;
    }
}