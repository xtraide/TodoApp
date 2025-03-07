# TodoApp

TodoApp est une application de gestion de tâches développée avec le framework Symfony.

## Prérequis

- PHP 8.0 ou supérieur
- Composer
- Un serveur web (Apache, Nginx, etc.)
- Une base de données (MySQL, PostgreSQL, etc.)

## Installation

1. Clonez le dépôt :

    ```sh
    git clone https://github.com/xtraide/TodoApp.git
    cd todoapp
    ```

2. Installez les dépendances avec Composer :

    ```sh
    composer install
    ```

3. Configurez les variables d'environnement en copiant le fichier `.env` :

    ```sh
    cp .env .env.local
    ```

    Modifiez le fichier `.env.local` pour configurer votre base de données et d'autres paramètres.

4. Créez la base de données et exécutez les migrations :

    ```sh
    php bin/console doctrine:database:create
    php bin/console doctrine:migrations:migrate
    ```

## Utilisation

1. Démarrez le serveur de développement Symfony :

    ```sh
    php bin/console server:run
    ```

2. Accédez à l'application dans votre navigateur à l'adresse [http://localhost:8000](http://localhost:8000).

## Routes

### TaskController

- `GET /task` : Affiche toutes les tâches avec un filtre (toutes, complétées, en attente).
- `GET, POST /task/new` : Crée une nouvelle tâche.
- `GET /task/{id}` : Affiche une tâche spécifique.
- `GET, POST /task/{id}/edit` : Édite une tâche existante.
- `POST /task/{id}` : Supprime une tâche.

### Entité Task

- `GET /api/tasks` : Récupère la liste des tâches.
- `POST /api/tasks` : Crée une nouvelle tâche.
- `GET /api/tasks/{id}` : Récupère une tâche spécifique.
- `PUT /api/tasks/{id}` : Met à jour une tâche spécifique.
- `DELETE /api/tasks/{id}` : Supprime une tâche spécifique.
