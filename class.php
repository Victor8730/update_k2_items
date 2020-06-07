<?php
//header('Content-Type: text/html; charset=utf-8');
include 'config.php'; // get config variables $db_prefix, $db_user, $db_pass, $db_host, $db_name, $urlSite, $titleSite, $h1Site, $content
spl_autoload_register(function ($class_name) {
   include 'class/'.$class_name . '.php';
});



