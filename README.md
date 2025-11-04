# 🏨 Catanho's Hotel

This project was developed as part of the **Dynamic Web Solutions** course.  
It’s a complete hotel management web application that includes both **client** and **administrative** interfaces, offering a modern, responsive, and efficient system for hotel operations.

---

## 💡 About the Project

**Catanho’s Hotel** is a web-based management system designed to simplify the process of booking, managing, and organizing hotel operations.  
It was built to provide a real-world simulation of how hotels handle customer interactions, reservations, and internal administration.

The project focuses on combining **dynamic web technologies** with **database-driven design**, ensuring a seamless flow between user actions and backend processing.

---

## ⚙️ Main Features

### 👤 Client Side
- User registration and login system  
- Room browsing and filtering  
- Online booking system  
- Reservation history and management  
- AJAX-powered interactions for a smoother experience  

### 🧑‍💼 Admin Side
- Secure admin login  
- Dashboard with real-time statistics  
- Room and booking management  
- Customer management  
- API integration for data access  
- Dynamic content updates (using AJAX)  

---

## 🧱 Tech Stack

- **Frontend:** HTML, CSS, JavaScript, AJAX  
- **Backend:** PHP  
- **Database:** MySQL  
- **API:** RESTful PHP-based endpoints  

---

## ⚙️ How to Set Up the Database Connection

To run this project and connect it to the database, follow these steps carefully:

---

### 🧰 1. Configure XAMPP
- Make sure **XAMPP** is installed and working properly.  
- Open the **XAMPP Control Panel** and start **Apache** and **MySQL**.

---

### 🗄️ 2. Create the Database
1. Open your browser and go to:  
   👉 [http://localhost/phpmyadmin](http://localhost/phpmyadmin)  
2. Create a new database named **`mydb`** *(or use another name and update it in your PHP code).*  
3. Import the SQL file (if provided) to set up the tables.

---

### 🧪 3. Test the Connection
Create a simple PHP file (for example, `test_connection.php`) to check if your database connection works:

```php
<?php
include 'includes/config.php';
echo "✅ Database connection successful!";
?>
```

---
### 🌐 4. Run in the Browser

1. Place your project folder inside htdocs:
```
C:\xampp\htdocs\Projeto_DWS\
```
2. Open your browser and go to:
```
http://localhost/Projeto_DWS/
```
3. Navigate to the desired PHP file (e.g., index.php).
