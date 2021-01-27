## Install

* `mkdir weather_history; cd weather_history`
* `git clone https://github.com/orm-backend/orm-backend/weather-rpc.git .`
* `compoer install`

## Run

* Edit .env to connect your DB
* Cache the configuration by running `php artisan config:cache`
* Ensure DB connection established `php artisan doctrine:schema:validate`
* Create tables `php artisan doctrine:schema:update`
* Insert test data to DB `php artisan seed`
* Perform testing `php artisan test`
* Start development server `php artisan serve`
