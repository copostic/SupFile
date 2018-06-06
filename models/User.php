<?php

class User
{
    public $firstName;
    public $lastName;
    public $email;
    public $password;
    public $uuid;
    public static $instance = null;
    public $session;
    public $connected;
    public $db;

    private function __construct() {
        $this->db = DB::getInstance();
        $this->session = session_id();
    }

    /**
     * @return User|null
     */
    public static function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance = new User();
        }
        return self::$instance;
    }


    public function createOnDB($email, $firstName, $lastName, $password = '') {
        return $this->db->result('INSERT INTO users (email, first_name, last_name, password, uuid, available_space, total_space) VALUES (?,?,?,?,30,30);', [$email, $firstName, $lastName, $password, random_text()]);
    }

    public function checkIfExists($email) {
        return $this->db->count('users', 'email', $email);
    }

    public function getByEmail($email) {
        return $this->db->result('SELECT id, first_name, last_name, password, uuid, available_space, total_space FROM users WHERE email = ?', [$email]);
    }

    /**
     * @param $email
     * @param $password
     * @return array|bool|mixed
     */
    public function localLogin($email, $password) {
        $userExist = $this->checkIfExists($email);
        if ($userExist) {
            $result = $this->getByEmail($email);
            $encrypted_password = $result['password'] ?? '';
            if (password_verify($password, $encrypted_password)) {
                $_SESSION['connected'] = $this->connected = true;
                $_SESSION['email'] = $this->email = $email;
                $_SESSION['first_name'] = $this->firstName = $result['first_name'] ?? 'John';
                $_SESSION['last_name'] = $this->lastName = $result['last_name'] ?? 'Doe';
                $_SESSION['uuid'] = $this->uuid= $result['uuid'] ?? '';
                $result = ['success' => 'true', 'message' => 'User successfully connected'];
            } else {
                $result = ['success' => 'false', 'message' => 'Password mismatch.'];
            }
        } else {
            $result = ['success' => 'false', 'message' => 'User doesn\'t exist'];
        }
        return $result;
    }

    /**
     * @param $email
     * @param $password
     * @param $passwordVerify
     * @param $firstName
     * @param $lastName
     * @return array|bool|mixed
     */
    public function localRegister($email, $password, $passwordVerify, $firstName, $lastName) {
        $userExist = $this->checkIfExists($email);
        if ($password == $passwordVerify) {
            if (!$userExist) {
                $encryptedPassword = password_hash($password, PASSWORD_ARGON2I);
                $uuid = random_text();
                $result = $this->db->result("INSERT INTO users (email, password, first_name, last_name, uuid, available_space, total_space) VALUES (?,?,?,?,?,30,30)", [$email, $encryptedPassword, $firstName, $lastName, $uuid], true);
                if ($result) {
                    $_SESSION['connected'] = $this->connected = true;
                    $_SESSION['email'] = $this->email = $email;
                    $_SESSION['first_name'] = $this->firstName = $firstName ?? 'John';
                    $_SESSION['last_name'] = $this->lastName = $lastName ?? 'Doe';
                    $_SESSION['uuid'] = $this->uuid = $uuid;
                    $result = ['success' => 'true', 'message' => 'User successfully created!'];
                } else {
                    $result = ['success' => 'false', 'message' => 'An error occurred'];
                }
            } else {
                $result = ['success' => 'false', 'message' => 'User already exist'];
            }
        } else {
            $result = ['success' => 'false', 'message' => 'Password not equals'];
        }

        return $result;
    }

}