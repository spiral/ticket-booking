# Clone the project
FROM alpine/git as git

ARG REPOSITORY=https://github.com/spiral/ticket-booking
ARG BRANCH=master
ARG APP_VERSION=v1.0

RUN git clone -b $BRANCH $REPOSITORY /app

FROM node:19-alpine

COPY --from=git /app/frontend /app
ENV NODE_OPTIONS=--openssl-legacy-provider

WORKDIR /app

RUN yarn install

ENV HOST 0.0.0.0
EXPOSE 3000

LABEL org.opencontainers.image.source=$REPOSITORY
LABEL org.opencontainers.image.description="Ticket booking Frontend"
LABEL org.opencontainers.image.licenses=MIT

CMD ["yarn", "dev"]