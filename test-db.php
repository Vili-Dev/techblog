<?php
require 'includes/pdo.php';
echo 'Connexion OK — ' . $pdo->query('SELECT 1')->fetchColumn();