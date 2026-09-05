<?php
session_start();
require 'includes/pdo.php';

// Les 3 derniers articles pour l'accueil
$stmt = $pdo->prepare('
    SELECT articles.*, users.nom AS auteur
    FROM articles
    JOIN users ON articles.auteur_id = users.id
    ORDER BY articles.date_publication DESC
    LIMIT 3
');
$stmt->execute();
$derniers = $stmt->fetchAll();
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechBlog</title>
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

        <?php if (isset($_SESSION['user_id'])): ?>
            <span class="nav-user">Bonjour, <?= htmlspecialchars($_SESSION['user_nom']) ?></span>
            <a href="logout.php">Déconnexion</a>
        <?php else: ?>
            <a href="login.php">Connexion</a>
        <?php endif; ?>
 
    </header>

    <main>
        <section class="hero">
            <h1>TechBlog, apprendre le web simplement</h1>
            <p>Actualités, tutoriels et conseils de développement web.</p>
            <a href="articles.php" class="btn">Voir les articles</a>
        </section>

        <section class="articles">
            <h2 class="section-title">Derniers articles</h2>
            <div class="articles-grid">
                <?php foreach ($derniers as $article): ?>
                    <article class="article-card">
                        <h2><a href="article.php?id=<?= $article['id'] ?>">
                            <?= htmlspecialchars($article['titre']) ?></a></h2>
                        <p class="article-meta"><?= htmlspecialchars($article['auteur']) ?> - <?= $article['date_publication'] ?></p>
                        <p><?= htmlspecialchars($article['extrait']) ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
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