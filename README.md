# PHP Web Template

This project is a simple PHP web template that demonstrates how to create a user authentication system using ```PDO``` with ```MySQL```. It includes registration, login, password hashing (using ```bcrypt```), and session management. It also uses the ```XAMPP``` local development environment to run the project.

## Features

- ```User Registration```: Allows users to create an account with a full name, email, and password.
- ```User Login```: Users can log in with their email and password.
- ```Password Hashing```: Passwords are securely stored in the database using ```bcrypt``` hashing.
- ```Session Management```: Once logged in, the user session is maintained until they log out.
- ```Remember Me```: Users can opt to be remembered across sessions (using cookies).
- ```MySQL Database Integration```: The project integrates with MySQL to store user information securely.

## Prerequisites

Before setting up the project, ensure you have the following software installed:

- ```XAMPP```: A local server environment that includes Apache, MySQL, and PHP.
  - Download XAMPP: https://www.apachefriends.org/download.html
  - Make sure Apache and MySQL are running in XAMPP.

---

## Project Setup

### Step 1: Clone the Repository

1. Clone or download the repository to your local machine.

   ```bash
   git clone https://github.com/AlphaX50/php-web-template.git
   ```
### Step 2: Place the Project in **htdocs**

1. Open your XAMPP installation folder (typically located in ```C:\xampp\``` on Windows).
2. Copy the project folder and place it in the **htdocs** folder (located in C:\xampp\htdocs\).   

### Step 3: Create the Database

1. Open phpMyAdmin by navigating to ```http://localhost/phpmyadmin/``` in your web browser.
2. Create a new database named ```template_PHP_web```.
3. Use the following SQL query to create the necessary table for storing users:

```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```
### Step 4: Configure the ```config.php```

1. In the ```config.php``` file, update the database connection details to match your local MySQL setup:

```php
<?php
// Set database connection setting
$host = 'localhost'; // MySQL server address (usually 'localhost')
$dbname = 'template_PHP_web'; // Database name
$username = 'root'; // MySQL username (default is 'root' on XAMPP)
$password = ''; // MySQL password (default empty on XAMPP)
$charset = 'utf8mb4'; // Character set to use

$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (\PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

?>
```
### Step 5: Run the Project

1. Start Apache and MySQL from the XAMPP control panel.
2. Open a browser and navigate to ```http://localhost/project-folder``` (```replace project-folder``` with the name of your project directory).
3. You should be able to see the project running locally.

### Step 6: Register a New User

1. Go to the ```signup.php``` page and register a new user.
2. Enter a full name, email, and a password.
3. Once the registration is successful, you will be redirected to the login page.

---

## Contact

If you encounter any issues or need assistance, feel free to reach out to me on Discord: alphax50.

## Preview 

1. ```Login.php```

<center><img src="https://cdn.discordapp.com/attachments/1257408385918173197/1314693531461488771/Screenshot_2.png?ex=6754b343&is=675361c3&hm=c262770d82e5ef65287907a2dd9fdcfa734602c465ea7c0806a757b92adf847d&"/></center>

2. ```Signup.php```
<center><img src="https://cdn.discordapp.com/attachments/1257408385918173197/1314693259930636310/Screenshot_5.png?ex=6754b302&is=67536182&hm=da92c4a1eb47a4508fe4af005cf31fc0a89c8aca0f5b07719a873935ba968665&"/></center>

3. ```Index.php```
<center><img src="https://cdn.discordapp.com/attachments/1257408385918173197/1314693259238309940/Screenshot_4.png?ex=6754b302&is=67536182&hm=31d12e8d95fe6524b0787628c2193687093bbb633aa062f37331277dad563b39&"/></center>

