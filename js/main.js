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