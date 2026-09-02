<?php
require 'includes/pdo.php';

// REQUÊTE PRÉPARÉE (même sans paramètre, on garde le réflexe)
$stmt = $pdo->prepare('
    SELECT articles.*, users.nom AS auteur
    FROM articles
    JOIN users ON articles.auteur_id = users.id
    ORDER BY articles.date_publication DESC
');
$stmt->execute();
$articles = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Articles - TechBlog</title>
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
        <h1 class="section-title">Tous les articles</h1>
        <section class="articles-grid">

            <?php foreach ($articles as $article): ?>
                <article class="article-card">
                    <h2>
                        <a href="article.php?id=<?= $article['id'] ?>">
                            <?= htmlspecialchars($article['titre']) ?>
                        </a>
                    </h2>
                    <p class="article-meta">
                        <?= htmlspecialchars($article['auteur']) ?>
                        - <?= $article['date_publication'] ?>
                    </p>
                    <p><?= htmlspecialchars($article['extrait']) ?></p>
                </article>
            <?php endforeach; ?>

        </section>
    </main>
    <footer>
        <ul>
            <li><a href="about.html">À propos</a></li>
            <li><a href="contact.html">Contact</a></li>
        </ul>
        <p>&copy; 2026 TechBlog - Projet de formation DWWM</p>
    </footer>
    <script src="data/articles.js"></script>
    <script src="js/main.js"></script>
</body>
</html>