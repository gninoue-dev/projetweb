<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'uroot');
define('DB_PASS', 'Ozen2423@');
define('DB_NAME', 'examsecure');
define('BASE_URL', 'http://localhost/projetweb/');

$pdo = null;
try {
    $pdo = new PDO(
        "mysql:unix_socket=/opt/lampp/var/mysql/mysql.sock;dbname=examsecure;charset=utf8mb4",
        DB_USER, DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );
} catch (PDOException $e) {
    die("Erreur connexion DB: " . $e->getMessage());
}

session_start();
?>