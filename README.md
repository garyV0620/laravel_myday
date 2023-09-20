# Myday App 
This App is like a twitter that you can post and comments on each post. 

## About
This project uses laravel framework with blade template.
It also uses websocket on a realtime comment update and shows also if someone is commenting on a myday

## Getting Started
You can clone the repository (https://github.com/garyV0620/laravel_myday.git) or download it 

### Prerequisites
-PHP 8.0 and Up
-SQL
-Or you can use local server such as WAMP and XAMP and LAMP
-COMPOSER
-NODE JS 
-GIT

### Installation
1. Clone repository using GIT
    $ git clone https://github.com/garyV0620/laravel_myday.git
    $ cd laravel_myday
2. Run composer install on your cmd or terminal.
3. Run npm install on your terminal.
4. Copy .env.example file to .env on the root folder. You can type copy .env.example .env if using command prompt Windows or cp .env.example .env if using terminal Ubuntu.
5. Edit .env change all Database settings
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=laravel
    DB_USERNAME=root
    DB_PASSWORD=
    
    Or you can use sqlite by commenting all on the top from your .env then use
    DB_CONNECTION=sqlite

6. Generate an app encryption key by running php artisan key:generate.
7. Run php artisan migrate. (if sqlite is use type 'yes' if prompt to create file)
8. To Run the APP
    8.1. Run npm run dev. (if you want to use custom host and port Run npm run dev -- --host=yourhostORIp --port=yourPort)
    8.2. Open another terminal
    8.3. Run php artisan serve. (if you want to use custom host and port Run php artisan serve --host=yourhostORIp --port=yourPort)(do not use same port on npm run dev)
    8.4. Open another terminal then Run php artisan websocket:init 
    8.5. Then check INFO  Server running on [http://127.0.0.1:8001].
    8.6. Open http://127.0.0.1:8001 on your browser.
9. Enjoy using the APP




