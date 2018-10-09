chimaf
======

A Symfony project created on September 23, 2017, 2:48 pm.

Comment installer le projet
---
> le cloner sur votre pc dans un répertoire de votre serveur web
> installer composer de façon globale sur votre pc: http://webdevzoom.com/how-to-install-composer-on-windows/#installing-composer

> A l'aide d'une invite de commande, se déplacer dans le répertoire de votre projet
Exécutez les commandes:
- composer install
- _./bin/console assets:install --symlink_
- _./bin/console assetic:dump_
- Si la base de données existe: _./bin/console doctrine:database:drop --force_
- Create database: _./bin/console doctrine:database:create_
- update database:  _./bin/console doctrine:schema:update --force_
- peupler la base de donnée: _./bin/console doctrine:fixtures:load -n_

accéder à l'application: _localhost/web/app_dev.php_
