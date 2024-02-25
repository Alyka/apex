## Introduction

This document provides a comprehensive overview and installation guide for the Apex application, built on Laravel and Docker.

### **Key Features:**

* **Modern Stack:** Utilizes Laravel 10, Postgres, Docker, and Nginx for a robust and scalable architecture.
* **Modular Design:** Organized into reusable modules for enhanced maintainability and flexibility.
* **Clear Separation of Concerns:** Employs Service Repository pattern and Proxy pattern for efficient code organization.
* **Robust Testing:** Includes automated and manual testing tools.
* **Detailed Documentation:** This document covers installation, architecture, testing, database connection, and folder structure.

## **Technologies used:**

* PHP: `8.2`
* Laravel: `10`
* Postgres: `latest`
* Docker: `24.0.2`
* Docker Compose: `2.18.1`
* Nginx: `latest`
* Supervisor: `latest`

### **Other extensions and utilities:**

* xdebug (for reporting test coverage), 
* zip
* unzip
* curl
* libzip-dev
* libpng-dev
* libssl-dev
* libpq-dev
* gd
* pdo
* pdo_pgsql

## Installation:

1. Make sure Docker and Docker Compose are installed on your system.
2. Clone the project from GitHub:  

```bash
git clone https://github.com/Alyka/apex.git && cd apex
```

3. Run the deployment script: 

```bash
bash deploy.sh
```

Then wait till the setup is completed. 

> You will have to leave the terminal open to keep the application running. This is because the script runs the containers in the foreground, not in detached mode. So open another terminal to run the test. If you must change this behavior and run the containers in detached mode, edit the `deploy.sh` script accordingly. However, then you may not be able to know when the setup actually gets completed or when something goes wrong.

4. The application should be live at 

```bash
localhost:8080
```

During installation, a default admin user is created for you, for testing on postman, with the following details:

* **Name:** Admin
* **Email:** admin@example.com
* **Password:** admin

You may also create a new admin user by running this command and following the prompt:

```bash
docker exec -it apex php artisan module:create-admin
```

This command does the same thing as creating a user manually except that, in this case, the role is automatically set to `admin`.

## Testing

Run the following command to test the application. A coverage report will also be displayed (not less than 88% total coverage is expected).

```bash
docker exec -it apex php artisan test --coverage
```

The abo

A postman collection is also bundled with the project. You can import this collection in postman and start your manual testing straight away. The file is located at `src/postman/Apex.postman_collection.json`

> Notice the pre-request script on the collection `pm.request.headers.add({key: 'X-Requested-With', value: 'XMLHttpRequest' });` which automatically add the `X-Requested-With` header with a value of `XMLHttpRequest` in all requests. This header is a standard way in laravel to tell the application that the request is an AJAX request and a JSON response is expeted instead of a HTML response.

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

Each module uses a similar folder structure to a full Laravel app to maintain a consistent and familiar organization.  Each module has its own `controllers`, `models`, `policies`, `form requests`, `service providers`, `API resources`, `services`, `repositories`, `config`, `routes`, `migrations`, `seeders`, `factories`,  `tests`, `events`, `listeners` and even `custom artisan commands` . 

There are other folders like `contracts` and `facades.` which contain contracts and facades classes that provide an abstraction layer, a way to interact with components of the application at a higher level to promot **loose coupling**. You may examine the code for more details.

## Interaction Flow:

* A request is received by the controller.
* The controller automatically validates the inputs using a form request class corresponding to the targeted controller action.
* Upon successful validation of the request, the controller forwards the call to the service layer to execute the associated business logic.
* The service layer, in turn, communicates with the repository for necessary data operations. 
* If no exceptions are encountered, the service layer returns the response back to the controller.
* The controller then transforms the data using an API resource class matching the name of the targeted controller action.
* The final transformed response is sent back to the client.


### Example

Let's examine a typical request where we want to get a paginated list of all users.

`GET /users?limit=15`

#### The route
The route will look like this:

```php
Route::get('/users', [UserController::class, 'index']);
```

#### The controller

This is what the controller does for us out-of-the-box and we don't have to worry writing it.

```php
use Illuminate\Http\Resources\Json\ResourceCollection;
use Modules\User\Http\Requests\IndexRequest;
use Modules\User\Http\Resources\IndexResource;

/**
 * Display all users.
 *
 * @param IndexRequest $request
 * @return ResourceCollection
 */
public function index(IndexRequest $request): ResourceCollection
{
    $request = $request->validated();

    $users = $this->service->index($request);

    // The action name 'index' tells the controller that a collection of resources is expected
    // so it transforms the resource to a collection.
    return IndexResource::collection($users);
}
```

Because every action in the controller follows this pattern, we use the **proxy pattern** to do it once and for all. After all, that's what design patterns are meant for; reusable solutions to common/reccurent problems that arise in software design and development.

> We will not see the above code in the controller because every controller extends a base controller that performs the logic flexibly based on the module to which the controller belongs. However, we can still go ahead to explicitly define the action in our controller in case we want more control or need to override the default behavior. It will still work in any case.

Also notice that names of the form request and api resource classes used correspond with that of the controller action.

- Action: `index`
- Form request class: `IndexRequest`
- Api resource: `IndexResource`

#### The service

Continuing with the same request flow above, this is what the service class should look like:

```php
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Display a paginated list of all users.
 *
 * @param array $request
 * @return LengthAwarePaginator
 */
public function index(array $request): LengthAwarePaginator
{
    return $this->repository->latest()->paginate($request['limit']);
}
```

> But then again, we may not see this code directly in the service class because all our service classes extend a base service class that defines it for us.

#### The repository

As we can see in the service class implementation above, the repository called the same methods as a typical eloquent model. That is because we also used the **proxy pattern** here to forward all calls the repository methods to its underlying model. So we can confidently use the repository the same way we use a laravel eloquent model.



### Another example

Assuming we have another type of request where we want to update a specified user:

`PUT|PATCH /users/1?name=Matthew`

The route will look like this:

#### The route

```php
Route::match(['PUT', 'PATCH'], '/users/{id}', [UserController::class, 'update']);
```

#### The controller

This is what the controller does for us out-of-the-box and we don't have to worry writing it.

```php
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\User\Http\Requests\UpdateRequest;
use Modules\User\Http\Resources\UpdateResource;

/**
 * Update the specified user.
 *
 * @param UpdateRequest $request
 * @param int $id
 * @return JsonResource
 */
public function update(UpdateRequest $request, int $id): JsonResource
{
    $request = $request->validated();

    $updatedUser = $this->service->update($request, $id);

    // Notice how a single resource is returned here and not a collection.
    return new UpdateResource($updatedUser);
}
```

- Action: `update`
- Form request class: `UpdateRequest`
- Api resource: `IUpdateResource`


#### The service

```php
/**
 * Update the specified user.
 *
 * @param array $request
 * @param int $id
 * @return Model
 */
public function update(array $request, int $id): Model
{
    $model = $this->repository->findOrFail($id);
    $model->update($request);

    return $model->refresh();
}
```

If we have more route parameters, they will all be passed as extra arguments to the service method.
