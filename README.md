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