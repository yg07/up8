<?php
session_start();
$hostname="127.0.0.1";
$username="root";
$password="root";
$dbname="up8";

$db_handler=mysqli_connect($hostname,$username, $password) or die ("Не удается подключиться к базе данных!");
mysqli_select_db($db_handler,$dbname) or die ("Ошибка выбора базы данных!");
