
# CollectionKeeper Setup Instructions

## Prerequisites
Before setting up the project, ensure you have the following installed:
- **AMPPS**: A software stack containing Apache, MySQL, PHP, and Perl
- **PHP**: Version 7.x or higher
- **MySQL**: Version 5.x or higher

## Installation Steps

### 1. Download the Project Files
Download the provided zip file containing all necessary project files and unzip it to a directory on your local machine.

### 2. Set Up the Database
- Open AMPPS and start Apache and MySQL.
- Open phpMyAdmin by navigating to `http://localhost/phpmyadmin` in your web browser.
- Create a new MySQL database named `collection`.
- Import the provided `collection.sql` file to set up the necessary tables.

### Create Table SQL Code
```sql
CREATE TABLE `users` (
    `user_id` INT AUTO_INCREMENT PRIMARY KEY,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `games` (
    `game_id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT,
    `title` VARCHAR(255) NOT NULL,
    `game_system` VARCHAR(255),
    `location` VARCHAR(255),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE
);

CREATE TABLE `movies` (
    `movie_id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT,
    `title` VARCHAR(255) NOT NULL,
    `location` VARCHAR(255),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE
);

CREATE TABLE `books` (
    `book_id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT,
    `title` VARCHAR(255) NOT NULL,
    `location` VARCHAR(255),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE
);
```

### 3. Configure the Database Connection
- Open `dbconfig.php` in the root directory of the unzipped project files.
- Update the database connection settings with your own credentials.
```php
<?php
$DBConnect = new mysqli("127.0.0.1", "yourusername", "yourpassword", "collection");
if ($DBConnect->connect_error) {
    die("Connection to DB failed:" . $DBConnect->connect_error);
}
?>
```

### 4. Set Up AMPPS
- Move the unzipped project files to the AMPPS `www` directory (usually located at `C:/Program Files (x86)/Ampps/www` on Windows).
- Ensure AMPPS is running Apache and MySQL.

### 5. Access the Website
- Open your web browser and navigate to `http://localhost/yourprojectdirectory` (replace `yourprojectdirectory` with the name of your project folder).
- You should see the homepage of the CollectionKeeper site.

## Additional Configuration

### API Keys
- Ensure you have valid API keys for the following services:
  - OMDb API
  - Google Books API
  - RAWG API
  - Google AI

- Replace the placeholder API keys in the JavaScript files with your own keys.

### 6. Troubleshooting
- Ensure the database credentials are correct.
- Check that AMPPS has read/write permissions for the project directory.
- Verify that all required PHP extensions are enabled.

## Contact
For any issues or questions, please contact Forest Bierman at [forestbierman@gmail.com].
