// Smooth scrolling pour les liens d'ancrage
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Header scroll effect
const header = document.getElementById('header');
if (header) {
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });
}

// Auto-hide messages après 5 secondes
const messages = document.querySelectorAll('.message');
messages.forEach(msg => {
    setTimeout(() => {
        msg.style.display = 'none';
    }, 5000);
});

// Validation du formulaire de contact
const contactForm = document.querySelector('.contact-form form');
if (contactForm) {
    contactForm.addEventListener('submit', function(e) {
        const email = document.getElementById('email').value;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (!emailRegex.test(email)) {
            e.preventDefault();
            alert('Veuillez entrer une adresse email valide.');
            return false;
        }
        
        const rgpd = document.querySelector('input[name="rgpd"]');
        if (rgpd && !rgpd.checked) {
            e.preventDefault();
            alert('Veuillez accepter la politique de confidentialité.');
            return false;
        }
    });
}

// Animation des compteurs de stats
function animateCounter(element, target) {
    let current = 0;
    const increment = target / 100;
    const timer = setInterval(() => {
        current += increment;
        if (current >= target) {
            element.textContent = target;
            clearInterval(timer);
        } else {
            element.textContent = Math.floor(current);
        }
    }, 20);
}

// Activer l'animation des stats quand la section est visible
const statsSection = document.querySelector('.stats-section');
if (statsSection) {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                document.querySelectorAll('.stat-number').forEach(stat => {
                    const target = parseInt(stat.textContent);
                    if (!stat.classList.contains('animated')) {
                        animateCounter(stat, target);
                        stat.classList.add('animated');
                    }
                });
            }
        });
    });
    
    observer.observe(statsSection);
}

// Responsive menu toggle (pour mobile)
const menuToggle = document.createElement('div');
menuToggle.className = 'menu-toggle';
menuToggle.innerHTML = '☰';
menuToggle.style.display = 'none';

if (window.innerWidth <= 768) {
    const nav = document.querySelector('nav');
    if (nav) {
        const headerContent = document.querySelector('.header-content');
        headerContent.insertBefore(menuToggle, nav);
        menuToggle.style.display = 'block';
        menuToggle.style.cursor = 'pointer';
        menuToggle.style.fontSize = '1.5rem';
        
        menuToggle.addEventListener('click', () => {
            nav.classList.toggle('active');
        });
    }
}

console.log('TechSolutions - Site chargé avec succès');
