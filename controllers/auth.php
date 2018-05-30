<?php

use Hybridauth\Exception\Exception;
use Hybridauth\Hybridauth;
use Hybridauth\HttpClient;
use Hybridauth\Storage\Session;

$vue = $page ?? 'login';
if (!empty($page)) {
    if ($page == 'social') {
        try {
            $hybridauth = new Hybridauth($config);

            $storage = new Session();

            if (isset($_GET['provider'])) {
                $storage->set('provider', $_GET['provider']);
            }

            if ($provider = $storage->get('provider')) {
                $hybridauth->disconnectAllAdapters();
                $adapter = $hybridauth->authenticate($provider);
                $storage->set('provider', null);
                if ($adapter->isConnected()) {
                    $profile = $adapter->getUserProfile();
                    $userExist = $db->count('users', 'email', $profile->email);
                    if ($userExist) {
                        $result = $db->result('SELECT first_name, last_name, password FROM users WHERE email = ?', [$profile->email]);
                    } else {
                        $result = $db->result("INSERT INTO users (email, first_name, last_name, available_space, total_space) VALUES (?,?,?,?,30,30)", [$profile->email, $profile->firstName, $profile->lastName]);
                    }
                    $_SESSION['connected'] = true;
                    $_SESSION['email'] = $profile->email;
                    $_SESSION['first_name'] = $result['first_name'] ?? $profile->firstName;
                    $_SESSION['last_name'] = $result['last_name'] ?? $profile->lastName;

                }
            }

            if (isset($_GET['logout'])) {
                $adapter = $hybridauth->getAdapter($_GET['logout']);
                $adapter->disconnect();
                $hybridauth->disconnectAllAdapters();
            }
            //HttpClient\Util::redirect('http://supfile.tk');
        } catch (Exception $e) {
            echo $e->getMessage();
        }

    } elseif ($page == 'login') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['email']) && !empty($_POST['password'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $userExist = $db->count('users', 'email', $email);
            if ($userExist) {
                $result = $db->result('SELECT first_name, last_name, password FROM users WHERE email = ?', [$email]);
                $encrypted_password = $result['password'] ?? '';
                if (password_verify($password, $encrypted_password)) {
                    $_SESSION['connected'] = true;
                    $_SESSION['email'] = $email;
                    $_SESSION['first_name'] = $result['first_name'] ?? 'John';
                    $_SESSION['last_name'] = $result['last_name'] ?? 'Doe';
                }
            }
        }

    } elseif ($page == 'register') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['password_verify']) && !empty($_POST['first_name']) && !empty($_POST['last_name'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $password_verify = $_POST['password_verify'];
            $first_name = $_POST['first_name'] ?? '';
            $last_name = $_POST['last_name'] ?? '';
            $userExist = $db->count('users', 'email', $email);
            if ($password == $password_verify) {
                if (!$userExist) {
                    $encrypted_password = password_hash($password, PASSWORD_ARGON2I);
                    $result = $db->result("INSERT INTO users (email, password, first_name, last_name, available_space, total_space) VALUES (?,?,?,?,30,30)", [$email, $encrypted_password, $first_name, $last_name]);
                    if ($result) {
                        $_SESSION['connected'] = true;
                        $_SESSION['email'] = $email;
                        $_SESSION['first_name'] = $result['first_name'] ?? 'John';
                        $_SESSION['last_name'] = $result['last_name'] ?? 'Doe';
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
        } else {
            $result = ['success' => 'false', 'message' => 'You must fill all the fields'];
        }
        $smarty->assign('result', json_encode($result));

    } elseif ($page == 'logout') {
        $hybridauth = new Hybridauth($config);
        $hybridauth->disconnectAllAdapters();
        unset($_SESSION);
        session_destroy();
        header('Location: /');
    }
} else {
    header('Location: /auth/login');
}

$smarty->display(VIEWS . 'inc/header.tpl');
$smarty->display(VIEWS . 'account/login.tpl');
$smarty->display(VIEWS . 'inc/footer.tpl');
