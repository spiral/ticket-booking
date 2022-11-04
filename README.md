# Ticket booking system

This is an example project base on Spiral Framework and GRPC microservices.

## Requirements

- PHP 8.1
- composer 
- docker

## Installation

Clone project into a folder 

Run console commands

```bash
chmod +x run.sh
./run.sh
```

## Project structure

- `frontend` - SPA GUI
- `web` - REST API
- `users` - GRPC microservice. It's responsible for user management and auth tokens management. Works only with database.
- `cinema` - GRPC microservice. It's responsible for movies and reservation. Works with database and temporal.
- `payment` - GRPC microservice. Fake payment gateway. It's receives requests for money charging and responses with receipt.
- `shared` - Shared package for all microservices. It contains common classes, proto files and compiler, DTO's, GRPC clients, e.t.c.

`docker-compose.yaml` contains all necessary containers to run project.
 - http://127.0.0.1:3000/ - Ticket booking system
 - http://127.0.0.1:9411/ - Zipkin
 - http://127.0.0.1:3002/ - Birddog
 - http://127.0.0.1:8088/ - Temporal
 - http://127.0.0.1:8089/ - Centrifugo
- http://127.0.0.1:23517/ - Buggregator

### Database

 - Connection address: `127.0.0.1:5432`
 - Username: `homestead`
 - Password: `secret`
 - Database: `homestead`


### TODO

- Register stripe in sandbox mode
- Book and charge before 15 min screening. User will have the ability to cancel ticket.
- Schema of services