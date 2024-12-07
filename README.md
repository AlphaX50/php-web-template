# PHP Web Template

This project is a simple PHP web template that demonstrates how to create a user authentication system using ```PDO``` with ```MySQL```. It includes registration, login, password hashing (using ```bcrypt```), and session management. It also uses the ```XAMPP``` local development environment to run the project.

## Features

- ```User Registration```: Allows users to create an account with a full name, email, and password.
- ```User Login```: Users can log in with their email and password.
- ```User Profile``` : Users can change their bio, email, and full name.
- ```User Settings``` : Users can change their passwords and delete their accounts.
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
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    bio TEXT DEFAULT NULL,
    profile_picture VARCHAR(255) DEFAULT NULL,
    role TINYINT(1) DEFAULT 0
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

---

## Preview 

1. ```Login.php```

<center><img src="https://cdn.discordapp.com/attachments/1221910095193964715/1315044213049659453/Screenshot_11.png?ex=6755f9dc&is=6754a85c&hm=34a60c8436b7aa2bd7f0bd3ab678ee38561bd0d5e98ff9b469695bbb4e133dce&"/></center>

2. ```Signup.php```

<center><img src="https://cdn.discordapp.com/attachments/1221910095193964715/1315044213422948393/Screenshot_15.png?ex=6755f9dc&is=6754a85c&hm=94f206141d14fa601f4f2916d32503e80e8dcb02f8dcaa84071a7de3772ac0fb&"/></center>

3. ```Index.php```

<center><img src="https://cdn.discordapp.com/attachments/1257408385918173197/1314693259238309940/Screenshot_4.png?ex=6754b302&is=67536182&hm=31d12e8d95fe6524b0787628c2193687093bbb633aa062f37331277dad563b39&"/></center>

4. ```Profil.php```

<center><img src="https://cdn.discordapp.com/attachments/1221910095193964715/1315044214064807946/Screenshot_10.png?ex=6755f9dc&is=6754a85c&hm=817c58d944c8606dedf297ad3e069088f0136821694e2fb1631affe8a13cb7d7&"/></center>

5. ```Settings.php```

<center><img src="https://cdn.discordapp.com/attachments/1221910095193964715/1315044219362213948/Screenshot_14.png?ex=6755f9de&is=6754a85e&hm=c78fcd4e05e1204f897f2be045bb44f430f9aae318ed2abc887cf35436a673e8&"/></center>