<?php
require 'includes/pdo.php';

session_start();   // ① OUVRE la session AVANT TOUT HTML

$erreurs = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $motdepasse = $_POST['motdepasse'] ?? '';

    // Validation basique
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreurs[] = 'L\'email est invalide.';
    }
    if ($motdepasse === '') {
        $erreurs[] = 'Le mot de passe est obligatoire.';
    }

    if (empty($erreurs)) {
        // ② CHERCHE le user par son email (requête préparée)
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // ③ VÉRIFIE le mot de passe contre le hash
        if ($user && password_verify($motdepasse, $user['mot_de_passe'])) {

            // ④ CONNEXION 
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nom'] = $user['nom'];

            // ⑤ ANTI-FIXATION : nouvel ID de session
            session_regenerate_id(true);

            header('Location: index.php');
            exit;

        } else {
            // Message VOLONTAIREMENT vague
            $erreurs[] = 'Email ou mot de passe incorrect.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - TechBlog</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <a href="index.php" class="logo">TechBlog</a>

        <nav>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="articles.php">Articles</a></li>
                <li><a href="about.html">À propos</a></li>
                <li><a href="contact.html">Contact</a></li>
            </ul>
        </nav>

        <div class="header-actions">
            <button id="theme-toggle" aria-label="Basculer le thème clair/sombre">🌗</button>
            <button id="menu-toggle" aria-label="Ouvrir le menu" class="menu-toggle">☰</button>
        </div>
    </header>
    <main>
        <div class="page-content">
            <h1 class="section-title">Connexion</h1>

            <?php if (!empty($erreurs)): ?>
                <ul class="erreurs">
                    <?php foreach ($erreurs as $erreur): ?>
                        <li><?= htmlspecialchars($erreur) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <form method="post" action="login.php">
                <div>
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div>
                    <label for="motdepasse">Mot de passe</label>
                    <input type="password" id="motdepasse" name="motdepasse" required>
                </div>
                <button type="submit">Se connecter</button>
            </form>
            <p>Pas de compte ? <a href="register.php">S'inscrire</a></p>
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