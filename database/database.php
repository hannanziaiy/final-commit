<?php

$host =  "localhost";
$username =  "root";
$password =  '';
$db;
$table;



try {
    $conn = new PDO("mysql:host=$host", $username, $password);

    // echo "conect! <br/>";
} catch (PDOException $e) {
    die(header('Location: html/500.html'));
} finally {
}
