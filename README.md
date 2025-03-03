# API

This folder contain the API (obvious hey). 
The API is made with symfony.


A reminder of all the command I have run for setting up the project:
```shell
# get your IP and update the Dockerfile, for example with
hostname -i | awk '{print $1}'

# create a .env for docker
touch .env
echo "DB_USERNAME=user" >> .env && echo "DB_PASSWORD=pass" >> .env && echo "DB_DATABASE=dbname" >> .env

# launch docker (dcup = docker compose up), the --build is up to you
dcup --build

# go into the docker
docker exec -it summitv3-api /bin/bash

# then install symfony
symfony new --no-git api
cd api

# the dev packages
composer require symfony/maker-bundle --dev
composer require security
composer require orm
composer require orm-fixtures --dev

# and finally create the .env.local for the db
cd symfonyProject
touch .env.local

# then you can run the server
# --allow-all-ip is important because we are in a docker
symfony server:start --allow-all-ip

# if you want to generate certificate for TLS you have to be sudo
docker exec -u root -it summitv3-api /bin/bash
symfony server:ca:install
```

## Fixtures

Fixtures have been implemented so use it :) 

```shell
symfony console doctrine:fixtures:load
```

## JWT

You need to add the key files for the jwt to work:
```shell
openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
```

Next you need to update the `.env.local` with the password set just before, look at the `.env` for the example.
