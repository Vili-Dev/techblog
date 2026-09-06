<?php
session_start();
require 'includes/pdo.php';

// 1. RÉCUPÉRER l'id depuis l'URL (article.php?id=3)
$id = intval($_GET['id'] ?? 0);

// 2. VALIDER : si l'id est invalide (0, négatif, texte) → 404
if ($id <= 0) {
    http_response_code(404);
    die('Article introuvable.');
}

// 3. REQUÊTE PRÉPARÉE PARAMÉTRÉE — le ? reçoit l'id
$stmt = $pdo->prepare('
    SELECT articles.*, users.nom AS auteur
    FROM articles
    JOIN users ON articles.auteur_id = users.id
    WHERE articles.id = ?
');
$stmt->execute([$id]);
$article = $stmt->fetch();   // fetch() = UNE ligne, pas fetchAll()

// 4. Si aucun article avec cet id → 404
if (!$article) {
    http_response_code(404);
    die('Article introuvable.');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($article['titre']) ?> - TechBlog</title>
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
            <?php if (isset($_SESSION['user_id'])): ?>
                <span class="nav-user">Bonjour, <?= htmlspecialchars($_SESSION['user_nom']) ?></span>
                <a href="logout.php">Déconnexion</a>
            <?php else: ?>
                <a href="login.php">Connexion</a>
            <?php endif; ?>

            <button id="theme-toggle" aria-label="Basculer le thème clair/sombre">🌗</button>
            <button id="menu-toggle" aria-label="Ouvrir le menu" class="menu-toggle">☰</button>
        </div>
    </header>
    <main>
        <div class="page-content">
            <article class="article-full">
                <h1><?= htmlspecialchars($article['titre']) ?></h1>
                <p class="article-meta">
                    Par <?= htmlspecialchars($article['auteur']) ?>
                    - <?= $article['date_publication'] ?>
                </p>
                <p><?= htmlspecialchars($article['contenu']) ?></p>
            </article>
            <a href="articles.php" class="btn">← Retour aux articles</a>
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