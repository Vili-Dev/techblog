<?php
require 'protection.php';

$erreurs = [];
$article = ['id' => null, 'titre' => '', 'extrait' => '', 'contenu' => '', 'categorie' => ''];

// MODE MODIFICATION ? (form.php?id=5)
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $pdo->prepare('
        SELECT * FROM articles
        WHERE id = ? AND auteur_id = ?
    ');
    $stmt->execute([$id, $_SESSION['user_id']]);
    $article = $stmt->fetch();

    if (!$article) {
        die('Article introuvable (ou pas à vous).');
    }
}

// SOUMISSION
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $article['titre'] = trim($_POST['titre'] ?? '');
    $article['extrait'] = trim($_POST['extrait'] ?? '');
    $article['contenu'] = trim($_POST['contenu'] ?? '');
    $article['categorie'] = trim($_POST['categorie'] ?? '');

    if ($article['titre'] === '') $erreurs[] = 'Le titre est obligatoire.';
    if ($article['contenu'] === '') $erreurs[] = 'Le contenu est obligatoire.';

    if (empty($erreurs)) {
        if ($article['id']) {
            // MODIFICATION
            $stmt = $pdo->prepare('
                UPDATE articles
                SET titre = ?, extrait = ?, contenu = ?, categorie = ?
                WHERE id = ? AND auteur_id = ?
            ');
            $stmt->execute([
                $article['titre'], $article['extrait'],
                $article['contenu'], $article['categorie'],
                $article['id'], $_SESSION['user_id']
            ]);
        } else {
            // CRÉATION
            $stmt = $pdo->prepare('
                INSERT INTO articles (titre, extrait, contenu, categorie, auteur_id)
                VALUES (?, ?, ?, ?, ?)
            ');
            $stmt->execute([
                $article['titre'], $article['extrait'],
                $article['contenu'], $article['categorie'],
                $_SESSION['user_id']
            ]);
        }

        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $article['id'] ? 'Modifier' : 'Nouvel' ?> article - TechBlog</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <a href="index.php" class="logo">← Retour admin</a>
    </header>
    <main>
        <div class="page-content">
            <h1 class="section-title"><?= $article['id'] ? 'Modifier' : 'Écrire' ?> un article</h1>

            <?php foreach ($erreurs as $e): ?>
                <p style="color:red"><?= htmlspecialchars($e) ?></p>
            <?php endforeach; ?>

            <form method="post">
                <div>
                    <label for="titre">Titre</label>
                    <input type="text" id="titre" name="titre"
                           value="<?= htmlspecialchars($article['titre']) ?>" required>
                </div>
                <div>
                    <label for="categorie">Catégorie</label>
                    <input type="text" id="categorie" name="categorie"
                           value="<?= htmlspecialchars($article['categorie']) ?>">
                </div>
                <div>
                    <label for="extrait">Extrait</label>
                    <input type="text" id="extrait" name="extrait"
                           value="<?= htmlspecialchars($article['extrait']) ?>">
                </div>
                <div>
                    <label for="contenu">Contenu</label>
                    <textarea id="contenu" name="contenu" rows="10" required><?= htmlspecialchars($article['contenu']) ?></textarea>
                </div>
                <button type="submit">Enregistrer</button>
            </form>
        </div>
    </main>
</body>
</html>