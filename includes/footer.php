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
          <li><a href="/readandrise/public/index.php">Home</a></li>
          <li><a href="/readandrise/public/notes.php">Study Materials</a></li>
          <li><a href="/readandrise/public/blogs.php">Success Stories</a></li>
          <li><a href="/readandrise/public/register.php">Join Community</a></li>
        </ul>
      </div>

      <div class="footer-section">
        <h4>Resources</h4>
        <ul class="footer-links">
          <li><a href="/readandrise/public/add_note.php">Upload Notes</a></li>
          <li><a href="/readandrise/public/add_blog.php">Share Your Story</a></li>
          <li><a href="/readandrise/public/dashboard.php">Dashboard</a></li>
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
    // Mobile menu toggle
    document.querySelector('.mobile-menu-toggle')?.addEventListener('click', function() {
      document.querySelector('.main-nav')?.classList.toggle('active');
      this.classList.toggle('active');
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function(e) {
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

    // Add loading animation
    window.addEventListener('load', function() {
      document.body.classList.add('loaded');
    });
  </script>
  </body>

  </html>