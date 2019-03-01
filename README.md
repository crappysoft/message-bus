# Requirements

php 7.2
mysql 5.7
nodejs, npm, yarn

# Installation

Configure .env.local

```
composer install
```

Create DB
```
bin/console doctrine:database:create
bin/console doctrine:schema:update -force
```

Generate keys for jwt
```
$ mkdir -p config/jwt # For Symfony3+, no need of the -p option
$ openssl genrsa -out config/jwt/private.pem -aes256 4096
$ openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
```

Load Fixtures
```
bin/console hautelook:fixtures:load -n
```

# Users
| username | password |
|----------|----------|
| admin    | 123qwe   |

Install yarn dependencies
```
yarn install
yarn run dev --watch
```

Run local server
```
bin/console server:run
```

# Swagger
Open http://localhost:8000/api

# Run Tests
Configure .env.test.local

```
bin/phpspec run
bin/behat
npx dredd
```
