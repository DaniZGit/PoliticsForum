# Politics Forum

This is a forum based on politics

It's being developed using Laravel and Vue.js

Currently i only implemented 3 pages:
    
    / or /forum/
    
    /forum/{category} --> turizem, gospodarstvo, kultura, kmetijstvo
    
    /forum/{category}/{id}

Database name: PoliticsForum
Migrations

    php artisan migrate --path=/database/migrations/2022_07_19_141251_create_categories_table.php
    
    php artisan migrate --path=/database/migrations/2022_07_19_134845_create_tags_table.php   

    php artisan migrate

    php artisan db:seed

TODO
    - user authentication (bearer tokens, probably will use symfony)
    - create/edit posts
    - filter by likes, latest, etc.