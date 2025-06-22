<?php

    require_once __DIR__.'/../config/database.config.php';

    $conn = mysqli_connect($host, $username, $pw, $db);

    if(!$conn) {
        echo "koneksi gagal";
    } 


?>