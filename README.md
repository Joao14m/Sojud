# Sojud - Soccer Players Reviews

## Tech Stack
![PHP](https://img.shields.io/badge/PHP-777BB4?logo=php&logoColor=white)
![Apache](https://img.shields.io/badge/Apache-D22128?logo=apache&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?logo=mysql&logoColor=white)

A role-based CRUD web app built with a **LAMP-style stack** (Apache + PHP + MySQL).  
Admins manage soccer players and Fans can review players **one time per player**.

## Overview
Users **sign up** with:
- Username
- Password
- Role: **Admin** or **Fan**

Role behavior:
- **Admin**: redirected to `dashboardAdmin.php` and can **Create, Read, Update, Delete** players
- **Fan**: redirected to `dashboardFan.php` and can **review players** (**only once per player**)

Note: If you create a second Admin, that Admin will only see **their own players**.
