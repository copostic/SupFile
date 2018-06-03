<?php

class DB extends PDO
{

    private static $instance;
    private $hostname;
    private $database;
    private $username;
    private $password;
    private $db;

    public function __construct() {
        try {
            $this->hostname = DB_HOST;
            $this->database = DB_NAME;
            $this->username = DB_USER;
            $this->password = DB_PASSWORD;
            $this->db = parent::__construct('mysql:host=' . $this->hostname . ';dbname=' . $this->database, $this->username, $this->password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            parent::setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception('Unable to connect to database.' . $e->getMessage());
        }
    }

    // Returns instance of object or creates it if it doesn't exist yet (singleton style)
    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new DB();
        }
        return self::$instance;
    }

    // Destroying database link at the end of execution
    public function __destruct() {
        $this->db = null;
    }

    // No cloning allowed
    private function __clone() {
    }

    public function result($query = false, $attr = false, $nofetch = false, $fetchtype = NULL) {
        $return = false;
        if ($query) {
            try {
                $stmt = parent::prepare($query);

                if ($attr)
                    $return = $stmt->execute($attr);
                else
                    $return = $stmt->execute();
            } catch (PDOException $e) {
                $error_message = $e->getMessage();
                $file_error = $e->getFile();
                $trace = $e->getTraceAsString();
                error_log("Error: " . $error_message . " On File: " . $file_error . " Traceback: " . $trace);
            }

            if ($nofetch) {
                return $return;
            } else {
                return $stmt->fetch($fetchtype);
            }
        }
        return false;
    }

    public function results($query = false, $attr = false, $nofetch = false, $fetchtype = NULL) {
        if ($query) {
            try {
                $stmt = parent::prepare($query);

                if ($attr) {
                    $return = $stmt->execute($attr);
                } else {
                    $return = $stmt->execute();
                }
            } catch (PDOException $e) {
                err("Error: " . $e->getMessage());
            }

            if ($nofetch)
                return $return;
            else
                return $stmt->fetchAll($fetchtype);
        }
        return false;
    }

    /**
     * @param bool $table
     * @param bool $attr
     * @return bool|null|string
     */
    public function insert($table = false, $attr = false) {
        if ($table && $attr) {
            $names = join(array_keys($attr), ",");
            $values = join(array_fill(0, count($attr), "?"), ",");
            $attr = array_values($attr);
            $result = null;
            try {
                $stmt = parent::prepare("INSERT INTO `" . $table . "` ($names) VALUES ($values)");
                $result = $stmt->execute($attr);

                if ($result)
                    $result = parent::lastInsertId();
            } catch (PDOException $e) {
                error_log("Error: " . $e->getMessage());
            }

            return $result;
        }
        return false;
    }

    public function update($table = false, $attr = false, $where = 'id') {
        if ($table && $attr) {
            $whereval = $attr[$where];
            unset($attr[$where]);
            $names = join(array_keys($attr), " = ?, ") . " = ?";

            $attr = array_values($attr);
            $attr[] = $whereval;
            $result = null;
            try {
                $stmt = parent::prepare("UPDATE `" . $table . "` SET $names WHERE $where = ?");
                //                error_log("UPDATE `".$table."` SET $names WHERE $where = ?");
                $result = $stmt->execute($attr);
            } catch (PDOException $e) {
                error_log("Error: " . $e->getMessage());
            }

            return $result;
        }
        return false;
    }

    public function count($table, $column, $value, $countCol = 'id') {
        $result = null;

        try {
            $stmt = parent::prepare("SELECT COUNT( `" . $countCol . "`) FROM $table WHERE $column = ?");
            //                error_log("UPDATE `".$table."` SET $names WHERE $where = ?");
            $result = $stmt->execute([$value]);
            if ($result)
                $result = $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Error: " . $e->getMessage());
        }
        return reset($result);
    }
}
