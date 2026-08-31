// ================================================
// MAIN.JS — comportements du site
// ================================================

// ----- 1. DARK MODE -----

const themeToggle = document.getElementById('theme-toggle');

function toggleTheme() {
    const html = document.documentElement;
    // documentElement = la balise <html> elle-même

    if (html.getAttribute('data-theme') === 'dark') {
        html.setAttribute('data-theme', 'light');
        saveTheme('light');
    } else {
        html.setAttribute('data-theme', 'dark');
        saveTheme('dark');
    }
}

themeToggle.addEventListener('click', toggleTheme);

function saveTheme(theme) {
    localStorage.setItem('theme', theme);
}

const savedTheme = localStorage.getItem('theme');
if (savedTheme) {
    document.documentElement.setAttribute('data-theme', savedTheme);
}

// ----- 2. CARTES ARTICLES -----

function creerCarte(article) {
    const carte = document.createElement('article');   // crée <article> en mémoire
    carte.className = 'article-card';                  // lui donne la classe CSS

    carte.innerHTML = `
        <h2><a href="article.html?id=${article.id}">${article.titre}</a></h2>
        <p class="article-meta">${article.auteur} - ${article.date} - ${article.duree} min</p>
        <p>${article.extrait}</p>
    `;
    return carte;
}

// ----- 3. AFFICHAGE DES ARTICLES -----

function afficherArticles() {
    const grille = document.getElementById('articles-grid');
    // si la page n'a pas de grille (ex: contact.html), on sort
    if (!grille) return;

    for (const article of ARTICLES) {
        grille.appendChild(creerCarte(article));
    }
}

afficherArticles();

// ----- 4 MENU BURGER -----

const menuToggle = document.getElementById('menu-toggle');

if (menuToggle) {
    menuToggle.addEventListener('click', function () {
        const nav = document.querySelector('nav');
        nav.classList.toggle('open');

        // met à jour l'icône et l'aria-label
        const ouvert = nav.classList.contains('open');
        menuToggle.textContent = ouvert ? '✕' : '☰';
        menuToggle.setAttribute('aria-label', ouvert ? 'Fermer le menu' : 'Ouvrir le menu');
    });
}