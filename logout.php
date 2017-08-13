<?php
/**
 * Created by PhpStorm.
 * User: tangjim
 * Date: 2016/12/18
 * Time: 1:22
 */
session_start();
if(isset($_SESSION["user"])) {
    // $_SESSION["user"] = null;
    unset($_SESSION["user"]);
}
header("Location:login.html");