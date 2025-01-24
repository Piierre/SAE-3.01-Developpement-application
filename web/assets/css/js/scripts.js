// Initialisation des particules
particlesJS("particles-js", {
    "particles": {
        "number": {
            "value": 80,
            "density": { "enable": true, "value_area": 800 }
        },
        "color": { "value": "#ffffff" },
        "shape": { "type": "circle" },
        "opacity": { "value": 0.5 },
        "size": { "value": 3, "random": true },
        "line_linked": { "enable": true, "distance": 150, "color": "#ffffff", "opacity": 0.4, "width": 1 },
        "move": { "enable": true, "speed": 3 }
    },
    "interactivity": {
        "events": {
            "onhover": { "enable": true, "mode": "repulse" }
        }
    }
});

// Effet de texte en écriture
const textArray = ["Explorez nos données...", "Analysez les tendances météo...", "Préparez vos prévisions !"];
let textIndex = 0;
let charIndex = 0;
const typingElement = document.querySelector('.typing-effect');

function typeText() {
    if (charIndex < textArray[textIndex].length) {
        typingElement.innerHTML += textArray[textIndex].charAt(charIndex);
        charIndex++;
        setTimeout(typeText, 100);
    } else {
        setTimeout(() => {
            charIndex = 0;
            typingElement.innerHTML = "";
            textIndex = (textIndex + 1) % textArray.length;
            typeText();
        }, 2000);
    }
}
typeText();

// Animation des compteurs
function animateCounter(elementId, endValue, duration) {
    let startValue = 0;
    let increment = Math.ceil(endValue / duration);
    let counter = setInterval(() => {
        startValue += increment;
        if (startValue >= endValue) {
            startValue = endValue;
            clearInterval(counter);
        }
        document.getElementById(elementId).textContent = startValue;
    }, 50);
}

window.onload = function () {
    animateCounter("stations-count", 350, 100);
    animateCounter("reports-count", 12000, 100);
};

// Effet de scrolling fluide
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({ behavior: 'smooth' });
    });
});

// Effet d'entrée des sections au scroll
const sections = document.querySelectorAll('.section');
window.addEventListener('scroll', () => {
    sections.forEach(section => {
        const position = section.getBoundingClientRect().top;
        const windowHeight = window.innerHeight;
        if (position < windowHeight - 100) {
            section.style.opacity = '1';
            section.style.transform = 'translateY(0)';
        }
    });
});

// Animation du header au scroll
window.addEventListener('scroll', () => {
    const header = document.querySelector('header');
    header.style.background = window.scrollY > 50 ? "rgba(0, 0, 0, 0.9)" : "rgba(0, 0, 0, 0.8)";
});

// Fonction retour en haut
function scrollToTop() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

window.addEventListener('scroll', () => {
    const backToTop = document.getElementById('back-to-top');
    backToTop.style.display = window.scrollY > 200 ? 'block' : 'none';
});

// Function to toggle dark mode
function toggleDarkMode() {
    document.body.classList.toggle('dark-mode');
    localStorage.setItem('darkMode', document.body.classList.contains('dark-mode'));
}

// Set theme based on user preference
function setThemeByPreference() {
    const darkMode = localStorage.getItem('darkMode') === 'true';
    if (darkMode) {
        document.body.classList.add('dark-mode');
    } else {
        document.body.classList.remove('dark-mode');
    }
}

setThemeByPreference();
