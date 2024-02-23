## Introduction

This document provides a comprehensive overview and installation guide for the Apex application, built on Laravel and Docker.

### **Key Features:**

* **Modern Stack:** Utilizes Laravel 10, Postgres, Docker, and Nginx for a robust and scalable architecture.
* **Modular Design:** Organized into reusable modules for enhanced maintainability and flexibility.
* **Clear Separation of Concerns:** Employs Service Repository pattern and Proxy pattern for efficient code organization.
* **Robust Testing:** Includes automated and manual testing tools for ensuring application quality.
* **Detailed Documentation:** This document covers installation, architecture, testing, database connection, and folder structure.

## **Technologies used:**

* PHP: 8.2
* Laravel: 10
* Postgres: latest
* Docker: 24.0.2
* Docker Compose: 2.18.1
* Nginx: latest
* Supervisor: latest

### **Other extensions and utilities:**

* zip, unzip, curl, libzip-dev, libpng-dev, libssl-dev, libpq-dev, gd, pdo, pdo\_pgsql

## Installation:

1. Make sure Docker and Docker Compose are installed on your system.
2. Clone the project from GitHub:  `git clone `[`https://github.com/Alyka/apex.git`](https://github.com/Alyka/apex.git)` && cd apex`
3. Build the images and start the containers:  `docker compose up --build -d`
4. The application should be live at `localhost:8080`.

During installation, a default admin user is created for you, for testing on postman, with the following details:

* **Name:** Admin
* **Email:** [admin@example.com](mailto\:admin@example.com)
* **Password:** admin

You may also create a new admin user by running this command and following the prompt:

`docker exec -it apex php artisan module:create-admin`

This command does the same thing as manually creating a user via postman except that the command sets the role to admin automatically.

## Testing

Run the following command to test the application:

`docker exec -it apex php artisan test`

A postman collection is also bundled with the project. You can import this collection in postman and start your manual testing straight away. This file is located at `src/postman/Apex.postman_collection.json`

## **Connecting to the database from your computer:**

You can connect to the database container from your host computer using a PostgreSQL database client.

**Connection details:**

* **Host:** localhost
* **Port:** 5433
* **Database:** apex
* **Username:** apex
* **Password:** password

> **Note:** These variables are located in the `.env` file. Modify them as needed. If you change the port, also update it in the `docker-compose.yml` file before starting the container.

## **Architecture concepts:**

* **Service Repository pattern:** Used to abstract the data access layer from the business logic layer. Repositories handle database interactions, and services encapsulate business logic.
* **Pluggability:** The entire application is built as a Laravel package, facilitating seamless integration into a fresh Laravel installation.
* **Modularity:** The application is organized as a collection of independent, pluggable, and reusable modules. This separation helps maintain a clear understanding of each module's responsibilities within a the application, promoting the principles of separation of concerns and modularity in software design.
* **Proxy Pattern:** Methods are not explicitly defined in the controllers rather a controller class acts as a proxy that forwards calls to its methods to a corresponding service class, which is responsible for handling the business logic. This pattern helps in avoiding repetition by eliminating the need to create duplicate methods in both the controller and service classes.

## **Folder structure:**

* **src:** Houses all project code. Sub-folders include:
  * **core:** Contains essential components and functionalities, including base classes, service providers, models, services, repositories, helpers/utilities, custom Artisan commands, and shared components.
  * **docker:** Configuration files for building and configuring images and containers:
    * `Dockerfile`: Instructions for building the base image.
    * `entrypoint.sh`: Runs pre-initialization scripts for automated setup, including database migrations, seeding, Laravel Passport installation, generating a Passport personal client, and storing it in the `.env` file.
    * `nginx.conf`: Nginx configurations with server blocks.
    * `supervisord.conf`: Supervisor configuration for managing processes (fpm, schedules, queues).
  * **modules:** Contains all application modules:
    * `Auth`: Handles authentication and Passport API token generation. Includes a `config/config.php` file for auth configurations, merged with the default Laravel `config/auth.php` file. This avoids modifying core framework files.
    * `Role`: Handles role management. Separating roles prevents reusability, scalability, and maintainability issues as the application grows.
    * `User`: Handles user creation, update, and deletion.

## Module

Each module uses a similar folder structure to a full Laravel app to ensure a consistent and familiar organization.  Each module has its own `controllers`, `models`, `policies`, `form requests`, `service providers`, `API resources`, `services`, `repositories`, `config`, `routes`, `migrations`, `seeders`, `factories`,  `tests`, `events`, `listeners` and even `custom artisan commands` . There are other folders like `contracts` and `facades.` which contain contracts and facades classes that provide an abstraction layer, a way to interact with components of the application at a higher level to promot **loose coupling**.

### Interaction Flow:

* A request is received by the controller.
* The controller automatically validates the input using a form request class corresponding to the targeted controller action. For example, a request to the controller action `UserController@index` will, by default, utilize a form request class named `IndexRequest.php` located in the `./Http/Requests/` directory of the `User` module.
* Upon successful validation of the request, the controller forwards the call to the service layer to execute the associated business logic.
* The service layer, in turn, communicates with the repository for necessary data operations.
* If no exceptions are encountered, the service layer returns the response back to the controller.
* The controller then transforms the data using an API resource class matching the name of the targeted controller action. Specifically, for a request to the controller action `UserController@index`, the application will use an API resource class named `IndexResource.php` located in the `./Http/Responses/` directory of the user module. In cases where the action is named `index`, the application automatically knows to return a collection by calling the collection method of the resource class. Simply put, if the action is named `index`, the application will return `IndexResource::collection($databaseCollection)` otherwise, it returns `new IndexResource($databaseModel)`.
* The final transformed response is sent back to the client.
