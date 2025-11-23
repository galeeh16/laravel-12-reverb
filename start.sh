#!/bin/bash
# php 8.5
/opt/homebrew/opt/php@8.5/bin/php artisan octane:frankenphp --workers=100 --host=0.0.0.0 --port=4500 --max-requests=10000