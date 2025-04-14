PROJECT OVERVIEW

SwiftTrans Logistics is an intercity goods transportation management system built with PHP, MySQL, HTML, CSS (Tailwind), and JavaScript.
It offers a modern, user-friendly interface with smooth animations and a fully responsive design.
Users can sign up, log in, and book transport services like bikes, tempos, and trucks for different load capacities.
Real-time fare calculation is managed dynamically through the fares table in the database.
The booking system captures essential details like vehicle type, pickup and drop locations, distance, weight, and fare.
Users can view booking history, track delivery status ("pending", "out for delivery", "delivered"), and manage their profiles.
Admins can log in to manage bookings, users, and vehicles, and update fare details through a centralized dashboard.
Backend operations like authentication, bookings, and fare management are handled efficiently with PHP and MySQL.
PDF generation for booking details and invoices is done using the Composer tool and relevant PHP libraries.

### 🔧 Key Features

#### 👤 User Panel:
- Register/Login with session management
- Book transport service by selecting cities and vehicle type
- **Auto fare calculation** based on selected city pair and vehicle
- View booking history and delivery status (Pending, Out for Delivery, Delivered)

#### 🔐 Admin Panel:
- Secure Admin login
- Dashboard to manage all bookings and users
- Update delivery status and fare rates
- Manage vehicle services (Bike, Tempo, Truck)

### 🛠 Tech Stack

- **Frontend:** HTML, CSS (Tailwind CSS), JavaScript  
- **Backend:** PHP (with embedded dynamic logic)  
- **Database:** MySQL (via phpMyAdmin)  
- **Server:** XAMPP (Apache + MySQL)

1️⃣ System Requirements
XAMPP (for Apache & MySQL): Download XAMPP
Composer (for PHP libraries): Download Composer
Code Editor (VS Code, Sublime, etc.)

2️⃣ Set Up Environment
Install XAMPP and Composer on your system.
Start Apache and MySQL from the XAMPP Control Panel.

for database config-- http://localhost/phpmyadmin/
import the file from bye/database/swifttrans.sql


1. ADMIN CREDENTIALS
Email- admin@example.com
Paas - admin123


2. If Port is not available
netstat -ano | findstr :3306
taskkill /PID  /F


3. for pdf Generations command 
composer --version
cd C:\xampp\htdocs\bye
composer require tecnickcom/tcpdf
Download and install Composer:
Go to- https://getcomposer.org/download/
Download the Composer-Setup.exe (for Windows).
Run the installer and follow the steps. Make sure to select your PHP executable when asked (usually something like C:\xampp\php\php.exe).

Add Composer to the system PATH (if needed):
If it still doesn’t work after installation:
Go to Control Panel > System > Advanced system settings > Environment Variables.
Find the "Path" variable under System variables, click Edit, then New.
Add the location where Composer was installed (usually C:\ProgramData\ComposerSetup\bin or C:\xampp\composer).
Click OK on all windows.

4. for running the project -- http://localhost/bye/

Developed by: Pratham Kumbhare
📧 prathamkumbhare4@gmail.com
🔗 https://www.linkedin.com/in/prathamkumbhare/

⭐ Star the repo if you like it!

Let me know if you'd like a banner or matching project logo as well!











