# Nursify
Rebuild of the original Goodwork repo 
# Getting Started:

Create .env file - There is an example.env file you can copy paste. This file holds all environment variables

From the root directory (i.e the directory this file is in) open a terminal and enter:

``npm install``

``composer install``

We will need to create the Application Key for Laravel. From our terminal window:
``php artisan key:generate``

Migrate database

``php artisan migrate``

To run a migration and seed the data on a clean database use:

``php artisan migrate --seed``

To rebuild database from scratch and reseed:

``php artisan migrate:refresh --seed``

To run the server

``php artisan serve``

To run an individual seed generator use:

``php artisan db:seed --class=(CLASS NAME OF SEEDER)``

To truncate database and re-seed:

``php artisan seed:truncate``

To create an Enum use the following command (Replace UserType with the name of your Enum Type):
``php artisan make:enum UserType``

To run unit tests:
``php vendor/phpunit/phpunit/phpunit``

To run a specific unit test:
``php vendor/phpunit/phpunit/phpunit --filter (CLASS NAME OF UNIT TEST)``

To create new unit test: 
``php artisan make:test CourseTest --unit``

# JS and CSS Compilation
Javascript, CSS and Vue files are compiled/minified using WebPack.
To run WebPack - to build JS and Vue components for Dev or Prod environments
``npm run dev``
OR
``npm run production``

To auto build changes made to Vue components and JS
``npm run watch``
OR
``npm run watch-poll``

To generate Swagger docs, run the following command
``php artisan l5-swagger:generate``
This command generates a .json file found here:
``storage/api-docs/api-doc.json``

To check if the Swagger docs are correct, copy the contents of this file and paste it here

``https://editor.swagger.io/``

Once you are happy with changes to the Swagger Docs, copy the content of api-doc.json to albatross-golf-docs.json and view this url

``http://localhost:9000``

Flush laravel config cache, especially when changing .env file.
``php artisan cache:clear``
``php artisan config:clear``
``php artisan config:cache``
``php artisan view:clear``

Procedure to go live or staging.
1) Zip up app, resources, routes and any other folder changed other than storage, node_modules, public and vendor.
2) Upload to site using WHM and unzip
3) If you need to upload storage, only upload the child folders you need. For example there is no need to upload storage/logs, but you may have to upload storage/client.
4) Login server via SSL using putty and navigate to root directory
5) Run each command separately in this order

``php artisan config:clear``
``php artisan config:cache``
``php artisan cache:clear``
``php artisan view:clear``

6) Run migrate

``php artisan migrate``

7) Run dump autoloader

``composer dump-autoload``

8) Run npm update

``npm update``
``npm run production`` --Only run npm run production after VueJS work is complete otherwise site will crash.

9) Run composer update

``composer update --no-plugins --no-scripts``

10) To link the storage to public

``php artisan storage:link``





SideNotes

Install mongodb and redis
install laravel-echo-server or use npx and start it
use php artisan serve