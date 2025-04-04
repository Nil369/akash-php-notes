document.addEventListener('DOMContentLoaded', function() {
    // Dark/Light mode toggle
    const themeToggles = document.querySelectorAll('.theme-toggle');
    const body = document.body;
    
    // Check for saved theme preference or prefer-color-scheme
    const savedTheme = localStorage.getItem('theme') || 
                      (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
    
    // Function to set dark mode
    function setDarkMode() {
        body.classList.remove('light-mode');
        body.classList.add('dark-mode');
        document.documentElement.setAttribute('data-bs-theme', 'dark');
        document.querySelectorAll('#lightIcon').forEach(icon => icon.classList.add('d-none'));
        document.querySelectorAll('#darkIcon').forEach(icon => icon.classList.remove('d-none'));
        document.querySelectorAll('#themeText').forEach(text => text.textContent = 'Light');
        
        // Set all toggle switches to checked state
        themeToggles.forEach(toggle => {
            toggle.checked = true;
        });
    }
    
    // Function to set light mode
    function setLightMode() {
        body.classList.remove('dark-mode');
        body.classList.add('light-mode');
        document.documentElement.setAttribute('data-bs-theme', 'light');
        document.querySelectorAll('#lightIcon').forEach(icon => icon.classList.remove('d-none'));
        document.querySelectorAll('#darkIcon').forEach(icon => icon.classList.add('d-none'));
        document.querySelectorAll('#themeText').forEach(text => text.textContent = 'Dark');
        
        // Set all toggle switches to unchecked state
        themeToggles.forEach(toggle => {
            toggle.checked = false;
        });
    }
    
    // Apply saved theme
    if (savedTheme === 'dark') {
        setDarkMode();
    } else {
        setLightMode();
    }
    
    // Toggle theme when any theme toggle is clicked
    themeToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            if (body.classList.contains('light-mode')) {
                // Switch to dark mode
                setDarkMode();
                localStorage.setItem('theme', 'dark');
            } else {
                // Switch to light mode
                setLightMode();
                localStorage.setItem('theme', 'light');
            }
        });
    });
    
    // Code block copy functionality
    document.querySelectorAll('.copy-btn').forEach(button => {
        button.addEventListener('click', function() {
            const codeBlock = this.parentElement.nextElementSibling;
            const codeText = codeBlock.textContent;
            
            navigator.clipboard.writeText(codeText).then(() => {
                // Change button text temporarily
                const originalText = this.textContent;
                this.textContent = 'Copied!';
                setTimeout(() => {
                    this.textContent = originalText;
                }, 1500);
            }).catch(err => {
                console.error('Could not copy code: ', err);
            });
        });
    });
    
    // Initialize Prism.js for syntax highlighting (if loaded)
    if (typeof Prism !== 'undefined') {
        Prism.highlightAll();
    }
    
    // Responsive menu behavior (if needed)
    const navbarToggler = document.querySelector('.navbar-toggler');
    if (navbarToggler) {
        navbarToggler.addEventListener('click', function() {
            document.querySelector('.navbar-collapse').classList.toggle('show');
        });
    }
    
    // Comment form validation
    const commentForm = document.getElementById('commentForm');
    if (commentForm) {
        commentForm.addEventListener('submit', function(event) {
            const commentText = document.getElementById('commentText');
            if (!commentText.value.trim()) {
                event.preventDefault();
                alert('Please enter a comment before submitting.');
            }
        });
    }
    
    // Like/bookmark functionality
    document.querySelectorAll('.like-btn, .bookmark-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const postId = this.dataset.postId;
            const action = this.classList.contains('like-btn') ? 'like' : 'bookmark';
            
            // Send AJAX request (example)
            fetch(`/action.php?action=${action}&id=${postId}`, {
                method: 'POST',
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const countElement = this.querySelector('.count');
                    if (countElement) {
                        countElement.textContent = data.count;
                    }
                    this.classList.toggle('active');
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
});

// Contact form submission handler
function handleContactSubmit(event) {
    event.preventDefault();
    
    // Get form values
    const name = document.getElementById('contactName').value.trim();
    const email = document.getElementById('contactEmail').value.trim();
    const subject = document.getElementById('contactSubject').value.trim();
    const message = document.getElementById('contactMessage').value.trim();
    
    // Basic validation
    if (!name || !email || !subject || !message) {
        alert('Please fill in all fields');
        return;
    }
    
    // In a real application, you would send this data to the server
    // For now, just show success message
    document.getElementById('contactForm').reset();
    document.getElementById('contactSuccess').classList.remove('d-none');
    
    // Hide success message after 5 seconds
    setTimeout(() => {
        document.getElementById('contactSuccess').classList.add('d-none');
    }, 5000);
} 