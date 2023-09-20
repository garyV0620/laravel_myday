#Myday App 
This App is like a twitter that you can post and comments on each post. 

##About
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
-Clone repository using GIT
    $ git clone https://github.com/garyV0620/laravel_myday.git
    $ cd laravel_myday
-Run composer install on your cmd or terminal.
-Run npm install on your terminal.
-Copy .env.example file to .env on the root folder. You can type copy .env.example .env if using command prompt Windows or cp .env.example .env if using terminal Ubuntu.
-Edit .env change all Database settings
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=laravel
    DB_USERNAME=root
    DB_PASSWORD=
    
    or you can use sqlite by commenting all on the top from your .env then use
    DB_CONNECTION=sqlite

-Generate an app encryption key by running php artisan key:generate.
-Run php artisan migrate. (if sqlite is use type 'yes' if prompt to create file)
-To Run the APP
    -Run npm run dev. (if you want to use custom host and port Run npm run dev -- --host=yourhostORIp --port=yourPort)
    -Open another terminal
    -Run php artisan serve. (if you want to use custom host and port Run php artisan serve --host=yourhostORIp --port=yourPort)(do not use same port on npm run dev)
    -Open another terminal then Run php artisan websocket:init 
    -Then check INFO  Server running on [http://127.0.0.1:8001].
    -Open http://127.0.0.1:8001 on your browser.
-Enjoy using the APP




