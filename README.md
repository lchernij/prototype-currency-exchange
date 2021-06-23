# Prototype Currency Exchange

## About

This is a simple project about consuming a public API with currency exchange.

## System Requirements
- `php 7.4`
- `Xdebug`
- `composer`
- at least `sqlite` to database

## How to install

- clone the repository
- run `composer install`
- if using `sqlite`, create a database at the database directory, named at `database.sqlite` Example: `database/database.sqlite`

## How to run the tests

Use `vendor/bin/phpunit`

## How to run the test coverage

`vendor/bin/phpunit --coverage-html .github/workflows/reports/`

I suggest run this command because this folder is in `.gitignore`:
 
## Features
### More info see this [kanban](https://github.com/lchernij/prototype-currency-exchange/projects/1)

|Feature|Situation|Comments|
|:--|--:|:--|
|User register|done||
|User login|done||
|User list selected currency|done||
|System update currency list|done|
|User view one currency register|parcial|Need add daily values to the currency|
|User registre a currency to receive updates|not started||
|System send e-mail with update|not started|

## Technical details

- Has GitHub Actions run tests when the Pull Request is open, you can see [here](https://github.com/lchernij/prototype-currency-exchange/actions)
    - Also, this action creates the coverage, you can see an example [here](https://github.com/lchernij/prototype-currency-exchange/actions/runs/962768838). At the bottom of the page, in the Artifact section. If the zip sees "empty" check if your operating system shows the `.` folder like `.github`
- Used some framework features like:
    - Eager Loading
    - Schedule
    - Commands
    - Trait
    - Resource
    - FormRequest
    - Factory
    - Relationships
- Can use [this](https://www.postman.com/lchernij/workspace/prototype-currency-exchange/overview) postman collection

## Future
- Will be in Heroku someday
- Will have swagger documentation

## Documentations used

- [Laravel Doc](https://laravel.com/docs/8.x)
- [Laravel Sanctum](https://www.positronx.io/build-secure-php-rest-api-in-laravel-with-sanctum-auth/) to make the authentication
- [Laravel Job schedule](https://www.positronx.io/laravel-cron-job-task-scheduling-tutorial-with-example/) to make the command to update currency list
- [BCB](https://olinda.bcb.gov.br/olinda/servico/PTAX/versao/v1/swagger-ui3#/) to get informations about currencies