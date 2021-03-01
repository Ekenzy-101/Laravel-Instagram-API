# LARAVEL INSTAGRAM CLONE API

This is the backend code for the instagram clone

## TECHNOLOGY STACKS

- Laravel
- GraphQL
- PHP
- PostgreSQL
- AWS S3

## SETUP

- Clone this repo by typing `git clone <repo url>`
- Go to the directory of repo by typing `cd <name of folder>`
- Run the command `npm install` to install all javascript dependencies
- Run the command `composer install` to install all php dependencies
- Set these environment variables
  `FRONTEND_ENDPOINT=<Your Frontend Endpoint e.g http://localhost:3000>`
  `MAIL_HOST=smtp.sendgrid.net`
  `MAIL_MAILER=smtp`
  `MAIL_PORT=587`
  `MAIL_USERNAME=apikey`
  `MAIL_PASSWORD=<Your Sendgrid api key e.g. SG.>`
  `MAIL_ENCRYPTION=tls`
  `MAIL_FROM_ADDRESS=<Your verified sender email address from Sendgrid`
  `AWS_ACCESS_KEY_ID=<IAM User Key ID>`
  `AWS_SECRET_ACCESS_KEY=<IAM User Access Key>`
  `AWS_DEFAULT_REGION=<Your default aws region>`
  `AWS_BUCKET=<S3 Bucket Name>`
  `DATABASE_URL=<Postgres Connection URI>`
- Run the command `php artisan jwt:secret` to generate a secret key for signing tokens
- Run the command `php artisan key:generate` to generate a application key
- Run the command `php artisan migrate` to create tables in your database
