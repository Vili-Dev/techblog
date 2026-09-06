<?php
require 'protection.php';

// Ses articles à lui (WHERE auteur_id = session)
$stmt = $pdo->prepare('
    SELECT id, titre, categorie, date_publication
    FROM articles
    WHERE auteur_id = ?
    ORDER BY date_publication DESC
');
$stmt->execute([$_SESSION['user_id']]);
$mesArticles = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - TechBlog</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <a href="../index.php" class="logo">TechBlog Admin</a>
        <nav>
            <ul>
                <li><a href="../index.php">Voir le site</a></li>
                <li><a href="../logout.php">Déconnexion</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <div class="page-content">
            <h1 class="section-title">Mes articles</h1>
            <p><a href="form.php" class="btn">+ Nouvel article</a></p>

            <?php if (empty($mesArticles)): ?>
                <p>Aucun article pour le moment.</p>
            <?php else: ?>
                <table border="1" cellpadding="8">
                    <tr>
                        <th>Titre</th><th>Catégorie</th><th>Date</th><th>Actions</th>
                    </tr>
                    <?php foreach ($mesArticles as $a): ?>
                        <tr>
                            <td><?= htmlspecialchars($a['titre']) ?></td>
                            <td><?= htmlspecialchars($a['categorie']) ?></td>
                            <td><?= $a['date_publication'] ?></td>
                            <td>
                                <a href="form.php?id=<?= $a['id'] ?>">Modifier</a>
                                <a href="supprimer.php?id=<?= $a['id'] ?>"
                                   onclick="return confirm('Supprimer cet article ?');">
                                    Supprimer
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>