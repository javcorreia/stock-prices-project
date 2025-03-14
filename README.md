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

## Usage
TODO...
