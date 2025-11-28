# 🎯 ReadAndRise

**Excellence Through Knowledge**

A premium, free educational platform where aspirants can access comprehensive exam notes, share study materials, and read inspiring success stories from fellow students. Built with PHP, MySQL, and modern web technologies—100% free, 100% community-driven.

---

## ✨ Key Features

### For Aspirants

- 📚 **Comprehensive Study Materials** - Access curated notes for CDS, AFCAT, NDA, Computer Science, Programming, and more
- 📝 **Community Contributions** - Upload and share your study materials with thousands of fellow aspirants
- 📄 **PDF Support** - Attach PDF documents to your notes for comprehensive learning resources
- ✨ **Success Stories** - Read and share real preparation journeys, struggles, and triumphs
- 🔍 **Advanced Filtering** - Find notes by exam categories, subjects, and tags
- 🏷️ **Smart Tagging** - Discover relevant materials through intelligent tag-based search
- 📱 **Mobile Optimized** - Study on-the-go with fully responsive design
- 🎨 **Modern UI/UX** - Beautiful, intuitive interface with smooth animations

### For Administrators

- ✅ **Content Moderation** - Review and approve pending notes and success stories
- 👥 **User Management** - Monitor and manage registered community members
- 📊 **Analytics Dashboard** - Track platform activity and pending content
- 🔒 **Secure Admin Panel** - Protected command center with role-based access
- ⚡ **Quick Actions** - One-click approve/reject for efficient moderation

---

## 🚀 What's New in v2.0

### Design & UX

- ✅ **Complete UI Redesign** - Energetic, vibrant color palette with electric blue, fire orange, and cyan accents
- ✅ **Interactive Elements** - Smooth animations, gradient buttons, hover effects with glowing shadows
- ✅ **Modern Typography** - Inter font family with multiple weights for professional appearance
- ✅ **Mobile First** - Completely responsive across all devices with optimized touch targets
- ✅ **Professional English** - Transformed from Hinglish to crisp, officer-grade professional content

### SEO & Performance

- ✅ **SEO Optimized** - Comprehensive meta tags, Open Graph, Twitter Cards, JSON-LD structured data
- ✅ **Sitemap & Robots.txt** - XML sitemap for search engines, crawl optimization
- ✅ **Performance Enhanced** - GZIP compression, browser caching, optimized assets via .htaccess
- ✅ **Core Web Vitals** - Optimized for LCP, FID, and CLS metrics

### Security & Accessibility

- ✅ **Security Hardened** - Enhanced XSS protection, security headers, file access protection
- ✅ **Accessibility Improved** - WCAG compliant with keyboard navigation and semantic HTML
- ✅ **HTTPS Ready** - Prepared for SSL certificate installation

---

## 🛠️ Tech Stack

- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Server**: Apache (XAMPP/WAMP)
- **Frontend**: HTML5, CSS3, Vanilla JavaScript
- **Session Management**: PHP Sessions
- **File Uploads**: PDF support with validation

---

## 📋 Prerequisites

- XAMPP/WAMP/LAMP installed
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache server

---

## 🚀 Installation

### 1. Clone the Repository

```bash
cd C:\xampp\htdocs
git clone https://github.com/NabinMahanty/ReadAndRise.git
cd ReadAndRise
```

### 2. Database Setup

```sql
-- Create database
CREATE DATABASE readandrise;
USE readandrise;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Notes table
CREATE TABLE notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    category VARCHAR(100) NOT NULL,
    tags TEXT,
    content TEXT NOT NULL,
    attachment_path VARCHAR(255) NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Blogs table (for struggle stories)
CREATE TABLE blogs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    content TEXT NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create admin user (password: admin123)
INSERT INTO users (name, email, password, role)
VALUES ('Admin', 'admin@readandrise.in', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
```

### 3. Configure Database Connection

Edit `includes/db.php`:

```php
$host = "localhost";
$dbname = "readandrise";
$user = "root";
$pass = "";  // Your MySQL password
```

### 4. Create Upload Directories

```bash
# Windows (PowerShell)
New-Item -ItemType Directory -Path "uploads\notes" -Force

# Linux/Mac
mkdir -p uploads/notes
chmod 755 uploads/notes
```

### 5. Configure .htaccess (Optional but Recommended)

The project includes `.htaccess` for:

- GZIP compression
- Browser caching
- Security headers
- File access protection

Ensure `mod_rewrite` is enabled in Apache configuration.

### 5. Start Apache & MySQL

**XAMPP (Windows):**

- Open XAMPP Control Panel
- Start Apache and MySQL services

**Linux/Mac:**

```bash
sudo systemctl start apache2
sudo systemctl start mysql
```

### 6. Access the Application

```
http://localhost/ReadAndRise/public/
```

**Admin Panel:**

```
http://localhost/ReadAndRise/admin/
```

---

## 📂 Project Structure

```
ReadAndRise/
├── admin/                  # Admin panel
│   ├── index.php          # Admin dashboard
│   ├── notes_pending.php  # Review pending notes
│   └── blogs_pending.php  # Review pending success stories
├── assets/                # Static assets
│   └── style.css         # Main stylesheet (1176 lines, energetic theme)
├── includes/              # Shared PHP includes
│   ├── auth.php          # Authentication helpers
│   ├── db.php            # Database connection (PDO)
│   ├── header.php        # Header with SEO meta tags
│   └── footer.php        # Footer component
├── public/                # Public pages
│   ├── index.php         # Homepage with statistics
│   ├── login.php         # User login
│   ├── register.php      # User registration
│   ├── dashboard.php     # User dashboard
│   ├── notes.php         # Browse study materials
│   ├── note.php          # View single note
│   ├── add_note.php      # Upload new note
│   ├── blogs.php         # Success stories listing
│   ├── blog.php          # View single success story
│   ├── add_blog.php      # Share success story
│   └── logout.php        # Logout handler
├── uploads/               # User uploads
│   └── notes/            # PDF attachments
├── .htaccess             # Apache configuration
├── .gitignore            # Git exclusions
├── robots.txt            # Search engine crawl rules
├── sitemap.xml           # XML sitemap for SEO
└── README.md             # This file
```

---

## 👤 User Roles

### Regular User

- Register and login
- Upload notes with PDF attachments
- View approved notes
- Manage own submissions

### Admin

- All user permissions
- Approve/reject notes
- Approve/reject blogs
- Access admin panel at `/admin/`

**Default Admin Login:**

- Email: `admin@readandrise.in`
- Password: `admin123`

---

## 🔒 Security Features

- ✅ **Password Hashing** - bcrypt algorithm via `password_hash()` and `password_verify()`
- ✅ **SQL Injection Prevention** - Prepared statements with PDO parameter binding
- ✅ **XSS Protection** - Output sanitization with `htmlspecialchars()`
- ✅ **File Upload Validation** - MIME type checking, file extension whitelisting (PDF only)
- ✅ **Session Security** - Secure session handling with regeneration on login
- ✅ **Role-Based Access Control** - Admin/user permission separation
- ✅ **Security Headers** - X-Frame-Options, X-Content-Type-Options, X-XSS-Protection via .htaccess
- ✅ **Directory Protection** - Disabled directory browsing, protected upload folders
- ✅ **CSRF Ready** - Prepared for token-based protection implementation

---

## 📝 Usage Guide

### For Students:

1. **Register** - Create a free account with name, email, and password
2. **Browse Materials** - Explore study notes by category (CDS, AFCAT, NDA, Computer Science, etc.)
3. **Filter & Search** - Use category dropdown and search to find specific content
4. **Upload Notes** - Share your study materials with PDF attachments
5. **Share Stories** - Write about your preparation journey and struggles
6. **Track Status** - Monitor approval status in your personal dashboard
7. **Read Success Stories** - Get inspired by others' journeys

### For Admins:

1. **Login** - Use admin credentials to access admin panel
2. **Review Content** - Check pending notes and success stories
3. **Approve/Reject** - Moderate submissions with one-click actions
4. **Monitor Platform** - Track platform activity and statistics
5. **Manage Quality** - Ensure high-quality, relevant content for community

---

## 🎨 Features in Detail

### Study Materials System

- **Upload Notes** - Title, category, tags, rich content editor, and optional PDF attachment
- **PDF Validation** - Secure file upload with MIME type and extension checking
- **Automatic Slug Generation** - SEO-friendly URLs from titles
- **Moderation Workflow** - Pending → Approved/Rejected status flow
- **Category Organization** - Structured by exam type (CDS, AFCAT, NDA, Computer Science, Programming)
- **Tag-Based Discovery** - Multiple tags per note for enhanced searchability
- **Rich Content Display** - Card-based UI with author, timestamp, and metadata

### Success Stories Platform

- **Share Journeys** - Students can write about their preparation experiences
- **Inspiration Hub** - Read real stories of struggle, perseverance, and success
- **Moderation System** - Admin approval ensures quality content
- **Professional Layout** - Clean, readable format with golden gradient theme
- **Search & Filter** - Find relevant stories easily

### Admin Moderation Panel

- **Dashboard Overview** - Real-time counts of pending content
- **Quick Review Interface** - Streamlined approve/reject workflow
- **One-Click Actions** - Efficient content moderation
- **Secure Access** - Role-based authentication and authorization
- **User Management** - Monitor registered community members

### UI/UX Excellence

- **Energetic Color Palette** - Electric blue (#0066ff), fire orange (#ff4500), cyan (#00d4ff)
- **Gradient Buttons** - Multi-color gradients with hover effects and glowing shadows
- **Smooth Animations** - CSS transitions for professional feel
- **Responsive Design** - Mobile-first approach with breakpoints at 768px and 480px
- **Interactive Elements** - Hover states, focus indicators, loading animations
- **Professional Typography** - Inter font family (weights 300-800) for clarity

---

## 🚧 Upcoming Features

- [ ] **Advanced Search** - Full-text search across notes with highlighting
- [ ] **Comments System** - Discussion threads on notes and stories
- [ ] **User Profiles** - Public profile pages with contribution history
- [ ] **Email Notifications** - Notify users on approval/rejection status
- [ ] **Bookmark System** - Save and organize favorite notes
- [ ] **Social Sharing** - Share notes on WhatsApp, Telegram, Facebook, Twitter
- [ ] **Dark Mode** - Toggle between light and dark themes
- [ ] **Rating System** - Upvote/downvote notes for quality ranking
- [ ] **Related Content** - AI-suggested similar notes and stories
- [ ] **Export Options** - Download notes as PDF or print-friendly format
- [ ] **Analytics Dashboard** - View statistics and user engagement metrics
- [ ] **Mobile App** - Native Android/iOS applications
- [ ] **Offline Support** - PWA with offline reading capabilities

---

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

---

## 📜 License

This project is open source and available under the [MIT License](LICENSE).

---

## 👨‍💻 Developer

**Nabin Mahanty**

- GitHub: [@NabinMahanty](https://github.com/NabinMahanty)
- Project: [ReadAndRise](https://github.com/NabinMahanty/ReadAndRise)

---

## 🙏 Acknowledgments

- Built for students, by students
- Community-driven free education platform
- No paid course pressure - just pure learning

---

## 📞 Support

### Getting Help

- **Issues**: Report bugs via [GitHub Issues](https://github.com/NabinMahanty/ReadAndRise/issues)
- **Feature Requests**: Suggest improvements through GitHub Issues
- **Documentation**: Refer to inline code comments and this README

### Troubleshooting

**Database Connection Error:**

- Verify MySQL is running
- Check credentials in `includes/db.php`
- Ensure database `readandrise` exists

**Upload Not Working:**

- Check `uploads/notes/` directory exists and has write permissions
- Verify PHP `upload_max_filesize` and `post_max_size` in php.ini
- Only PDF files are accepted

**Admin Access Issues:**

- Default credentials: `admin@readandrise.in` / `admin123`
- Change password after first login for security
- Check user role is set to 'admin' in database

**Styling Issues:**

- Clear browser cache
- Check `assets/style.css` is loading (1176 lines)
- Verify no .htaccess conflicts

---

**Made with ❤️ for the student community**

_Read • Learn • Rise_ 🚀
