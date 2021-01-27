## Install

* `mkdir weather_history; cd weather_history`
* `git clone https://github.com/orm-backend/orm-backend/weather-rpc.git .`
* `composer install`
* `cp .env.example .env`
* `php artisan key:generate`

## Run

* Edit .env to connect your DB
* Cache the configuration by running `php artisan config:cache`
* Ensure DB connection established `php artisan doctrine:schema:validate`
* Create tables `php artisan doctrine:schema:update`
* Insert test data to DB `php artisan db:seed`
* Perform testing `php artisan test`
* Start development server `php artisan serve`
