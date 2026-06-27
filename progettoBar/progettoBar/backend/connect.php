<?php
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "bar";
    
    $conn = new mysqli($host, $username, $password, $database);

    if(mysqli_connect_errno()){
        echo "Errore di connessione";
        exit();
    }
?>