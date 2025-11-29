  </main>

  <footer class="main-footer">
    <div class="footer-content">
      <div class="footer-section">
        <h3>ReadAndRise</h3>
        <p class="footer-tagline">Empowering aspirants through knowledge sharing and community support.</p>
        <div class="social-links">
          <a href="https://github.com/NabinMahanty/ReadAndRise" target="_blank" rel="noopener noreferrer" aria-label="GitHub">
            <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z" />
            </svg>
          </a>
        </div>
      </div>

      <div class="footer-section">
        <h4>Quick Links</h4>
        <ul class="footer-links">
          <li><a href="/ReadAndRise/public/index.php">Home</a></li>
          <li><a href="/ReadAndRise/public/notes.php">Study Materials</a></li>
          <li><a href="/ReadAndRise/public/blogs.php">Success Stories</a></li>
          <li><a href="/ReadAndRise/public/register.php">Join Community</a></li>
        </ul>
      </div>

      <div class="footer-section">
        <h4>Resources</h4>
        <ul class="footer-links">
          <li><a href="/ReadAndRise/public/add_note.php">Upload Notes</a></li>
          <li><a href="/ReadAndRise/public/add_blog.php">Share Your Story</a></li>
          <li><a href="/ReadAndRise/public/dashboard.php">Dashboard</a></li>
        </ul>
      </div>

      <div class="footer-section">
        <h4>Our Mission</h4>
        <p class="footer-text">Providing free, high-quality educational resources to students preparing for competitive examinations. No paid courses, just pure community-driven learning.</p>
      </div>
    </div>

    <div class="footer-bottom">
      <p>© <?php echo date('Y'); ?> ReadAndRise. All rights reserved. | <span class="motto">Read. Learn. Rise.</span></p>
      <p class="footer-note">Built with dedication for the student community 🎯</p>
    </div>
  </footer>

  <script>
    // hide loader when page fully loaded
    window.addEventListener('load', function() {
      const loader = document.getElementById('page-loader');
      if (loader) {
        // Add hidden class to fade out loader
        loader.classList.add('hidden');
        // Show body content
        document.body.classList.add('loaded');
        // Remove loader from DOM after transition
        setTimeout(() => loader.remove(), 600);
      }
    });

    // Mobile menu toggle - Enhanced for responsive design
    const menuToggle = document.querySelector('.mobile-menu-toggle');
    const mainNav = document.querySelector('.main-nav');
    const headerActions = document.querySelector('.header-actions');

    if (menuToggle && mainNav) {
      menuToggle.addEventListener('click', function(e) {
        e.preventDefault();
        mainNav.classList.toggle('mobile-active');
        headerActions?.classList.toggle('mobile-active');
        this.classList.toggle('active');

        // Animate hamburger icon
        const spans = this.querySelectorAll('span');
        if (this.classList.contains('active')) {
          spans[0].style.transform = 'rotate(45deg) translateY(8px)';
          spans[1].style.opacity = '0';
          spans[2].style.transform = 'rotate(-45deg) translateY(-8px)';
        } else {
          spans[0].style.transform = 'none';
          spans[1].style.opacity = '1';
          spans[2].style.transform = 'none';
        }
      }, {
        passive: false
      });

      // Close mobile menu when clicking outside
      document.addEventListener('click', function(e) {
        if (!e.target.closest('.main-nav') &&
          !e.target.closest('.mobile-menu-toggle') &&
          mainNav.classList.contains('mobile-active')) {
          mainNav.classList.remove('mobile-active');
          headerActions?.classList.remove('mobile-active');
          menuToggle.classList.remove('active');

          const spans = menuToggle.querySelectorAll('span');
          spans[0].style.transform = 'none';
          spans[1].style.opacity = '1';
          spans[2].style.transform = 'none';
        }
      });
    }

    // Mobile dropdown toggle - optimized
    document.querySelectorAll('.dropdown-trigger').forEach(trigger => {
      trigger.addEventListener('click', function(e) {
        if (window.innerWidth <= 768) {
          e.preventDefault();
          const dropdown = this.closest('.nav-dropdown');
          const menu = dropdown.querySelector('.dropdown-menu');

          // Toggle active state
          dropdown.classList.toggle('active');

          // Animate dropdown arrow
          const arrow = this.querySelector('.dropdown-arrow');
          if (arrow) {
            arrow.style.transform = dropdown.classList.contains('active') ?
              'rotate(180deg)' :
              'rotate(0deg)';
          }
        }
      }, {
        passive: false
      });
    });

    // Close mobile menu on window resize
    let resizeTimer;
    window.addEventListener('resize', function() {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(function() {
        if (window.innerWidth > 768) {
          mainNav?.classList.remove('mobile-active');
          headerActions?.classList.remove('mobile-active');
          menuToggle?.classList.remove('active');

          if (menuToggle) {
            const spans = menuToggle.querySelectorAll('span');
            spans[0].style.transform = 'none';
            spans[1].style.opacity = '1';
            spans[2].style.transform = 'none';
          }
        }
      }, 250);
    });

    // Smooth scroll for anchor links - optimized
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function(e) {
        const targetId = this.getAttribute('href');
        if (targetId === '#') return;

        e.preventDefault();
        const target = document.querySelector(targetId);
        if (target) {
          target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });

          // Close mobile menu after navigation
          if (window.innerWidth <= 768) {
            mainNav?.classList.remove('mobile-active');
            headerActions?.classList.remove('mobile-active');
            menuToggle?.classList.remove('active');
          }
        }
      }, {
        passive: false
      });
    });

    // Lazy load images that aren't critical
    if ('loading' in HTMLImageElement.prototype) {
      const images = document.querySelectorAll('img[loading="lazy"]');
      images.forEach(img => {
        if (img.dataset.src) {
          img.src = img.dataset.src;
        }
      });
    } else {
      // Fallback for browsers that don't support lazy loading
      const script = document.createElement('script');
      script.src = 'https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js';
      script.async = true;
      document.body.appendChild(script);
    }
  </script>
  </body>

  </html>