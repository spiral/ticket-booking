# Demo application - Ticket booking system

![Ticket booking](https://user-images.githubusercontent.com/773481/204212124-d6de2a92-5450-40e6-9438-effce70741b2.jpg)

It built on the Spiral Framework, which is a high-performance PHP framework that allows developers to create reusable, 
independent, and easy-to-maintain services. In this demo application, you can find an example of using RoadRunner's 
gRPC plugin to create and consume gRPC services.

In this demo system, you can purchase tickets using a fake payment gateway. You can also view and manage your bookings, 
and receive notifications about your tickets and transactions. It's a great way to get a feel for how a real ticket 
booking system works, without any of the actual commitment (or expense).

To ensure the best performance, reliability, and observability, we have used a number of powerful tools and technologies 
such as **Opentelemetry**, **Centrifugo**, **RoadRunner**, **Grafana**, **Bidrdog**, **Buggregator**, and **Temporalio**. 
These help us to understand how the system is behaving, identify and fix issues, and optimize the resources.

![Grafana dashboard](https://user-images.githubusercontent.com/773481/205066017-ecddefc4-1d07-4428-b3ad-af49baadad0a.png)

Overall, our demo ticket booking system is a great example of how Spiral Framework and other tools can be used to build 
a modern and efficient application. We hope you have a blast using it and learning more about the capabilities of 
Spiral Framework and the other tools we've used. 

### Happy (fake) ticket shopping!


## Requirements

- PHP 8.1
- composer 
- docker

## Installation

Clone project into a desired folder 

Run console commands

```bash
docker compose up -d
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
