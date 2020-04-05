<?php
error_reporting(0);
session_start();
$ssid = session_id();
$db_user = "root";
$db_pass = '';
$db_name = "calendarDatabase";
$DB = new mysqli("localhost", $db_user, $db_pass) or die($DB->connect_error);
$sql = "select schema_name from information_schema.schemata where schema_name = 'calendardatabase'";
$qry = $DB->query($sql);
if($qry->num_rows == 1)
{
    $DB->query("USE ".$db_name);
} else {
    include("./setup.php");
    exit();
}
?>