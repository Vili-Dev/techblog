<?php
require 'includes/pdo.php';

$erreurs = [];

// ══════════ TRAITEMENT (si le formulaire est soumis) ══════════
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $motdepasse = $_POST['motdepasse'] ?? '';

    // --- VALIDATION SERVEUR (never trust client input) ---
    if ($nom === '') {
        $erreurs[] = 'Le nom est obligatoire.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreurs[] = 'L\'email est invalide.';
    }
    if (strlen($motdepasse) < 8) {
        $erreurs[] = 'Le mot de passe doit faire au moins 8 caractères.';
    }

    // --- EMAIL DÉJÀ PRIS ? ---
    if (empty($erreurs)) {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $erreurs[] = 'Cet email est déjà utilisé.';
        }
    }

    // --- TOUT EST BON → INSERTION ---
    if (empty($erreurs)) {
        $hash = password_hash($motdepasse, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare('
            INSERT INTO users (email, mot_de_passe, nom)
            VALUES (?, ?, ?)
        ');
        $stmt->execute([$email, $hash, $nom]);

        // Redirection : on renvoie VERS la page de connexion
        header('Location: login.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - TechBlog</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <a href="index.html" class="logo">TechBlog</a>
        
        <nav>
            <ul>
                <li><a href="index.html">Accueil</a></li>
                <li><a href="articles.php">Articles</a></li>
                <li><a href="about.html">À propos</a></li>
                <li><a href="contact.html">Contact</a></li>
            </ul>
        </nav>
        <button id="theme-toggle" aria-label="Basculer le thème clair/sombre">🌗</button>
        <button id="menu-toggle" aria-label="Ouvrir le menu" class="menu-toggle">☰</button>
    </header>
    <main>
        <div class="page-content">
            <h1 class="section-title">Créer un compte</h1>

            <?php if (!empty($erreurs)): ?>
                <ul class="erreurs">
                    <?php foreach ($erreurs as $erreur): ?>
                        <li><?= htmlspecialchars($erreur) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <form method="post" action="register.php">
                <div>
                    <label for="nom">Nom</label>
                    <input type="text" id="nom" name="nom" required>
                </div>
                <div>
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div>
                    <label for="motdepasse">Mot de passe (8 caractères min.)</label>
                    <input type="password" id="motdepasse" name="motdepasse" required>
                </div>
                <button type="submit">S'inscrire</button>
            </form>
            <p>Déjà un compte ? <a href="login.php">Se connecter</a></p>
        </div>
    </main>
    <footer>
        <ul>
            <li><a href="about.html">À propos</a></li>
            <li><a href="contact.html">Contact</a></li>
        </ul>
        <p>&copy; 2026 TechBlog - Projet de formation DWWM</p>
    </footer>
    <script src="js/main.js"></script>
</body>
</html>