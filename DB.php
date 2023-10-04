<?php

class DB {
    private $dbHost = "DBHOST";
    private $dbUsername = "DBUSER";
    private $dbPassword = "DBPASS";
    private $dbName = "DBNAME";
    private $db;

    public function __construct() {
        if (!isset($this->db)) {
            // connect to the database
            $conn = new mysqli($this->dbHost, $this->dbUsername, $this->dbPassword, $this->dbName);
            if ($conn->connect_error) {
                die("Failed to connect with MySQL: " . $conn->connect_error);
            } else {
                $this->db = $conn;
            }
        }
    }

    /**
     * function to insert data into DB
     * @param $table
     * @param $args
     * @return false|mixed
     */
    public function create($table, $args) {
        $response = false;
        if (!empty($args)) {
            $columns = implode(", ", array_keys($args));
            $escaped_values = array_map(array($this->db, 'real_escape_string'), array_values($args));
            $values = implode("', '", $escaped_values);

            $result = $this->db->query("INSERT INTO $table ($columns) VALUES ('$values')");
            $response = $result ? $this->db->insert_id : false;
        }

        return $response;
    }

    /**
     * function to read data from DB
     * @param $table
     * @param $args
     * @return false|mixed
     */
    public function read($table, $args) {
        $response = false;
        if (!empty($args)) {
            $condArr = array();
            foreach ($args as $key => $value) {
                $condArr[] = $key . " = '" . $this->db->real_escape_string($value) . "'";
            }

            $result = $this->db->query("SELECT * FROM $table WHERE " . implode(" AND ", $condArr));
            if ($result->num_rows == 1) {
                $response = array('mode' => 'single', 'result' => $result->fetch_assoc());
            } else if ($result->num_rows > 1) {
                $response = array('mode' => 'multi', 'result' => $result->fetch_all());
            }
        }

        return $response;
    }

    /**
     * function to update data into DB
     * @param $table
     * @param $data
     * @param $args
     * @return false|mixed
     */
    public function update($table, $data, $args) {
        $response = false;
        if (!empty($data) && !empty($args)) {
            $valueArr = array();
            foreach ($data as $key => $value) {
                $valueArr[] = $key . " = '" . $this->db->real_escape_string($value) . "'";
            }

            $condArr = array();
            foreach ($args as $key => $value) {
                $condArr[] = $key . " = '" . $this->db->real_escape_string($value) . "'";
            }

            $response = $this->db->query("UPDATE $table SET " . implode(", ", $valueArr) . " WHERE " . implode(" AND ", $condArr));
        }

        return $response;
    }

    /**
     * function to delete data from DB
     * @param $table
     * @param $args
     * @return false|mixed
     */
    public function delete($table, $args) {
        $response = false;
        if (!empty($args)) {
            $condArr = array();
            foreach ($args as $key => $value) {
                $condArr[] = $key . " = '" . $this->db->real_escape_string($value) . "'";
            }

            $response = $this->db->query("DELETE FROM $table WHERE " . implode(" AND ", $condArr));
        }

        return $response;
    }
}