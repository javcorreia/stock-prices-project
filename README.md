# Stock prices check
Tool to check prices in stock market.  
Uses the [AlphaVantage](https://www.alphavantage.co/) api service to get the latest values.

## Requirements
- [Docker](https://docs.docker.com/get-started/get-docker/)

### Optional requirements
There's no need to install extra packages.  
Tools to run the needed setup are provided in the `bin` directories of each stack.  
But if you want them installed in your system:
- [PHP](https://php.new)
- [Composer](https://getcomposer.org/download/)
- [NVM](https://github.com/nvm-sh/nvm) for your node needs

## Installation
- clone the project:
```shell
git clone https://github.com/javcorreia/stock-prices-project
```
- change to the project directory:
```shell
# it's the following directory, if not changed in clone 
cd stock-prices-project
```
- run initial main setup:
```shell
./setup
```
- initiate docker stack
```shell
docker compose up -d --build
```
- setup api
```shell
cd api
bin/composer run setup
```
- setup client
```shell
cd ../client
bin/composer run setup
```

## Infrastructure 
The api and client are configured as PHP-FPM applications accessed through nginx as proxy.  
- For emails, [mailpit](https://mailpit.axllent.org/) is configured.  
- For caching, [Valkey](https://valkey.io/), a Redis compatible solution, is configured.  
- For database needs, PostgreSQL is configured.  
- For messaging queues, RabbitMQ is congiured.

| Container | Description                                                     | Exposed Ports |
|-----------|-----------------------------------------------------------------|---------------|
| nginx     | nginx configured to access both api and client container        | 8000 - 8001   |
| api       | the api container with php-fpm (Symfony)                        |           |
| client    | the frontend container with php-fpm (Laravel with Vue/Innertia) |           |
| db        | the database container (PostgreSQL)                             | 5433          |
| cache     | the cache container (Valkey/Redis)                              | 16379          |
| rabbitmq     | the message queue container (RabbitMQ)                          | 15672          |
| mailpit     | the mailer container (RabbitMQ)                                 | 8025          |


## API
A collection to use in postman is available at [support_files folder](./support_files/postman_collection.json).  
The documentation is generated using OpenAPI specs with the symfony bundle 
[NelmioApiDocBundle](https://symfony.com/bundles/NelmioApiDocBundle/current/index.html) and can be accessed at [APIDocs](http://127.0.0.1:8001/api/doc).  
A json file can be generated at [APIDocJSON](http://127.0.0.1:8001/api/doc.json) to be used in tools such as Postman/Insomnia to create a collection.

## Mails
Mailpit is being used as local smtp mail catcher to ease development.  
Sent emails can be read in the mailpit inbox interface at [127.0.0.1:8025](http://127.0.0.1:8025/).
