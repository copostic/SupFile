<?php

require_once LIB . 'mime_type_lib.php';

/**
 * @param $path
 * @return array
 */
function scan($path) {
    $files = [];
    if (file_exists($path)) {
        foreach (scandir($path) as $file) {
            if (!$file || $file[0] == '.') {
                continue;
            }
            if (is_dir($path . '/' . $file)) {
                $files[] = [
                    "name" => $file,
                    "type" => "folder",
                    "path" => $path . '/' . $file,
                    "items" => scan($path . '/' . $file)
                ];
            } else {
                $files[] = [
                    "name" => $file,
                    "type" => "file",
                    "path" => $path . '/' . $file,
                    "size" => filesize($path . '/' . $file)
                ];
            }
        }

    }

    return $files;
}


function rmdirRecursive($path) {
    foreach (scandir($path) as $file) {
        if ('.' === $file || '..' === $file) continue;
        if (is_dir("$path/$file")) rmdirRecursive("$path/$file");
        else unlink("$path/$file");
    }
    rmdir($path);
}

if (file_exists('test.zip')) {
    unlink('test.zip');

}


function createZip($source, $destination) {
    if (!extension_loaded('zip') || !file_exists($source)) {
        return false;
    }

    $zip = new ZipArchive();
    if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
        return false;
    }

    $source = str_replace('\\', '/', $source);

    if (is_dir($source) === true) {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

        foreach ($files as $file) {
            if ($file->getFilename() != '.' && $file->getFilename() != '..' && $file->getFilename() != 'C:' && $file->getFilename() != 'E:') {
                $file = str_replace('\\', '/', $file);

                if (is_dir($file) === true) {
                    $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
                } else if (is_file($file) === true) {
                    $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
                }
            }
        }
    } else if (is_file($source) === true) {
        $zip->addFromString(basename($source), file_get_contents($source));
    }
    return $zip->close();
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
                    if (is_dir($path)) {
                        $arrayFile = explode('/', $path);
                        $folderName = array_pop($arrayFile);
                        $folderName .= '.zip';
                        createZip($path, 'files/zip/' . $folderName);
                        $path = 'files/zip/' . $folderName;
                    }
                    $type = get_file_mime_type($path);
                    header("Pragma: public");
                    header("Expires: -1");
                    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                    header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime('test.zip')) . ' GMT');
                    header('Content-Type: ' . $type);
                    header('Content-Disposition: attachment; filename="' . end($checkPath) . '"');
                    header('Content-Length: ' . filesize($path));
                    header('Connection: close');
                    if (ob_get_level()) ob_end_clean();
                    return readfile($path);
                } else {
                    $result = ['success' => 'false', 'message' => 'File doesn\'t exist'];
                }
                break;

            case 'rename':
                if (file_exists($path)) {
                    array_pop($checkPath);
                    $newPath = implode('/', $checkPath) . '/' . $newName;
                    $newNameWithoutExt = explode('.', $newName);
                    $fileExtension = array_pop($newNameWithoutExt);
                    $newNameWithoutExt = $newNameWithoutExt[0];
                    if (file_exists($newPath)) {
                        $count = 1;
                        if (!file_exists(implode('/', $checkPath) . '/' . $newNameWithoutExt . ' (1).' . $fileExtension)) {
                            $result = rename($path, implode('/', $checkPath) . '/' . $newNameWithoutExt . ' (1).' . $fileExtension);
                        } else {
                            $test = implode('/', $checkPath) . '/' . $newNameWithoutExt . ' (' . $count . ').' . $fileExtension;
                            while (file_exists(implode('/', $checkPath) . '/' . $newNameWithoutExt . ' (' . $count . ').' . $fileExtension)) {
                                $count += 1;
                            }
                            $result = rename($path, implode('/', $checkPath) . '/' . $newNameWithoutExt . ' (' . $count . ').' . $fileExtension);
                        }
                    } else {
                        $result = rename($path, $newPath);
                    }
                    if ($result)
                        $result = ['success' => 'true', 'message' => 'File successfully renamed'];
                } else
                    $result = ['success' => 'false', 'message' => 'Error while renaming file'];
                break;
            case 'delete':
                if (file_exists($path)) {
                    if (is_dir($path)) {
                        rmdirRecursive($path);
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
    if (empty($action) || $action == "delete" || $action == 'rename') {
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
        echo json_encode($result) ?? '';
    }
} else {
    $smarty->assign('title', 'Explorer');
    $smarty->display(VIEWS . 'inc/header.tpl');
    $smarty->display(VIEWS . 'account/explorer.tpl');
    $smarty->display(VIEWS . 'inc/footer.tpl');
    //header('Location: /');
}



