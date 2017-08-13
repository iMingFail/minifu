<?php
/**
 * Created by PhpStorm.
 * User: tangjim
 * Date: 2016/12/18
 * Time: 0:57
 */
session_start();
if(!isset($_SESSION["user"])) {
    $url = "login.html";
    header("Location:$url");
    exit;
}
?>