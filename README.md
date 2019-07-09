# YNOVAGROUP
## Le groupement de pharmaciens indépendants

### Démarrer le projet
Créer un fichier .env.local  puis y coller le contenu du fichier .env.dist  en y renseignant les données de connexion à la base de données.

### Terminal:
- Aller dans le répertoire du projet et taper:
```composer install```

- Créer la base de donnée :
```bin/console doctrine:database:create```
```bin/console doctrine:migrations:migrate```

- Remplir la base de donnée à partir des fixtures :
```bin/console doctrine:fixtures:load```

- Ouvrir un nouvel onglet:
```php bin/console server:run```


😇 Si problème d'actualisation suite à modification html/css:
- Effectuer cette commande dans l'onglet principal:
```php bin/console cache:clear```

😱 Et si rien ne va plus... :
- Effectuer les commandes suivantes :
```php bin/console doc:data:drop --force
php bin/console doc:data:create
php bin/console doc:schema:update --force
php bin/console doc:fix:lo```

messages
<i class="fas fa-envelope-open-text"></i>

fichiers 
<i class="fas fa-file-alt"></i>

articles
<i class="far fa-newspaper"></i>

