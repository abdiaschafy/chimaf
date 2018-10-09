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
- ./bin/console assets:install --symlink
- ./bin/console assetic:dump
- Si la base de données existe: ./bin/console doctrine:database:drop --force
- Create database: ./bin/console doctrine:database:create
- update database:  ./bin/console doctrine:schema:update --force
- peupler la base de donnée: ./bin/console doctrine:fixtures:load -n

accéder à l'application: localhost/web/app_dev.php
