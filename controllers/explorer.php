<?php

/**
 * @param $directory
 * @return array
 */
function scan($directory) {
    $files = [];
    if (file_exists($directory)) {
        foreach (scandir($directory) as $file) {
            if (!$file || $file[0] == '.') {
                continue;
            }
            if (is_dir($directory . '/' . $file)) {
                $files[] = [
                    "name" => $file,
                    "type" => "folder",
                    "path" => $directory . '/' . $file,
                    "items" => scan($directory . '/' . $file)
                ];
            } else {
                $files[] = [
                    "name" => $file,
                    "type" => "file",
                    "path" => $directory . '/' . $file,
                    "size" => filesize($directory . '/' . $file)
                ];
            }
        }

    }

    return $files;
}

if(empty($_SESSION['connected'])){
   header('Location: /');
   exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? null;
    $path = $_POST['path'] ?? null;
    $newName = $_POST['new_name'] ?? null;
    $checkPath = explode('/', $path);
    if ($checkPath[0] == 'files' && $checkPath[1] == $_SESSION['uuid'] && !in_array('..', $checkPath) && !in_array('.', $checkPath)) {
        switch ($action) {
            case 'rename':
                if (file_exists($path)) {
                    array_pop($checkPath);
                    $result = rename($path, implode('/', $checkPath) . $newName);
                    if ($result)
                        $result = ['success' => 'true', 'message' => 'File successfully renamed'];
                } else
                    $result = ['success' => 'false', 'message' => 'Error while renaming file'];
                break;
            case 'delete':
                if (file_exists($path)) {
                    if (is_dir($path)) {
                        $result = rmdir($path);
                        if ($result)
                            $result = ['success' => 'true', 'message' => 'Directory successfully deleted.'];
                        else
                            $result = ['success' => 'false', 'message' => 'Error while deleting directory.'];
                    } else {
                        $result = unlink($path);
                        if ($result)
                            $result = ['success' => 'true', 'message' => 'File successfully deleted.'];
                        else
                            $result = ['success' => 'true', 'message' => 'Error while deleting file.'];
                    }
                } else {
                    $result = ['success' => 'false', 'message' => 'File doesn\'t exist.'];
                }
                break;
        }
    }
    $userDirectory = 'files/' . $_SESSION['uuid'];
    if (!file_exists($userDirectory)) {
        mkdir($userDirectory);
        copy(PATH . '/files/example/README.txt', $userDirectory . 'README.txt');
    }
    $response = scan($userDirectory);

    header('Content-type: application/json');

    echo json_encode(array(
        "name" => "files",
        "type" => "folder",
        "path" => $userDirectory,
        "items" => $response
    ));
} else {

    $smarty->display(VIEWS . 'inc/header.tpl');
    $smarty->display(VIEWS . 'account/explorer.tpl');
    $smarty->display(VIEWS . 'inc/footer.tpl');
    //header('Location: /');
}



