# Demo application - Ticket booking system

![Ticket booking](https://user-images.githubusercontent.com/773481/204212124-d6de2a92-5450-40e6-9438-effce70741b2.jpg)
This is an example project base on Spiral Framework and GRPC microservices with Grafana dashboard.

![Grafana dashboard](https://user-images.githubusercontent.com/773481/205066017-ecddefc4-1d07-4428-b3ad-af49baadad0a.png)

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

## Services dashboards

- http://127.0.0.1:3000/ - Ticket booking system
- http://127.0.0.1:9411/ - Zipkin
- http://127.0.0.1:3001/ - Grafana (login: `admin`, password: `secret`)
- http://127.0.0.1:8088/ - Temporal
- http://127.0.0.1:8089/ - Centrifugo
- http://127.0.0.1:8003/ - Buggregator
- http://127.0.0.1:8002/ - Birddog

## Project structure

- `frontend` - SPA GUI
- `centrifugo` - Centrifugo API
- `web` - REST API
- `users` - GRPC microservice. It's responsible for user management and auth tokens management. Works only with database.
- `cinema` - GRPC microservice. It's responsible for movies and reservation. Works with database and temporal.
- `payment` - GRPC microservice. Fake payment gateway. It's receives requests for money charging and responses with receipt.
- `shared` - Shared package for all microservices. It contains common classes, proto files and compiler, DTO's, GRPC clients, e.t.c.
`docker-compose.yaml` contains all necessary containers to run project.

### Database

 - Connection address: `127.0.0.1:5432`
 - Username: `homestead`
 - Password: `secret`
 - Database: `homestead`

![ticket reservation](https://user-images.githubusercontent.com/773481/205067692-6fe4c5b4-904d-4637-8bc2-7f84eff1d5fb.png)

### TODO

- Register stripe in sandbox mode
- Book and charge before 15 min screening. User will have the ability to cancel ticket.
- Schema of services
