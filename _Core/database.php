<?php
DEFINE("dbserver", "");
DEFINE("dbuser", "");
DEFINE("dbpass", "");
DEFINE("dbname", "");

$db = new PDO(
                "mysql:host=" .dbserver. ";dbname=" .dbname,dbuser,dbpass,
                array(
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET CHARACTER SET utf8"
                )
);
?>