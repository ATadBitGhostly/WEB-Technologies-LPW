// Didn't use getElementbyClassName cause well it would select all elements with navbar, and I would have to write [0] at the end.
// Used querySelector cause it can find any CSS Selector and always returns the first element.
const navbar = document.querySelector('.navbar')

window.addEventListener('scroll', function() {
    if (this.window.scrollY > 50) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
})

const date = new Date();
document.getElementById('dynamicDate').innerHTML = date.getFullYear();


const footer = document.querySelector('footer');

footer.addEventListener('mouseenter', function() {
    footer.classList.add('hoverEffect');
    // console.log('YOU SHALL ENTER!');
})

footer.addEventListener('mouseleave', function() {
    footer.classList.remove('hoverEffect');
    // console.log('YOU SHALL LEAVE!');
})


document.addEventListener("DOMContentLoaded", () => {
    const btn = document.getElementById('themeToggler');
    const darkIcon = document.getElementById('dark-mode-icon');

    // Apply saved theme
    const savedTheme = localStorage.getItem('theme');

    if (savedTheme === 'dark') {
        document.body.classList.add('dark-mode');
        darkIcon.classList.replace('bi-moon-stars', 'bi-sun');
    } else {
        document.body.classList.remove('dark-mode');
        darkIcon.classList.replace('bi-sun', 'bi-moon-stars');
    }

    btn.addEventListener('click', () => {
        const isDark = document.body.classList.toggle('dark-mode');

        if (isDark) {
            localStorage.setItem('theme', 'dark');
            darkIcon.classList.replace('bi-moon-stars', 'bi-sun');
        } else {
            localStorage.setItem('theme', 'light');
            darkIcon.classList.replace('bi-sun', 'bi-moon-stars');
        }

        console.log("Theme now:", localStorage.getItem('theme')); // debug
    });
});
