<?php
require_once "connection.php";
require_once "db.php";
$link=mysqli_connect($host, $user, $password, $database)
  or die("Error ". mysqli_error($link));

if(isset($_GET['id']) && is_numeric($_GET['id'])){
 Weather::delete($link, $_GET['id']);
}
header("Location: /index.php");

?>