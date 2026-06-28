# Banking System

A full-stack web banking application built with PHP, MySQL, and vanilla JavaScript.

## Features
- User registration and login with password hashing
- Session-based authentication
- Account dashboard showing balance, account number and recent transactions
- Deposit, withdraw and transfer funds between accounts
- Full transaction history
- AJAX-powered UI — no page reloads on transactions
- Responsive design for mobile and desktop

## Tech Stack
- **Frontend:** HTML, CSS, JavaScript (Fetch API)
- **Backend:** PHP
- **Database:** MySQL

## Setup
1. Clone the repo into your XAMPP `htdocs` folder
2. Import the database by running the following SQL in phpMyAdmin:
```sql
CREATE DATABASE users;

USE users;

CREATE TABLE login_info (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    user_name VARCHAR(100),
    password VARCHAR(255)
);

CREATE TABLE accounts (
    account_id INT AUTO_INCREMENT PRIMARY KEY,
    user_name VARCHAR(100),
    full_name VARCHAR(100),
    account_number VARCHAR(20) UNIQUE,
    balance DECIMAL(10,2) DEFAULT 0.00
);

CREATE TABLE transactions (
    transaction_id INT AUTO_INCREMENT PRIMARY KEY,
    account_number VARCHAR(20),
    type VARCHAR(20),
    amount DECIMAL(10,2),
    date DATETIME DEFAULT CURRENT_TIMESTAMP,
    description VARCHAR(255)
);
```
3. Update the database credentials in each PHP file if needed
4. Open `login.html` in your browser to get started
