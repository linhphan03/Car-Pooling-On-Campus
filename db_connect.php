<?php
$server = "cray.cs.gettysburg.edu";
$dbase = "s25_lps";
$user = "hasasp01";
$pass = "hagasp01";
$dsn = "mysql:host=$server;dbname=$dbase"; // data source name
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];
try {
    $db = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    error_log($e->getMessage());
    exit();
}
?>
