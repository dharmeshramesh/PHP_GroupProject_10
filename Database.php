<?php
class Database {
    private $connection;

    public function __construct($host = "localhost", $user = "root", $password = "", $database = "php_group_project") {
        $this->connection = new mysqli($host, $user, $password, $database);

        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }

    public function query($sql) {
        return $this->connection->query($sql);
    }

    public function prepare($query) {
        return $this->connection->prepare($query);
    }

    public function escape_string($value) {
        return $this->connection->real_escape_string($value);
    }

    public function __destruct() {
        $this->connection->close();
    }

    public function getConnection() {
        return $this->connection;
    }
}
?>
