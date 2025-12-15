<?php
// logout.php
session_start();

// Distruge toate variabilele de sesiune
$_SESSION = array();

// Distruge sesiunea
session_destroy();

// Redirecționează la pagina de login
header("Location: login.php?logged_out=1");
exit();
?>