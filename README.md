# Chat Application

This is a simple chat application backend with a simple frontend built with PHP using the Slim Framework, SQLite as the database, and PHPUnit for testing. The instructions below guide you through setting up the application, configuring dependencies, setting up the database, running the application, and testing it.

## Prerequisites
Ensure you have the following installed on your system:
- **PHP 8.3**
  You can download and install PHP from the official website: [Download PHP](https://www.php.net/downloads)  
- Composer
  Composer is a dependency manager for PHP. You can install it by following the instructions on the official website: [Get Composer](https://getcomposer.org/download/)  
- SQLite
- Git

## Setup Instructions

### 1. Clone the Repository
Clone the repository to your local machine and navigate to the project directory:
```bash
git clone https://github.com/vishalkanteppa/Chat-Application.git
cd Chat-Application
```

### 2. Install Dependencies
Use Composer to install the required dependencies for the project:

```bash
composer install
```
or try this if the above does not work:
```bash
php composer.phar install
```
This will install the Slim Framework, PHPUnit, and any other necessary packages.

### 3. Setup Database
1. Create the SQLite Database: Navigate to the /data folder and create the SQLite database file:
```bash
mkdir -p data
touch data/database.sqlite
```

2. Run the Database Setup Script: Execute the database setup script to create the required tables:
```bash
php database-setup.php
```

### 4. Run the Application
Start the Slim application using the PHP server:
```bash
php -S localhost:8080 -t public
```
Your application will be running at ```http://localhost:8080```.

### 5. API Endpoints
Here are the available API endpoints:
* Homepage: ```GET /```  - Displays the main interface of the application, allowing users to access all APIs.
* Create User: ```POST /users```
* Create Group: ```POST /groups```
* Join Group: ```POST /join_group```
* Send Message: ```POST /messages```
* List Messages: ```GET /groups/{group_name}/messages```

### 6. Running Tests
To run the PHPUnit tests for the application, follow these steps:
1. Ensure the server is running: Before running the tests, make sure the application server is running on ```localhost:8080```.

2. Run the tests using PHPUnit:
```bash
./vendor/bin/phpunit --testdox tests
```
This will execute all the test cases located in the ```tests``` directory and display a summary of the results.

### 7. Project Structure
Below is a quick overview of the project's structure:
```
/chat-app
│
├── /data/
│   └── database.sqlite       # SQLite Database
│
├── /public/                 
│   └── index.html            # Simple frontend to access the APIs
    └── index.php             # Slim app entry point

│
├── /src/                      # Application code
│   ├── GroupController.php    # Handles group-related logic
│   ├── MessageController.php  # Handles message-related logic
│   ├── UserController.php     # Handles user-related logic
│   ├── ResponseHelper.php     # Handles user-related logic
│   └── Database.php           # Database connection logic
│
├── /tests/                            # Tests for the APIs
│   └── GroupControllerTest.php        # GroupController tests
│   ├── MessageControllerTest.php      # MessageController tests
    └── UserControllerTest.php         # UserController tests

│
├── composer.json             # Composer dependencies
├── composer.lock
├── database-setup.php        # Setup script for the database
└── README.md                 # Instructions

```