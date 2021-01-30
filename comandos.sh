composer install
sudo php bin/console cache:clear --env=prod
sudo php bin/console cache:clear --env=dev
sudo php bin/console doctrine:schema:update --force
sudo chown www-data:www-data var/ -R
