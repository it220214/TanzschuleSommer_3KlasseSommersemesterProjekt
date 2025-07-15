<?php
$servername = 'db_server';
$port = 3306;
$username = "tsSommer";
$password = "tsSommerSommerts";
$dbname = "tsSommer";

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed" . $conn->connect_error);
}
