<?php

if(!empty($_SESSION['connected'])){
	header('Location:/explorer');
	exit;
}

if ($action == 'home' || $action == 'terms-and-conditions') {
    $title = '';
} else {
    $action = 404;
}

if ($action == 404) {
    header("HTTP/1.0 404 Not Found");
    $smarty->display(VIEWS . 'inc/header.tpl');
    $smarty->display(VIEWS . '404.tpl');
} else {
    $smarty->display(VIEWS . 'inc/header.tpl');
    $smarty->display(VIEWS . $action . '.tpl');
}


$smarty->display(VIEWS . 'inc/footer.tpl');

