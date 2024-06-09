# Tasks Management System

 RESTful API for a simple task management system using CodeIgniter 4. The APIs allow users to create, read, update, and delete tasks. Each task have a title, description, status, and due date. Additionally, the assignment involves implementing JWT for user authentication.


 ## Table of Contents

- [Prerequisites](#prerequisites)
- [Installation and Configuration](#installation-and-configuration)
- [Endpoints](#endpoints)



## Prerequisites

Please be sure that you have the following requirements to run the project successfully:

- PHP 8.1 or later
- CodeIgniter 4
- Composer installed
- MySQL

## Installation and configuration

Please follow the following steps:

1- Clone the project using this command : 
git clone https://github.com/RaghadKarasneh/ManagementSystemUsingCodeigniter.git

2- Run the following commands:

- cd ManagementSystemUsingCodeigniter
- composer install
- cp .env.example .env

3- Configure the .env file with your database credentials:
### Database configuration:

database.default.hostname = localhost

database.default.database = your_database_name

database.default.username = your_database_username

database.default.password = your_database_password

database.default.DBDriver = MySQLi

database.default.DBPrefix =

database.default.port = 3306

### Generate JWT Secret Key:
- The JWT Secret Key is a crucial component for securing authentication in the application. It is used to sign JWT tokens, ensuring that they are not tampered with during transmission between the client and server.
- To generate JWT Secret Key, you need a random 32 bytes key, and add it to the .env file ( JWT_SECRET = key_value ). Here are some ways to generate the key:

    - Run the following command to generate JWT_SECRET: node -e "console.log(require('crypto').randomBytes(32).toString('hex'));"    

    - Online Generator: such as RandomKeygen tool.

## Endpoints
- You will find below the endpoints of the application:
    ### Authentication Endpoints
    - POST /auth/register : to register a new account.

    - POST /auth/login : to login within an existing account. 

    ### Task Endpoints
    - GET /tasks : to retrieve all tasks.

    - POST /tasks : to create a new task.

    - GET /tasks/{id} : to retrieve a specific task.

    - PUT /tasks/{id}: to update a spcific task.

    - DELETE /tasks/{id}: to delete a specific task.