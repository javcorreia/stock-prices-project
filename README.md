# Stock prices check
Tool to check prices in stock market.  
Uses the [AlphaVantage](https://www.alphavantage.co/) api service to get the latest values.

## Requirements
- [Docker](https://docs.docker.com/get-started/get-docker/)

## Installation
- clone the project:
```shell
git clone https://github.com/javcorreia/stock-prices-project
```
- change to the project directory:
```shell
cd stock-prices-project
```
- run initial main setup:
```shell
./setup
```
- initiate docker stack (everything is run in docker)
```shell
docker compose up -d --build
```
- set up the api (creates the database, runs the migrations and creates the jwt key/pair used by the jwt library)
```shell
cd api
bin/composer run setup
```
- configure your [Alpha Vantage API key](https://www.alphavantage.co/support/#api-key) by setting the following env var in the `api/.env` file 
```dotenv
ALPHA_VANTAGE_API_KEY=YOURAPIKEY
```

## Docker infrastructure 
The api and client are configured as PHP-FPM applications accessed through nginx as proxy.  
- For emails, [mailpit](https://mailpit.axllent.org/) is configured.  
- For caching, [Valkey](https://valkey.io/), a Redis compatible solution, is configured.  
- For database needs, PostgreSQL is configured.  
- For messaging queues, RabbitMQ is congiured.

| Container           | Description                                                                 | Exposed Ports |
|---------------------|-----------------------------------------------------------------------------|---------------|
| nginx               | nginx container, proxy to api php-fpm                                       | 8001          |
| api                 | the api container with php-fpm (Symfony)                                    |               |
| api-message-handler | the message handler container, handles messages sent to rabbitmq (Symfony)  |               |
| client              | the client container running node (vue3)                                    | 8000          |
| db                  | the database container (PostgreSQL)                                         | 5433          |
| cache               | the cache container (Valkey/Redis)                                          | 16379         |
| rabbitmq            | the message queue container (RabbitMQ)                                      | 15672         |
| mailpit             | the mailer container (Mailpit)                                              | 8025          |


## API
A collection to use in postman is available at [support_files folder](./support_files/postman_collection.json).  
The documentation is generated using OpenAPI specs with the symfony bundle 
[NelmioApiDocBundle](https://symfony.com/bundles/NelmioApiDocBundle/current/index.html) and can be accessed at [APIDocs](http://127.0.0.1:8001/api/doc).  
A json file can be generated at [APIDocJSON](http://127.0.0.1:8001/api/doc.json) to be used in tools such as Postman/Insomnia to create a collection.

## Mails
Mailpit is being used as local smtp mail catcher to ease development.  
Sent emails can be read in the mailpit inbox interface at [127.0.0.1:8025](http://127.0.0.1:8025/).

## Testing
To run the unit tests:
- make sure the stack is running, run docker compose up if not:
```shell
docker compose up -d
```
- then, execute the following command in `api` root directory:
```shell
bin/composer run tests
```

## Client
A small client to handle the api calls is available at `http://localhost:8000`.  
It was made using the official [vuejs](https://github.com/vuejs/create-vue) `create-vue` starter kit.  
The following packages were added to aid in the development:
- axios
- jwt-decode
- tailwindcss

## TODO
- [ ] configure throttle in api endpoints using [Symfony's rate limiter](https://symfony.com/doc/current/rate_limiter.html)
- [ ] more unit tests to increase coverage
- [ ] list the history results in a nice formatted table with pagination
- [ ] improve the docker images for production deploy
