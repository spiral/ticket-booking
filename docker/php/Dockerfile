ARG ROAD_RUNNER_IMAGE=2.12.3

# Build rr binary
FROM ghcr.io/roadrunner-server/roadrunner:$ROAD_RUNNER_IMAGE as rr

# Clone the project
FROM alpine/git as git

ARG REPOSITORY=https://github.com/spiral/ticket-booking
ARG BRANCH=master

RUN git clone -b $BRANCH $REPOSITORY /app

FROM spiralscout/php81-grpc:1.0.0 as backend

RUN apk update

RUN rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install \
        pgsql pdo_pgsql

RUN curl -s https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/ --filename=composer

ARG SERVICE_NAME
ARG APP_VERSION=v1.0
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV OTEL_SERVICE_NAME=php-$SERVICE_NAME

COPY --from=git /app/$SERVICE_NAME /app/$SERVICE_NAME
COPY --from=rr /usr/bin/rr /app/$SERVICE_NAME
COPY --from=git /app/proto /app/$SERVICE_NAME/proto
COPY --from=git /app/shared /app/shared

WORKDIR /app/$SERVICE_NAME

RUN composer config --no-plugins allow-plugins.spiral/composer-publish-plugin false
RUN composer install

LABEL org.opencontainers.image.source=$REPOSITORY
LABEL org.opencontainers.image.description="Ticket booking $SERVICE_NAME"
LABEL org.opencontainers.image.licenses=MIT

CMD ["rr", "serve"]
