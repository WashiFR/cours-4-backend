#!/bin/bash

echo "Exécution des tests PHPUnit..."
php bin/phpunit

echo "Exécution des tests fonctionnels Symfony..."
php bin/console lint:twig
php bin/console lint:yaml
php bin/console doctrine:schema:validate

echo "Tous les tests sont terminés."
