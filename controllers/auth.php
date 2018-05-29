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

    }
    elseif ($page == 'login') {

    } elseif ($page == 'register') {

    }
} else {
    header('Location: /auth/login');
}

$smarty->display(VIEWS . 'account/' . 'login' . '.tpl');

//        password_hash($pass, PASSWORD_ARGON2I);
