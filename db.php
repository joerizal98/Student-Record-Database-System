<?php

$server = "localhost";
$user = "root";
$password = "";
$db = "fyp";

$link = mysqli_connect($server,$user,$password,$db);

if(!$link) {
    die("Connection Failed:".mysqli_connect_error());
}

?>