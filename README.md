# Politics Forum

This is a forum based on politics

It's being developed using Laravel and Vue.js

Currently i only implemented 3 pages:
    
    / or /forum/
    
    /forum/{category} --> turizem, gospodarstvo, kultura, kmetijstvo
    
    /forum/{category}/{id}

Database name: PoliticsForum
<br/>
Import .sql file that is located in <b>sql</b> folder

Or run migrations (run all commands before running server)

    php artisan migrate:reset

    php artisan migrate --path=/database/migrations/2022_07_19_141251_create_categories_table.php

    php artisan migrate --path=/database/migrations/2022_07_19_134845_create_tags_table.php   

    php artisan migrate

    php artisan db:seed

Running server (run on seperate terminals)

    php artisan serve

    npm run watch

TODO
    - user authentication (bearer tokens, probably will use symfony)
    - create/edit posts
    - filter by likes, latest, etc.