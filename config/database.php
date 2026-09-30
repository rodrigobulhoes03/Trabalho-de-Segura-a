<?php

$conn = mysqli_connect('localhost', 'root', '1234', 'database_registos');

if (!$conn) {
    error_log(mysqli_connect_error());
    exit('Erro');
}

mysqli_set_charset($conn, 'utf8mb4');