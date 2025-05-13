<?php
require_once("init.php");
require_once("check-login.php");

if(isset($_POST["id"])) {$id = $_POST["id"];}
if(isset($_POST["name"])) {$name = $_POST["name"];}

switch($_POST["oper"]) {

 case "add":
    $sql = "insert into city (name) values ('$name')";
    if(!mysqli_query($db_handler, $sql)) {
        header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
        header('Content-Type: text/html; charset=utf-8');
        echo "Error adding city: ".mysqli_error($db_handler);
    }
    break;
 
 case "edit":
    $sql = "update city
            set name='$name'
            where id=$id";
    if(!mysqli_query($db_handler, $sql)) {
        header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
        header('Content-Type: text/html; charset=utf-8');
        echo "Ошибка изменения city: ".mysqli_error($db_handler);
    }
    break;
 
 case "del":
    $sql = "DELETE FROM city WHERE id=$id";
    if(!mysqli_query($db_handler, $sql)) {
        header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
        header('Content-Type: text/html; charset=utf-8');
        echo "Error deleting city: ".mysqli_error($db_handler);
    }
    break;
 }

mysqli_close($db_handler);
?>
