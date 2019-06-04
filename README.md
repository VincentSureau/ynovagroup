# YNOVAGROUP
## Le groupement de pharmaciens indépendants

### Démarrer le projet
Créer un fichier .env  puis y coller le contenu du fichier .env.dist  en y renseignant les données de connexion à la base de données.


### Terminal:
- Aller dans le répertoire du projet et taper:
```composer install```

- Ouvrir un nouvel onglet:
```php bin/console server:run```


Si problème d'actualisation suite à modification html/css:
- Effectuer cette commande dans l'onglet principal:
```php bin/console cache:clear```