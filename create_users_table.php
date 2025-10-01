<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bookshop";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// sql to drop table
$sql = "DROP TABLE IF EXISTS ci_users;";

if ($conn->query($sql) === TRUE) {
    echo "Table ci_users dropped successfully";
} else {
    echo "Error dropping table: " . $conn->error;
}

$conn->close();
