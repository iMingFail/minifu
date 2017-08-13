<?php
/**
 * Created by PhpStorm.
 * User: tangjim
 * Date: 2016/10/17
 * Time: 4:45
 */
$type = $_GET['t'];
if (!empty($type) && $type != "") {
    if ($type == "u") {
        require_once 'user.php';
    }
}
if (!empty($type) && $type != "") {
    if ($type == "s") {
        require_once 'setting.php';
    }
}
if (!empty($type) && $type != "") {
    if ($type == "m") {
        require_once 'admin.php';
    }
}
if (!empty($type) && $type != "") {
    if ($type == "v") {
        require_once 'vouchers.php';
    }
}
if (!empty($type) && $type != "") {
    if ($type == "t") {
        require_once 'top_up.php';
    }
}