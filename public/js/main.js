// Global smooth scrolling handler
document.addEventListener('click', function(e) {
    // Handle FAQ links from other pages
    if (e.target.matches('a[href*="#faq"]') || (e.target.closest('a') && e.target.closest('a').href && e.target.closest('a').href.includes('#faq'))) {
        // If not on landing page, navigate to landing page first
        if (!window.location.pathname.endsWith('/')) {
            window.location.href = '/' + window.location.hash;
            return false;
        }
    }
    
    // Handle anchor links
    if (e.target.matches('a[href^="#"]') && e.target.getAttribute('href') !== '#') {
        const targetId = e.target.getAttribute('href');
        const targetElement = document.querySelector(targetId);
        
        if (targetElement) {
            e.preventDefault();
            
            // Calculate the scroll position with offset for fixed header
            // Header height (h-16 = 4rem = 64px) + top padding (top-4 = 1rem = 16px) + extra spacing (20px)
            const headerOffset = 100; // 64px + 16px + 20px = 100px
            const elementPosition = targetElement.getBoundingClientRect().top;
            const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
            
            window.scrollTo({
                top: offsetPosition,
                behavior: 'smooth'
            });
            
            // Update URL without page jump
            history.pushState(null, null, targetId);
            return false;
        }
    }
});

// Handle initial hash in URL on page load
document.addEventListener('DOMContentLoaded', function() {
    if (window.location.hash) {
        const targetElement = document.querySelector(window.location.hash);
        if (targetElement) {
            setTimeout(() => {
                const headerOffset = 120;
                const elementPosition = targetElement.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
                
                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            }, 100);
        }
    }
});

document.addEventListener('DOMContentLoaded', function() {

    // Initialize Splide carousel if it exists
    if (document.querySelector('.splide')) {
        try {
            new Splide('.splide', {
                type: 'loop',
                perPage: 1,
                autoplay: true,
                interval: 5000,
                pauseOnHover: true,
                pagination: true,
                arrows: true,
            }).mount();
            console.log('Splide carousel initialized');
        } catch (e) {
            console.error('Error initializing Splide:', e);
        }
    }

    // FAQ accordion functionality - Updated for new structure
    const faqGroups = document.querySelectorAll('#faq .group');
    if (faqGroups.length > 0) {
        faqGroups.forEach(group => {
            const button = group.querySelector('button');
            const content = group.querySelector('div[class*="max-h-0"]');
            const arrow = group.querySelector('button svg');
            
            if (button && content) {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Close other open FAQs
                    faqGroups.forEach(otherGroup => {
                        if (otherGroup !== group) {
                            const otherContent = otherGroup.querySelector('div[class*="max-h-0"]');
                            const otherArrow = otherGroup.querySelector('button svg');
                            if (otherContent && otherArrow) {
                                otherContent.classList.remove('max-h-96', 'group-focus:mt-3');
                                otherContent.classList.add('max-h-0');
                                otherArrow.classList.remove('rotate-180');
                            }
                        }
                    });
                    
                    // Toggle current FAQ
                    if (content.classList.contains('max-h-0')) {
                        content.classList.remove('max-h-0');
                        content.classList.add('max-h-96', 'group-focus:mt-3');
                        if (arrow) {
                            arrow.classList.add('rotate-180');
                        }
                    } else {
                        content.classList.remove('max-h-96', 'group-focus:mt-3');
                        content.classList.add('max-h-0');
                        if (arrow) {
                            arrow.classList.remove('rotate-180');
                        }
                    }
                });
            }
        });
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(event) {
        // Close user dropdown when clicking outside
        const userMenuButton = document.getElementById('user-menu-button');
        const userMenuDropdown = document.getElementById('user-menu-dropdown');

        if (userMenuButton && userMenuDropdown &&
            !userMenuButton.contains(event.target) &&
            !userMenuDropdown.contains(event.target)) {
            userMenuDropdown.classList.add('hidden');
        }

        // Close notification dropdown when clicking outside
        const notificationButton = document.getElementById('notification-button');
        const notificationDropdown = document.getElementById('notification-dropdown');

        if (notificationButton && notificationDropdown &&
            !notificationButton.contains(event.target) &&
            !notificationDropdown.contains(event.target)) {
            notificationDropdown.classList.add('hidden');
        }
    });

});



// User menu dropdown toggle
const userMenuButton = document.getElementById('user-menu-button');
const userMenuDropdown = document.getElementById('user-menu-dropdown');

if (userMenuButton && userMenuDropdown) {
userMenuButton.addEventListener('click', function() {
    userMenuDropdown.classList.toggle('hidden');
    const arrow = this.querySelector('svg');
    if (arrow) {
        arrow.classList.toggle('rotate-180');
    }
});

// Close the dropdown when clicking outside
document.addEventListener('click', function(event) {
    if (!userMenuButton.contains(event.target) && !userMenuDropdown.contains(event.target)) {
        userMenuDropdown.classList.add('hidden');
        const arrow = userMenuButton.querySelector('svg');
        if (arrow) {
            arrow.classList.remove('rotate-180');
        }
    }
});
}

function confirmLogout() {
// Show loading state
const logoutButton = document.querySelector('#logout-modal button:last-child');
const logoutForm = document.getElementById('logout-form');

if (logoutButton) {
    const originalText = logoutButton.innerHTML;
    logoutButton.innerHTML = `
        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Memproses...
    `;
    logoutButton.disabled = true;
}

// Submit the logout form
if (logoutForm) {
    logoutForm.submit();
}
}

// Back to Top Button functionality
document.addEventListener('DOMContentLoaded', function() {
    const backToTopButton = document.getElementById('backToTop');
    
    if (backToTopButton) {
        // Ensure button is always on top
        backToTopButton.style.zIndex = '9999';
        backToTopButton.style.position = 'fixed';
        backToTopButton.style.bottom = '1.5rem';
        backToTopButton.style.right = '1.5rem';
        
        // Show/hide button based on scroll position
        function toggleBackToTop() {
            if (window.pageYOffset > 300) { // Show after 300px of scrolling
                backToTopButton.classList.remove('opacity-0', 'invisible');
                backToTopButton.classList.add('opacity-100');
            } else {
                backToTopButton.classList.remove('opacity-100');
                backToTopButton.classList.add('opacity-0', 'invisible');
            }
        }
        
        // Initial check
        toggleBackToTop();
        
        // Listen to scroll events
        window.addEventListener('scroll', toggleBackToTop, { passive: true });

        // Smooth scroll to top when clicked
        backToTopButton.addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
});

document.addEventListener('DOMContentLoaded', function() {
    // Particles.js initialization
    if (typeof particlesJS !== 'undefined') {
        particlesJS("particles-js", {
            "particles": {
                "number": {
                    "value": 80,
                    "density": {
                        "enable": true,
                        "value_area": 800
                    }
                },
                "color": {
                    "value": "#ffffff"
                },
                "shape": {
                    "type": "circle",
                    "stroke": {
                        "width": 0,
                        "color": "#000000"
                    },
                },
                "opacity": {
                    "value": 0.5,
                    "random": false,
                    "anim": {
                        "enable": false,
                        "speed": 1,
                        "opacity_min": 0.1,
                        "sync": false
                    }
                },
                "size": {
                    "value": 3,
                    "random": true,
                    "anim": {
                        "enable": false,
                        "speed": 40,
                        "size_min": 0.1,
                        "sync": false
                    }
                },
                "line_linked": {
                    "enable": true,
                    "distance": 150,
                    "color": "#ffffff",
                    "opacity": 0.4,
                    "width": 1
                },
                "move": {
                    "enable": true,
                    "speed": 2,
                    "direction": "none",
                    "random": false,
                    "straight": false,
                    "out_mode": "out",
                    "bounce": false,
                }
            },
            "interactivity": {
                "detect_on": "canvas",
                "events": {
                    "onhover": {
                        "enable": true,
                        "mode": "grab"
                    },
                    "onclick": {
                        "enable": true,
                        "mode": "push"
                    },
                    "resize": true
                },
                "modes": {
                    "grab": {
                        "distance": 140,
                        "line_linked": {
                            "opacity": 1
                        }
                    },
                    "push": {
                        "particles_nb": 4
                    }
                }
            },
            "retina_detect": true
        });
    }

    // Typing effect
    var TxtType = function(el, toRotate, period) {
        this.toRotate = toRotate;
        this.el = el;
        this.loopNum = 0;
        this.period = parseInt(period, 10) || 2000;
        this.txt = '';
        this.tick();
        this.isDeleting = false;
    };

    TxtType.prototype.tick = function() {
        var i = this.loopNum % this.toRotate.length;
        var fullTxt = this.toRotate[i];

        if (this.isDeleting) {
            this.txt = fullTxt.substring(0, this.txt.length - 1);
        } else {
            this.txt = fullTxt.substring(0, this.txt.length + 1);
        }

        this.el.innerHTML = this.txt;

        var that = this;
        var delta = 200 - Math.random() * 100;

        if (this.isDeleting) {
            delta /= 2;
        }

        if (!this.isDeleting && this.txt === fullTxt) {
            delta = this.period;
            this.isDeleting = true;
        } else if (this.isDeleting && this.txt === '') {
            this.isDeleting = false;
            this.loopNum++;
            delta = 500;
        }

        setTimeout(function() {
            that.tick();
        }, delta);
    };

    // Initialize typing effect
    var elements = document.getElementsByClassName('typewrite');
    for (var i = 0; i < elements.length; i++) {
        var toRotate = elements[i].getAttribute('data-type');
        var period = elements[i].getAttribute('data-period');
        if (toRotate) {
            new TxtType(elements[i], JSON.parse(toRotate), period);
        }
    }

    // Counter animation
    const counterElements = document.querySelectorAll('.counter-value');
    const animateCounter = (el) => {
        const target = parseInt(el.getAttribute('data-count'));
        const duration = 2000; // 2 seconds
        const step = target / (duration / 16); // 60fps
        let current = 0;

        const updateCounter = () => {
            current += step;
            if (current < target) {
                el.textContent = Math.floor(current).toLocaleString();
                requestAnimationFrame(updateCounter);
            } else {
                el.textContent = target.toLocaleString();
            }
        };

        updateCounter();
    };

    // Intersection Observer for counters
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.5
    });

    counterElements.forEach(counter => {
        observer.observe(counter);
    });
});
