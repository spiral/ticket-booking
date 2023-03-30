ARG APP_IMAGE=spiralscout/php81-grpc:1.0.0
ARG ROAD_RUNNER_IMAGE=latest

# Build rr binary
FROM spiralscout/roadrunner:$ROAD_RUNNER_IMAGE as builder

FROM $APP_IMAGE

ARG SERVICE_NAME

RUN apk update

RUN rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install \
        pgsql pdo_pgsql

COPY --from=composer:2.4.4 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY ./${SERVICE_NAME}/composer.json /var/www
COPY ./shared /var/shared

RUN composer install --no-autoloader --no-scripts && composer clear-cache && composer dump --optimize --no-scripts

COPY ./${SERVICE_NAME} /var/www
COPY --from=builder /usr/bin/rr /var/server/
COPY ./${SERVICE_NAME}/.rr.yaml /var/server/
