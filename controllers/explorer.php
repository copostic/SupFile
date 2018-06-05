<?php

require_once LIB . 'mime_type_lib.php';

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


function rmdir_recursive($dir) {
    foreach (scandir($dir) as $file) {
        if ('.' === $file || '..' === $file) continue;
        if (is_dir("$dir/$file")) rmdir_recursive("$dir/$file");
        else unlink("$dir/$file");
    }
    rmdir($dir);
}

if (empty($_SESSION['connected'])) {
    header('Location: /');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? null;
    $path = $_POST['path'] ?? null;
    $newName = $_POST['newName'] ?? null;
    $folderName = $_POST['folderName'] ?? null;
    $checkPath = explode('/', $path);
    if ($checkPath[0] == 'files' && $checkPath[1] == $_SESSION['uuid'] && !in_array('..', $checkPath) && !in_array('.', $checkPath)) {
        switch ($action) {
            case 'download':
                if (file_exists($path)) {
                    $type = get_file_mime_type($path);
                    header("Pragma: public");
                    header("Expires: -1");
                    header('Cache-Control: must-revalidate');
                    header('Content-Type: ' . $type);
                    header('Content-Disposition: attachment; filename="' . end($checkPath) . '"');
                    header('Content-Length: ' . filesize($path));
                    if (ob_get_level()) ob_end_clean();
                    return readfile($path);
                } else {
                    $result = ['success' => 'false', 'message' => 'File doesn\'t exist'];
                }
                break;

            case 'rename':
                if (file_exists($path)) {
                    array_pop($checkPath);
                    $result = rename($path, implode('/', $checkPath) . '/' . $newName);
                    if ($result)
                        $result = ['success' => 'true', 'message' => 'File successfully renamed'];
                } else
                    $result = ['success' => 'false', 'message' => 'Error while renaming file'];
                break;
            case 'delete':
                if (file_exists($path)) {
                    if (is_dir($path)) {
                        rmdir_recursive($path);
                        $result = ['success' => 'true', 'message' => 'Directory successfully deleted.'];
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

            case 'add':
                $count = 1;
                $fullDir = PATH . $path . '/' . $folderName;
                if (file_exists($fullDir) && is_dir($fullDir)) {
                    if (!file_exists($fullDir . ' (1)')) {
                        mkdir($fullDir . ' (1)', 0777, true);
                    } else {
                        while (file_exists($fullDir . ' (' . $count . ')')) {
                            $count += 1;
                        }
                        mkdir($fullDir . ' (' . $count . ')', 0777, true);
                    }
                } else {
                    mkdir($fullDir, 0777, true);
                }
                if ($result)
                    $result = ['success' => 'true', 'message' => 'Directory successfully created.'];
                else
                    $result = ['success' => 'true', 'message' => 'Error while creating the directory.'];
                break;


        }
    }
    $userDirectory = 'files/' . $_SESSION['uuid'];
    if (!file_exists($userDirectory)) {
        mkdir($userDirectory);
        copy(PATH . '/files/example/README.txt', $userDirectory . '/README.txt');
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
    $smarty->assign('title', 'Explorer');
    $smarty->display(VIEWS . 'inc/header.tpl');
    $smarty->display(VIEWS . 'account/explorer.tpl');
    $smarty->display(VIEWS . 'inc/footer.tpl');
    //header('Location: /');
}



