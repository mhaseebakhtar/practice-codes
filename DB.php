<?php

class DB {
    private $dbHost = "DBHOST";
    private $dbUsername = "DBUSER";
    private $dbPassword = "DBPASS";
    private $dbName = "DBNAME";
    private $db;
    private $table;
    private $columns;
    private $where;

    public function __construct() {
        if (!isset($this->db)) {
            // connect to the database
            $conn = new mysqli($this->dbHost, $this->dbUsername, $this->dbPassword, $this->dbName);
            if ($conn->connect_error) {
                die("Failed to connect with MySQL: " . $conn->connect_error);
            } else {
                $this->db = $conn;

                // set default values
                $this->columns = '*';
                $this->where = '';
            }
        }
    }

    /**
     * function to select a table for query operation
     * @param $table
     * @return DB
     */
    public function table($table) {
        $this->table = $table;

        return $this;
    }

    /**
     * function to select columns for query operation
     * @param $columns
     * @return DB
     */
    public function select($columns = ['*']) {
        $this->columns = implode(', ', $columns);

        return $this;
    }

    /**
     * function to select columns for query operation
     * @param $args
     * @return DB
     */
    public function where($args) {
        if (!empty($args)) {
            $condArr = array();
            foreach ($args as $key => $value) {
                $condArr[] = $key . " = '" . $this->db->real_escape_string($value) . "'";
            }

            $this->where = " WHERE " . implode(" AND ", $condArr);
        }

        return $this;
    }

    /**
     * function to insert data into DB
     * @param $args
     * @return false|mixed
     */
    public function insert($args) {
        $response = false;
        if (!empty($args)) {
            $columns = implode(", ", array_keys($args));
            $escaped_values = array_map(array($this->db, 'real_escape_string'), array_values($args));
            $values = implode("', '", $escaped_values);

            $result = $this->db->query("INSERT INTO " . $this->table . " ($columns) VALUES ('$values')");
            $response = $result ? $this->db->insert_id : false;
        }

        return $response;
    }

    /**
     * function to get data from DB
     * @return false|mixed
     */
    public function get() {
        $result = $this->db->query("SELECT " . $this->columns . " FROM " . $this->table . $this->where);
        return ($result->num_rows > 0) ? $result->fetch_assoc() : false;
    }

    /**
     * function to fetch data from DB
     * @return false|mixed
     */
    public function getAll() {
        $result = $this->db->query("SELECT " . $this->columns . " FROM " . $this->table . $this->where);
        return ($result->num_rows > 0) ? $result->fetch_all() : false;
    }

    /**
     * function to update data into DB
     * @param $data
     * @return false|mixed
     */
    public function update($data) {
        $response = false;
        if (!empty($data)) {
            $valueArr = array();
            foreach ($data as $key => $value) {
                $valueArr[] = $key . " = '" . $this->db->real_escape_string($value) . "'";
            }

            $response = $this->db->query("UPDATE " . $this->table . " SET " . implode(", ", $valueArr) . $this->where);
        }

        return $response;
    }

    /**
     * function to delete data from DB
     * @return false|mixed
     */
    public function delete() {
        return $this->db->query("DELETE FROM " . $this->table . $this->where);
    }
}