<?php
// db_connect.php

// ATENȚIE: Aceste credențiale TREBUIE să se potrivească cu cele create de tine la Pasul 2.

$host = 'lamp_mysql'; 
$db   = 'web_db';
$user = 'web_user';      // Noul utilizator cu permisiuni minime
$pass = 'PAROLA_PUTERNICA_PHP'; // Parola puternică setată de tine
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     // În producție, mesajul de eroare ar trebui să fie mai generic
     die("Eroare fatală de conexiune la baza de date: " . $e->getMessage());
}
?>