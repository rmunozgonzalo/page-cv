cd /var/www/html/page-cv
sudo chown admin:admin var/ -R
sudo chown admin:admin public/upload/images/ -R
git pull origin main
sudo chown admin:admin /var/www/html/page-cv -R
composer install
php bin/console cache:clear --env=prod
php bin/console cache:clear --env=dev
php bin/console doctrine:schema:update --force
sudo chown www-data:www-data var/ -R
sudo chown www-data:www-data public/upload/images/ -R
