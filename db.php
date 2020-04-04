<?php
error_reporting(0);
session_start();
$ssid = session_id();
$db_user = "root";
$db_pass = '';
$db_name = "calendarDatabase";
$DB = new mysqli("localhost", $db_user, $db_pass, $db_name) or die($DB->connect_error);
?>