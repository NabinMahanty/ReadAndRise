# 🎯 ReadAndRise

**Never Forget Your Lakshya**

A premium, free educational platform where aspirants can access comprehensive exam notes, share study materials, and read inspiring success stories from fellow students. Built with PHP, MySQL, and modern web technologies—100% free, 100% community-driven.

---

## ✨ Key Features

### For Aspirants

- 📚 **Comprehensive Study Materials** - Access curated notes for CDS, AFCAT, NDA, Computer Science, Programming, and more
- 📝 **Community Contributions** - Upload and share your study materials with thousands of fellow aspirants
- 📄 **PDF Support** - Attach PDF documents to your notes for comprehensive learning resources
- 📰 **Current Affairs** - Stay updated with latest news and developments relevant to competitive exams
- 📝 **Question Papers** - Access previous year question papers with Google Drive folder links
- ✨ **Success Stories** - Read and share real preparation journeys, struggles, and triumphs
- 🔍 **Advanced Filtering** - Find notes by exam categories, subjects, tags, year, and more
- 🏷️ **Smart Tagging** - Discover relevant materials through intelligent tag-based search
- 📱 **Fully Responsive** - Perfect experience on mobile, tablet, and desktop devices
- 🎨 **Modern Dark Theme** - Beautiful dark UI with vibrant gradients and smooth animations

### For Administrators

- ✅ **Content Moderation** - Review and approve pending notes, stories, current affairs, and question papers
- 👥 **User Management** - Monitor and manage registered community members
- 📊 **Analytics Dashboard** - Track platform activity and pending content across all sections
- 🔒 **Secure Admin Panel** - Protected command center with role-based access
- ⚡ **Quick Actions** - One-click approve/reject for efficient moderation
- 📈 **Statistics Overview** - Real-time metrics for all content types

---

## 🚀 What's New in v2.0

### Design & UX Overhaul

- ✅ **Complete Dark Theme Redesign** - Modern dark (#0f172a, #1e293b) with blue accents (#60a5fa)
- ✅ **Fully Responsive** - Mobile-first design with breakpoints for all devices (mobile, tablet, desktop)
- ✅ **Interactive Mobile Menu** - Hamburger navigation with smooth animations
- ✅ **Gradient Cards** - Beautiful card designs with subtle gradients and hover effects
- ✅ **Consistent UI** - Unified design system across all pages (public, dashboard, admin)
- ✅ **Touch-Friendly** - Optimized button sizes and spacing for mobile devices
- ✅ **Modern Typography** - Inter font family with professional hierarchy

### New Features

- ✅ **Current Affairs Section** - Complete repository for exam-related news and updates
- ✅ **Question Papers Module** - Share Google Drive folders with previous year papers
- ✅ **Enhanced Search & Filters** - Multi-column filters with year, subject, and category options
- ✅ **Status Badges** - Color-coded badges (green/approved, yellow/pending, red/rejected)
- ✅ **Empty States** - Helpful messages and suggestions when no content is found
- ✅ **Results Count** - Real-time display of search results

### SEO & Performance

- ✅ **SEO Optimized** - Comprehensive meta tags, Open Graph, Twitter Cards, JSON-LD structured data
- ✅ **Dynamic Page Titles** - Context-aware titles and descriptions for better search rankings
- ✅ **Sitemap & Robots.txt** - XML sitemap for search engines, crawl optimization
- ✅ **Performance Enhanced** - GZIP compression, browser caching, optimized assets
- ✅ **Loading Animations** - Lottie-based loader with smooth transitions

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

-- Blogs table (Success Stories)
CREATE TABLE blogs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    category VARCHAR(100) NOT NULL,
    content TEXT NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Current Affairs table
CREATE TABLE current_affairs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    summary TEXT,
    content TEXT NOT NULL,
    image_path VARCHAR(255) NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Questions table (Previous Year Papers)
CREATE TABLE questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    year INT NOT NULL,
    subject VARCHAR(100) NOT NULL,
    qtype VARCHAR(50) NOT NULL,
    description TEXT,
    drive_folder_link VARCHAR(500) NOT NULL,
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
New-Item -ItemType Directory -Path "uploads\current" -Force

# Linux/Mac
mkdir -p uploads/notes uploads/current
chmod 755 uploads/notes uploads/current
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
├── admin/                     # Admin panel
│   ├── index.php             # Admin dashboard with statistics
│   ├── notes_pending.php     # Review pending study materials
│   ├── blogs_pending.php     # Review pending success stories
│   ├── current_pending.php   # Review pending current affairs
│   ├── questions_pending.php # Review pending question papers
│   └── add_current.php       # Admin: Add current affairs
├── assets/                    # Static assets
│   ├── style.css             # Main stylesheet (2000+ lines, dark theme)
│   └── logo.png              # Platform logo
├── includes/                  # Shared PHP includes
│   ├── auth.php              # Authentication helpers
│   ├── db.php                # Database connection (PDO)
│   ├── header.php            # Header with SEO, navigation, loader
│   └── footer.php            # Footer with mobile menu JS
├── public/                    # Public pages
│   ├── index.php             # Homepage with hero section
│   ├── login.php             # User login
│   ├── register.php          # User registration
│   ├── dashboard.php         # User dashboard with all contributions
│   ├── notes.php             # Browse study materials
│   ├── note.php              # View single note
│   ├── add_note.php          # Upload new note
│   ├── edit_note.php         # Edit your note
│   ├── delete_note.php       # Delete your note
│   ├── current_affairs.php   # Browse current affairs
│   ├── current.php           # View single current affair
│   ├── add_current.php       # Add current affair
│   ├── questions.php         # Browse question papers
│   ├── add_question.php      # Submit question paper folder
│   ├── blogs.php             # Success stories listing
│   ├── blog.php              # View single success story
│   ├── add_blog.php          # Share success story
│   ├── edit_blog.php         # Edit your story
│   ├── delete_blog.php       # Delete your story
│   └── logout.php            # Logout handler
├── uploads/                   # User uploads
│   ├── notes/                # PDF attachments for notes
│   └── current/              # Images for current affairs
├── .htaccess                 # Apache configuration
├── .gitignore                # Git exclusions
├── robots.txt                # Search engine crawl rules
├── sitemap.xml               # XML sitemap for SEO
└── README.md                 # This file
```

---

## 👤 User Roles

### Regular User

- Register and login
- Upload notes with PDF attachments
- Share current affairs articles with images
- Submit question paper Google Drive folders
- Write and share success stories
- View approved content
- Manage own submissions via dashboard
- Track approval status (pending/approved/rejected)

### Admin

- All user permissions
- Approve/reject notes
- Approve/reject blogs
- Approve/reject current affairs
- Approve/reject question papers
- Access admin panel at `/admin/`
- View comprehensive statistics
- Monitor all pending submissions

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
3. **Filter & Search** - Use category dropdown, year filter, subject search to find specific content
4. **Upload Notes** - Share your study materials with PDF attachments (max 10MB)
5. **Add Current Affairs** - Post exam-related news with optional images
6. **Submit Question Papers** - Share Google Drive folder links with previous year papers
7. **Share Stories** - Write about your preparation journey and struggles
8. **Track Status** - Monitor approval status in your personal dashboard (color-coded badges)
9. **Edit/Delete** - Manage your contributions from the dashboard

### For Admins:

1. **Login** - Use admin credentials to access admin panel
2. **Review Content** - Check pending notes, stories, current affairs, and question papers
3. **Approve/Reject** - Moderate submissions with one-click actions
4. **Preview Before Approval** - View full content before making decision
5. **Monitor Platform** - Track platform activity and statistics in dashboard
6. **Manage Quality** - Ensure high-quality, relevant content for community

---

## 🎨 Features in Detail

### Study Materials System

- **Upload Notes** - Title, category, tags, rich content editor, and optional PDF attachment (max 10MB)
- **PDF Validation** - Secure file upload with MIME type and extension checking
- **Automatic Slug Generation** - SEO-friendly URLs from titles with uniqueness check
- **Moderation Workflow** - Pending → Approved/Rejected status flow with color-coded badges
- **Category Organization** - Structured by exam type (CDS, AFCAT, NDA, Computer Science, Programming)
- **Tag-Based Discovery** - Multiple tags per note for enhanced searchability
- **Rich Content Display** - Card-based dark UI with gradient backgrounds, author info, timestamps

### Current Affairs Module

- **Submit Articles** - Title, summary, full content, and optional image upload
- **Image Support** - Visual content for better engagement (stored in uploads/current/)
- **Search Functionality** - Find relevant articles by keywords across title, summary, and content
- **Results Count** - Real-time display of matching articles
- **Preview Links** - Admins can preview before approving
- **Card Layout** - Beautiful cards with image thumbnails and article metadata

### Question Papers Repository

- **Google Drive Integration** - Share folder links containing question papers
- **Year & Subject Filters** - Three-column filter (search, year dropdown, subject input)
- **Question Type** - Categorize as CDS, AFCAT, NDA, etc.
- **Description Field** - Add context about the papers
- **Direct Access** - "Open Folder" buttons linking to Google Drive
- **Badge Display** - Color-coded badges for year, subject, and type

### Success Stories Platform

- **Share Journeys** - Students can write about their preparation experiences
- **Category System** - Organize stories by type (Preparation Journey, Strategy Tips, Success Stories)
- **Inspiration Hub** - Read real stories of struggle, perseverance, and success
- **Moderation System** - Admin approval ensures quality content
- **Search & Filter** - Find relevant stories by keywords and category
- **Professional Layout** - Clean, readable format with dark theme

### User Dashboard

- **Contribution Overview** - Statistics cards showing counts for each content type
- **Welcome Section** - Personalized greeting with quick action buttons
- **All Submissions** - Separate sections for notes, blogs, current affairs, and question papers
- **Status Tracking** - Color-coded badges (green=approved, yellow=pending, red=rejected)
- **Quick Actions** - Edit and delete buttons for all your content
- **Empty States** - Helpful messages encouraging first contribution

### Admin Moderation Panel

- **Dashboard Overview** - Real-time counts of pending items across all categories
- **Statistics Cards** - Pending, approved, and rejected counts with color-coded themes
- **Quick Review Interface** - Streamlined approve/reject workflow for all content types
- **Preview Functionality** - View full content before making moderation decision
- **One-Click Actions** - Efficient content moderation with immediate feedback
- **Secure Access** - Role-based authentication and authorization
- **Recent Activity** - Track latest submissions

### UI/UX Excellence

- **Dark Theme Design** - Modern dark color palette (#0f172a, #1e293b, #334155)
- **Blue Accent Colors** - Primary blue (#60a5fa) for CTAs and highlights
- **Gradient Backgrounds** - Subtle gradients (135deg) on cards and buttons
- **Status Color System** - Green (approved), Yellow (pending), Red (rejected)
- **Smooth Animations** - CSS transitions for professional feel (0.3s ease)
- **Responsive Breakpoints** - Mobile (≤768px), Tablet (769-1024px), Desktop (>1024px)
- **Touch-Friendly** - Optimized button sizes and spacing for mobile devices
- **Interactive Elements** - Hover states, focus indicators, active states
- **Professional Typography** - Inter font family (weights 400-700) for clarity and hierarchy
- **Mobile Menu** - Hamburger navigation with smooth slide-in animation
- **Empty States** - Helpful messages with suggestions when no content found
- **Loading Animation** - Lottie-based loader with smooth fade transition

---

## 📱 Mobile Testing Guide

### Local Network Testing

To test on your mobile device:

1. **Find Your Computer's IP Address:**

   ```powershell
   ipconfig | Select-String -Pattern "IPv4"
   ```

2. **Configure Windows Firewall:**

   - Open Windows Firewall settings
   - Allow Apache HTTP Server (port 80)
   - Or manually allow `C:\xampp\apache\bin\httpd.exe`

3. **Ensure Same WiFi Network:**

   - Connect both computer and mobile to same WiFi

4. **Access on Mobile:**
   ```
   http://YOUR_IP_ADDRESS/ReadAndRise/public/index.php
   ```

### Browser DevTools Testing (Instant)

1. Open site in Chrome/Edge
2. Press **F12** to open DevTools
3. Click device toggle icon (Ctrl+Shift+M)
4. Select device (iPhone, Samsung Galaxy, iPad, etc.)
5. Test in different orientations

### Using ngrok (Remote Testing)

1. Download ngrok from https://ngrok.com
2. Run: `ngrok http 80`
3. Access via public URL provided

---

## 🚧 Upcoming Features

- [ ] **Advanced Search** - Full-text search across all content types with highlighting
- [ ] **Comments System** - Discussion threads on notes, stories, and current affairs
- [ ] **User Profiles** - Public profile pages with contribution history and statistics
- [ ] **Email Notifications** - Notify users on approval/rejection status via email
- [ ] **Bookmark System** - Save and organize favorite notes and articles
- [ ] **Social Sharing** - Share content on WhatsApp, Telegram, Facebook, Twitter
- [ ] **Light/Dark Mode Toggle** - User preference for theme selection
- [ ] **Rating System** - Upvote/downvote content for quality ranking
- [ ] **Related Content** - AI-suggested similar notes and articles
- [ ] **Export Options** - Download notes as PDF or print-friendly format
- [ ] **Analytics Dashboard** - Advanced metrics and user engagement statistics
- [ ] **Mobile App** - Native Android/iOS applications
- [ ] **PWA Support** - Progressive Web App with offline reading capabilities
- [ ] **Real-time Notifications** - Live updates for content approval
- [ ] **Multi-language Support** - Hindi and regional languages
- [ ] **Study Planner** - Personal study schedule and goal tracking

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

- Check `uploads/notes/` and `uploads/current/` directories exist with write permissions
- Verify PHP `upload_max_filesize` and `post_max_size` in php.ini (recommended: 10M+)
- Only PDF files accepted for notes, images (JPG/PNG) for current affairs
- Check file size limits (10MB for PDFs, 5MB for images)

**Admin Access Issues:**

- Default credentials: `admin@readandrise.in` / `admin123`
- **Important:** Change password after first login for security
- Check user role is set to 'admin' in database users table
- Clear browser cookies/cache if session issues occur

**Styling Issues:**

- Clear browser cache (Ctrl+F5)
- Check `assets/style.css` is loading properly (2000+ lines)
- Verify no .htaccess conflicts
- Ensure all CSS media queries are loaded

**Responsive Design Not Working:**

- Check viewport meta tag in header.php
- Clear browser cache
- Test in browser DevTools mobile view
- Verify JavaScript in footer.php is loading

**Mobile Access Issues:**

- Ensure computer and mobile on same WiFi
- Check Windows Firewall allows Apache (port 80)
- Verify XAMPP Apache is running
- Use correct IP address format: `http://IP_ADDRESS/ReadAndRise/...`

---

**Made with ❤️ for the student community**

_Read • Learn • Rise_ 🚀
