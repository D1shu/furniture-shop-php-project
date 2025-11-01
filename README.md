# PrimeFurniture - PHP Mini Project

## Overview
**PrimeFurniture** is a simple yet functional **Furniture Shop Management System** built using **Core PHP**, **MySQL**, **HTML5**, **CSS3**, and **JavaScript**.  
It provides an easy-to-use admin panel for managing furniture items, categories, and customer data.

This project is ideal for **college mini projects**, **PHP beginners**, and **demo e-commerce systems**.

---

## Tech Stack
- **Frontend:** HTML5, CSS3, JavaScript  
- **Backend:** Core PHP (Procedural / mysqli)  
- **Database:** MySQL (phpMyAdmin / XAMPP)  
- **Server:** Apache (via XAMPP or WAMP)

---

## Project Structure
```
PrimeFurniture/
│
├── db.php                  # Database connection file
├── index.php               # Homepage
├── admin/
│   ├── dashboard.php       # Admin Dashboard
│   ├── add_furniture.php   # Add new furniture item
│   ├── manage_furniture.php# View/Edit/Delete furniture
│   ├── categories.php      # Manage categories
│   ├── login.php           # Admin login page
│   ├── logout.php          # Logout handler
│   ├── includes/
│   │   ├── header.php      # Common admin header
│   │   ├── footer.php      # Common admin footer
│   │   └── sidebar.php     # Sidebar navigation
│
├── assets/
│   ├── css/                # Stylesheets
│   ├── js/                 # JavaScript files
│   ├── images/             # Product & UI images
│
├── sql/
│   └── primefurniture.sql  # Database export file
│
└── README.md               # Project documentation
```

---

## Features
✅ Admin Login / Logout  
✅ Add / Edit / Delete Furniture Products  
✅ Category Management  
✅ Product Image Upload  
✅ Simple & Clean UI  
✅ Responsive Design  
✅ Basic Form Validation  
✅ MySQL Database Integration  

---

## Setup Instructions

### 1. Clone or Download
Download or clone this repository:
```bash
git clone https://github.com/D1shu/furniture-shop-php-project/
```

### 2. Setup Database
1. Open **phpMyAdmin**
2. Create a new database named `primefurniture`
3. Import the `primefurniture.sql` file from the `sql` folder

### 3. Configure Database
Open `db.php` and update your credentials if needed:
```php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "primefurniture";
$conn = mysqli_connect($host, $user, $pass, $dbname);
```

### 4. Run the Project
Start **XAMPP** or **WAMP** and open the browser:
```
http://localhost/PrimeFurniture/
```

---

## Default Admin Credentials
```
Username: admin
Password: admin123
```
*(You can change these in the database.)*

---

## Future Enhancements
- User-side shopping system
- Cart & Checkout functionality
- Payment Gateway integration
- Product search & filters
- Email notifications

---

## Author
**Project:** PrimeFurniture  
**Developed by:** Dishant Patel
**Language:** PHP & MySQL  
**Version:** 1.0.0  

---

⭐ *If you like this project, don’t forget to give it a star!*  
