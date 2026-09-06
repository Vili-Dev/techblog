<?php
require 'protection.php';

$id = intval($_GET['id'] ?? 0);

$stmt = $pdo->prepare('DELETE FROM articles WHERE id = ? AND auteur_id = ?');
$stmt->execute([$id, $_SESSION['user_id']]);

header('Location: index.php');
exit;