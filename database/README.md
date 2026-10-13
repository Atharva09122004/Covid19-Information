# 🗄️ Database Schema & Migration Guide

This directory contains the database definitions, constraints, table schemas, and initial seed datasets for the **COVID-19 Information and Awareness Website**.

---

## 📋 Database Information

- **Database Name**: `covid19_db`
- **Database Engine**: MySQL 5.7+ / MariaDB 10.4+ (InnoDB)
- **Character Set**: `utf8mb4`
- **Collation**: `utf8mb4_unicode_ci`

---

## 📂 Files

| File | Description |
| :--- | :--- |
| [`database.sql`](database.sql) | Complete SQL script containing table creation statements (`CREATE TABLE`), foreign key definitions, unique indexes, and initial educational and statistical seed datasets. |

---

## 📊 Database Tables Overview

| Table Name | Primary Key | Foreign Keys | Purpose |
| :--- | :--- | :--- | :--- |
| **`users`** | `id` (AUTO_INCREMENT) | None | Stores user profiles, unique emails/usernames, BCRYPT hashed passwords, mobile numbers, and access control roles (`user`/`admin`). |
| **`covid_info`** | `id` (AUTO_INCREMENT) | None | Stores educational articles categorized into Overview, Symptoms, Prevention, and Safety. |
| **`statistics`** | `id` (AUTO_INCREMENT) | None | Stores confirmed cases, recovered cases, and death metrics for tracked countries. |
| **`comments`** | `id` (AUTO_INCREMENT) | `user_id` -> `users(id)` (`ON DELETE CASCADE`) | Stores community feedback posts linked to registered authors. |
| **`contact_messages`** | `id` (AUTO_INCREMENT) | None | Stores visitor inquiries submitted through the Contact Us form. |

---

## 🚀 Setup & Import Instructions

### Option 1: Via phpMyAdmin (GUI)
1. Start **Apache** and **MySQL** in the XAMPP Control Panel.
2. Open your web browser and navigate to: [http://localhost/phpmyadmin/](http://localhost/phpmyadmin/)
3. Click the **Import** tab in the top navigation bar.
4. Click **Choose File** and select `database/database.sql` from this directory.
5. Click **Go** at the bottom of the page to execute the script.

### Option 2: Via MySQL Command Line Interface (CLI)
```bash
# Connect to MySQL and import database.sql
mysql -u root -p < database/database.sql
```
