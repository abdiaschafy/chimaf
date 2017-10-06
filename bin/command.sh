#composer update
php bin/console doctrine:database:drop --force
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force

php bin/console doctrine:fixtures:load -n
php bin/console assetic:dump
php bin/console assets:install --symlink