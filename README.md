# Politics Forum

This is a forum based on politics

It's being developed using Laravel and Vue.js

Currently i only implemented 3 pages:
    
    / or /forum/
    
    /forum/{category} --> turizem, gospodarstvo, kultura, kmetijstvo
    
    /forum/{category}/{id}

Pages being worked on:

    /user

    /user/login

    /user/register

    /user/dashboard

# Video

Here is a link to 1 minute speedrun of the site: https://youtu.be/pxGDl2HJkpI

# Setup Database (MYSQL)

Database name: politics_forum
<br/>
In .env file make sure you set database username and password according to your settings...
<br/>

## Method one
Import politics_forum.sql file that is located in <b>sql</b> folder

## Method two
run all the migrations (run all commands below before running server)

    php artisan migrate:reset

    php artisan migrate --path=/database/migrations/2022_07_19_141251_create_categories_table.php

    php artisan migrate --path=/database/migrations/2022_07_19_134845_create_tags_table.php   

    php artisan migrate

## Seeder
Fill the database with dummy data

    php artisan db:seed

## Run server (run on seperate terminals)
First you should execute the command below to install all the needed dependencies

    npm install

And then start the server

    php artisan serve

    npm run watch

# TODO

    - user authentication (bearer tokens, probably will use symfony)
    - create/edit posts
    - filter by likes, latest, etc.