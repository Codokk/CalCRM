<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$db_user = "root";
$db_pass = '';
$DB = new mysqli("localhost", $db_user, $db_pass) or die($DB->connect_error);
$sql = "select schema_name from information_schema.schemata where schema_name = 'calendardatabase'";
$qry = $DB->query($sql);
if($qry->num_rows == 1)
{
    exit("Setup has already been performed");
} else {
    //Create calendar Database and populate
    $sqlArray = [
        "CREATE DATABASE calendardatabase",
        "Use calendardatabase",
        "CREATE TABLE `users` (
            `uid` int(100) NOT NULL AUTO_INCREMENT,
            `username` varchar(100) DEFAULT NULL,
            `password` varchar(100) DEFAULT NULL,
            `email` varchar(100) DEFAULT NULL,
            `phone` int(100) DEFAULT NULL,
            `ssid` varchar(100) DEFAULT NULL,
            PRIMARY KEY (`uid`)
          ) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;",
        "INSERT INTO `users` VALUES (1,'username','password','test@test.test',1234567890,'1111111111');",
        "CREATE TABLE `customers` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `fname` varchar(100) DEFAULT NULL,
            `lname` varchar(100) DEFAULT NULL,
            `email` varchar(100) DEFAULT NULL,
            `phone` varchar(100) DEFAULT NULL,
            `active` tinyint(1) DEFAULT 1,
            PRIMARY KEY (`id`)
        ) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;",
        "CREATE TABLE `services` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(100) DEFAULT NULL,
            `price` varchar(100) DEFAULT NULL,
            `duration` varchar(100) DEFAULT NULL,
            `active` tinyint(1) NOT NULL DEFAULT 1,
            PRIMARY KEY (`id`)
          ) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;",
          "CREATE TABLE `products` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(100) COLLATE utf8_bin DEFAULT NULL,
            `price` varchar(100) COLLATE utf8_bin DEFAULT NULL,
            `stock` varchar(100) COLLATE utf8_bin DEFAULT NULL,
            `active` tinyint(1) NOT NULL DEFAULT 1,
            PRIMARY KEY (`id`)
          ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;",
          "CREATE TABLE `appointments` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `cid` varchar(100) DEFAULT NULL,
            `items` varchar(100) DEFAULT NULL,
            `date` varchar(100) DEFAULT NULL,
            `duration` varchar(100) DEFAULT NULL,
            `status` int(11) DEFAULT 1,
            `eid` int(11) DEFAULT NULL,
            PRIMARY KEY (`id`)
          ) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;"
    ];
    
    for($i = 0; $i < count($sqlArray); $i++)
    {
        $DB->query($sqlArray[$i]) or die($DB->error);
    }
    ?>

    <h1>Setup Completed!</h1>
    <br>
    <h4><strong>Username</strong>: username<br/>
    <strong>Password</strong>: password </h4>
    <br/>
    <a href="./index.php">Log In</a>
    <?php
    exit;
}
?>