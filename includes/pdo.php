<?php
// ================================================
// PDO.PHP — connexion à la base de données
// ================================================

$hote = 'localhost';       // où est MySQL (notre machine)
$bdd = 'techblog';         // le nom de la base
$utilisateur = 'root';     // l'utilisateur (WAMP local)
$motdepasse = '';           // vide en local WAMP

try {
    $pdo = new PDO(
        "mysql:host=$hote;dbname=$bdd;charset=utf8mb4",
        $utilisateur,
        $motdepasse,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,   // erreurs = exceptions
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // fetch = tableaux associatifs
            PDO::ATTR_EMULATE_PREPARES => false,           // VRAIES requêtes préparées
        ]
    );
} catch (PDOException $e) {
    // En dev on affiche l'erreur ; en prod on la loggerait
    die('Erreur de connexion à la base de données.');
}