# 📚 ReadAndRise

**Read • Learn • Rise**

A free educational platform where students can access exam notes, share their own study materials, and read real struggle stories from fellow students. Built with PHP and MySQL.

---

## 🎯 Features

### For Students

- 📖 **Browse Free Notes** - Access notes for CDS, AFCAT, Computer Science, Programming, and more
- 📝 **Upload Your Notes** - Share your study materials with the community
- 📄 **PDF Support** - Upload and view PDF attachments with notes
- 📚 **Struggle Stories** - Read and share real student journey stories
- 🔍 **Category-based Browsing** - Find notes by exam/subject categories
- 🏷️ **Tag System** - Search notes by relevant tags

### For Admins

- ✅ **Content Moderation** - Review and approve pending notes and blogs
- 👥 **User Management** - Monitor registered users
- 📊 **Dashboard** - Track pending content count
- 🔒 **Admin-only Access** - Secure admin panel

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

### 4. Create Upload Directory

```bash
mkdir -p admin/uploads/notes
chmod 755 admin/uploads/notes
```

### 5. Start Apache & MySQL

- Open XAMPP Control Panel
- Start Apache and MySQL services

### 6. Access the Application

```
http://localhost/ReadAndRise/public/
```

---

## 📂 Project Structure

```
ReadAndRise/
├── admin/                  # Admin panel
│   ├── index.php          # Admin dashboard
│   ├── notes_pending.php  # Review pending notes
│   ├── blogs_pending.php  # Review pending blogs
│   └── uploads/           # Uploaded files
│       └── notes/         # PDF attachments
├── assets/                # Static assets
│   └── style.css         # Main stylesheet
├── includes/              # Shared PHP includes
│   ├── auth.php          # Authentication helpers
│   ├── db.php            # Database connection
│   ├── header.php        # Header component
│   └── footer.php        # Footer component
├── public/                # Public pages
│   ├── index.php         # Homepage
│   ├── login.php         # User login
│   ├── register.php      # User registration
│   ├── dashboard.php     # User dashboard
│   ├── notes.php         # Browse all notes
│   ├── note.php          # View single note
│   ├── add_note.php      # Upload new note
│   └── logout.php        # Logout handler
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

- ✅ Password hashing with `password_hash()`
- ✅ Prepared statements (PDO) to prevent SQL injection
- ✅ XSS protection with `htmlspecialchars()`
- ✅ File upload validation (PDF only)
- ✅ Session-based authentication
- ✅ Role-based access control
- ✅ CSRF protection ready

---

## 📝 Usage Guide

### For Students:

1. **Register** - Create a free account
2. **Browse Notes** - View notes by category/tags
3. **Upload Notes** - Share your study materials
4. **View PDFs** - Read embedded PDF attachments
5. **Track Status** - Check approval status in dashboard

### For Admins:

1. **Login** - Use admin credentials
2. **Review Content** - Check pending notes/blogs
3. **Approve/Reject** - Moderate submissions
4. **Monitor** - Track platform activity

---

## 🎨 Features in Detail

### Note Upload System

- Upload notes with title, category, tags, and content
- Optional PDF attachment (validated for security)
- Automatic slug generation from title
- Pending approval workflow
- Read-only PDF viewer with embedded iframe

### Admin Moderation

- Pending content count on dashboard
- Quick review interface
- One-click approve/reject actions
- User-friendly admin panel

### Content Display

- Clean, card-based UI
- Category and tag filtering
- Author attribution
- Timestamp display
- Responsive design

---

## 🚧 Upcoming Features

- [ ] **Struggle Stories** - Blog section for student journeys
- [ ] **Search Functionality** - Full-text search across notes
- [ ] **Comments System** - Allow discussions on notes
- [ ] **User Profiles** - Public user profile pages
- [ ] **Email Notifications** - Notify users on approval/rejection
- [ ] **Google AdSense Integration** - Monetization support
- [ ] **Social Sharing** - Share notes on social media
- [ ] **Bookmark System** - Save favorite notes

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

For issues and feature requests, please use the [GitHub Issues](https://github.com/NabinMahanty/ReadAndRise/issues) page.

---

**Made with ❤️ for the student community**

_Read • Learn • Rise_ 🚀
