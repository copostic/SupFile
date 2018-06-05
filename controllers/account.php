<?php

$view = $page ?? 'information';
if (!empty($page)) {
    if ($page == 'information') {
    }
}

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    $smarty->display(VIEWS . 'inc/header.tpl');
    $smarty->display(VIEWS . 'account/' . $view .'.tpl');
    $smarty->display(VIEWS . 'inc/footer.tpl');
}