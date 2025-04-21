#!/bin/bash
# php artisan octane:frankenphp --port=8000 --host=0.0.0.0
#php artisan serve --port=8000 --host=0.0.0.0
php artisan octane:start --server=swoole --host=0.0.0.0 --port=8000