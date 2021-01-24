composer install
php bin/console cache:clear --env=prod
php bin/console cache:clear --env=dev
php bin/console doctrine:schema:update --force
sudo chown www-data:www-data var/ -R
