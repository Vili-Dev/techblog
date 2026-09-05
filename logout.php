<?php
session_start();

// Détruire complètement la session
$_SESSION = [];
session_destroy();

header('Location: index.php');
exit;