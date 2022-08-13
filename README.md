# Politics Forum

This is a forum based on politics

It's being developed using Laravel and Vue.js<br/>
Authorization is being done using Sanctum and Vuex(for storing tokens)

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

# Setup

Before continuing please first run these 2 commands to install all the needed dependencies:

    composer install
    
    npm install
    
# Setup Database (MYSQL)

Database name: politics_forum
<br/>
Make sure you setup your database and add your db credentials(databaseName, username, password) to the .env.example file and then rename the file to .env
<br/>

## Method one
Import politics_forum.sql file that is located in <b>sql</b> folder

## Method two
run all the migrations (run all commands below before running server)

    php artisan migrate:reset

    php artisan migrate --path=/database/migrations/2022_07_19_141251_create_categories_table.php

    php artisan migrate --path=/database/migrations/2022_07_19_134845_create_tags_table.php   

    php artisan migrate

    php artisan db:seed

## Start Project (run on seperate terminals)

Start the server

    php artisan serve

    npm run watch

<b>In case</b> you get a "<b>generate key</b>" error, please first run: <b>php artisan key:generate</b>

# TODO

    - user authentication (bearer tokens, probably will use sanctum) --> DONE (tokens are stored in Vuex state and not in cookies...)
    - create/edit posts
    - filter by likes, latest, etc.
