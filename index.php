<?php
require("db.php");
$sql = "SELECT * FROM `users` WHERE `ssid` = '".$ssid."'";
$qry = $DB->query($sql);
if($qry->num_rows == 1)
{
    $usr = $qry->fetch_assoc();
    include('home.php');
} else {
    include('login.php');
}
?>