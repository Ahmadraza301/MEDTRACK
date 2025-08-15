# MedTrack - Pharmacy Management System

A comprehensive PHP-based pharmacy management system that connects patients with nearby pharmacies for medicine availability and purchase.

## 🚀 Features

### Core Features
- **Medicine Search**: Find medicines by name with real-time availability
- **Location-Based Search**: Discover pharmacies near your location
- **Stock Management**: Real-time inventory tracking
- **Secure Payments**: Integrated Razorpay payment gateway
- **Multi-User System**: Admin, Shopkeeper, and Customer portals
- **Responsive Design**: Mobile-friendly interface

### User Roles
- **Admin**: System management and oversight
- **Shopkeeper**: Product and inventory management
- **Customer**: Medicine search and purchase

## 🔧 Requirements

- PHP 8.0 or higher
- MySQL 5.7 or higher / MariaDB 10.2 or higher
- XAMPP/WAMP/LAMP stack
- Modern web browser
- Razorpay account (for payments)

## 📦 Installation

### 1. Clone/Download Project
```bash
# Clone the repository
git clone https://github.com/yourusername/medtrack.git
cd medtrack

# Or download and extract ZIP file
```

### 2. Database Setup
1. Start your XAMPP/WAMP server
2. Open phpMyAdmin (http://localhost/phpmyadmin)
3. Create a new database named `medtrack_db`
4. Import the `medtrack_db.sql` file

### 3. Configuration
1. Copy `config/config.example.php` to `config/config.php`
2. Update database credentials in `config/config.php`:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USERNAME', 'root');
   define('DB_PASSWORD', '');
   define('DB_NAME', 'medtrack_db');
   ```

3. Update Razorpay credentials:
   ```php
   define('RAZORPAY_TEST_KEY', 'your_test_key');
   define('RAZORPAY_TEST_SECRET', 'your_test_secret');
   ```

### 4. File Permissions
Ensure the following directories are writable:
```bash
chmod 755 uploaded_img/
chmod 755 logs/
```

### 5. Access the Application
Open your browser and navigate to:
```
http://localhost/MedTrack
```

## 🔐 Default Login Credentials

### Admin
- **Username**: admin
- **Password**: admin123
- **URL**: http://localhost/MedTrack/admin/login.php

### Shopkeeper
- **Email**: john@medtrack.com
- **Password**: admin123
- **URL**: http://localhost/MedTrack/shopkeeper/login.php

## 🛡️ Security Features

### Implemented Security Measures
- **SQL Injection Prevention**: Prepared statements throughout
- **CSRF Protection**: Token-based request validation
- **Input Validation**: Comprehensive input sanitization
- **Password Security**: Strong password requirements and hashing
- **Session Security**: Secure session management
- **Rate Limiting**: Login attempt restrictions
- **Security Headers**: XSS, CSRF, and clickjacking protection

### Security Best Practices
- All user inputs are validated and sanitized
- Passwords are hashed using `password_hash()`
- Database queries use prepared statements
- Session IDs are regenerated after login
- CSRF tokens prevent cross-site request forgery
- Rate limiting prevents brute force attacks

## 📁 Project Structure

```
MedTrack/
├── admin/                 # Admin panel files
├── assets/               # CSS, JS, images, vendor files
├── components/           # Reusable components (header, footer, DB)
├── config/              # Configuration files
├── shopkeeper/          # Shopkeeper panel files
├── uploaded_img/        # Product images
├── logs/                # Application logs
├── index.php            # Main homepage
├── products.php         # Products listing
├── checkout.php         # Payment processing
├── submit_payment.php   # Payment API
├── medtrack_db.sql      # Database schema
└── README.md            # This file
```

## 🔄 Database Schema

### Key Tables
- **admin_accounts**: Administrator user accounts
- **shopkeeper_accounts**: Pharmacy shop accounts
- **products**: Medicine inventory
- **product_categories**: Medicine categories
- **orders**: Customer orders and payments
- **product_views**: Product view tracking

## 🚀 Usage

### For Customers
1. Browse medicines on the homepage
2. Search for specific medicines
3. View product details and shop information
4. Proceed to checkout and payment
5. Track order status

### For Shopkeepers
1. Login to shopkeeper panel
2. Add/edit products and categories
3. Manage inventory and stock levels
4. View and process orders
5. Update shop information

### For Administrators
1. Access admin panel
2. Manage shopkeeper accounts
3. Monitor system activity
4. View reports and analytics
5. System configuration

## 🛠️ Customization

### Adding New Features
1. Create new PHP files in appropriate directories
2. Update database schema if needed
3. Add routes and navigation links
4. Implement security measures
5. Test thoroughly

### Styling
- Main styles: `assets/css/styles.css`
- Admin styles: `assets/css/admin_style.css`
- Shopkeeper styles: `assets/css/shopkeeper_style.css`

## 🐛 Troubleshooting

### Common Issues

#### Database Connection Error
- Verify XAMPP/WAMP is running
- Check database credentials in `config/config.php`
- Ensure MySQL service is active

#### Payment Issues
- Verify Razorpay credentials
- Check internet connectivity
- Ensure proper SSL configuration

#### File Upload Errors
- Check directory permissions
- Verify file size limits
- Ensure valid file types

### Debug Mode
Enable debug mode in `config/config.php`:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

## 📈 Performance Optimization

### Database Optimization
- Indexes on frequently queried columns
- Optimized SQL queries with JOINs
- Connection pooling

### Frontend Optimization
- Minified CSS and JavaScript
- Optimized images
- Lazy loading for images
- CDN usage for external resources

## 🔮 Future Enhancements

### Planned Features
- **Mobile App**: Native iOS/Android applications
- **AI Integration**: Smart medicine recommendations
- **Inventory Alerts**: Low stock notifications
- **Analytics Dashboard**: Advanced reporting
- **Multi-language Support**: Internationalization
- **API Development**: RESTful API for third-party integration

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## 📄 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 🆘 Support

For support and questions:
- Create an issue on GitHub
- Contact: support@medtrack.com
- Documentation: [Wiki](https://github.com/yourusername/medtrack/wiki)

## 🙏 Acknowledgments

- Bootstrap for UI framework
- Razorpay for payment gateway
- Font Awesome for icons
- Glide.js for carousel functionality

---

**Note**: This is a development version. For production use, ensure all security measures are properly configured and tested.
