# Paradise API

Paradise API is a RESTful Application Programming Interface (API) designed for the interactive management of animals in a zoo. It provides a range of features allowing users to perform various operations, including CRUD (Create, Read, Update, Delete) operations on animals.

## API Usage

### Get hello world message

```http
  GET /hello
```

### API Documentation with Swagger UI

Interactive API documentation is available via Swagger UI. This allows you to visualize and interact with the API's resources without having any of the implementation logic in place.

To access Swagger UI:

Ensure the application is running.
Open a browser and navigate to  <http://localhost:8080/swagger-ui>.

This interface will allow you to directly test the API endpoints.

## Running Locally with Docker

To run this project locally, you'll need Docker and Docker Compose. If you haven't installed them, follow the steps below:

### 1. Install Docker

To install Docker, follow the instructions specific to your operating system on the [Docker download page](https://www.docker.com/get-started).

### 2. Install Docker Compose

Docker Compose is usually included with modern Docker installations. If not, follow the instructions to [install Docker Compose](https://docs.docker.com/compose/install/).

### 3. Clone the Project

```bash
  git clone https://github.com/LeNelSoN/paradise.git
```

### 4.Go to the project directory

```bash
  cd paradise
```

### 5. Launch Docker Containers

```bash
  make up
```

### 6. Access the Application

The application will be accessible at http://localhost:8080.

### 7. Stop Docker Containers

When you're done, stop the Docker containers.

```bash
  make down
```

#### Docker Compose Version

Make sure you have Docker Compose installed on your machine. 
If you are using a standalone version of Docker Compose, please adjust the `docker compose` variable in the Makefile.