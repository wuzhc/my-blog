<?php
/**
 * Created by PhpStorm.
 * User: wuzhc
 * Date: 19-4-17
 * Time: 下午2:33
 */

function getFiles($dir)
{
    $data = [];

    $files = scandir($dir);
    foreach ($files as $file) {
        if (strpos($file, '.') === 0) {
            continue;
        }
        if (is_dir($dir . '/' . $file)) {
            $data[$file] = getFiles($dir . '/'.$file);
        } else {
            $data[] = $file;
        }
    }

    return $data;
}

function getDirs($dir)
{
    $data = [];

    $files = scandir($dir);
    foreach ($files as $file) {
        if (strpos($file, '.') === 0) {
            continue;
        }
        if (is_file($dir . '/' . $file)) {
            continue;
        }
        $data[] = $file;
    }

    return $data;
}