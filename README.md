# LaravelBreeze-WithMysqlEncrypted

**LaravelBreeze-WithMysqlEncrypted** est un projet basé sur Laravel qui utilise le chiffrement MySQL pour sécuriser les informations sensibles telles que les adresses e-mail et les mots de passe. Ce projet est conçu pour démontrer l'intégration du chiffrement MySQL avec Laravel Breeze et la manière dont les données sont protégées à l'aide de la méthode `AES_ENCRYPT` et `AES_DECRYPT`.

## Fonctionnalités

-   Utilisation de Laravel Breeze pour les fonctionnalités d'authentification.
-   Chiffrement des colonnes `email` et `password` dans la table `users` à l'aide de MySQL.
-   Mutateurs et accesseurs personnalisés pour le chiffrement et le déchiffrement des données.

## Installation

### Prérequis

Assure-toi d'avoir les éléments suivants installés sur ton système :

-   PHP >= 8.1
-   Composer
-   MySQL
-   Node.js et NPM (pour les assets frontend)

### Cloner le dépôt

Clone le dépôt du projet depuis GitHub :

```bash
git clone https://github.com/ton-utilisateur/laravelbreeze-withmysqlencrypted.git
cd laravelbreeze-withmysqlencrypted
```

### Installer les dépendances PHP

Installe les dépendances PHP du projet avec Composer :

```bash
composer install
```

### Installer Laravel Breeze

Pour installer Laravel Breeze, exécute la commande suivante :

```bash
php artisan breeze:install
```

Cette commande va installer les vues d'authentification de base et les routes nécessaires. Ensuite, tu dois compiler les assets frontend :

```bash
npm install && npm run dev
```

### Configurer la base de données

Crée une base de données MySQL pour le projet, puis configure les paramètres de connexion dans le fichier `.env`. Voici un exemple de configuration :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
DB_ENCRYPTION_KEY=your_encrypted_key
```

### Exécuter les migrations

Applique les migrations pour créer les tables nécessaires dans la base de données :

```bash
php artisan migrate
```

### Lancer le serveur de développement

Démarre le serveur de développement Laravel :

```bash
php artisan serve
```

Le projet sera accessible à l'adresse [http://localhost:8000](http://localhost:8000).

## Fonctionnalités

### Modifications de la base de données

La migration pour la table `users` a été modifiée pour utiliser des colonnes de type BLOB pour les e-mails et les mots de passe. Voici la structure modifiée de la table :

```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->binary('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->binary('password');
    $table->rememberToken();
    $table->timestamps();
});
```

### Configuration de l'environnement

Ajoute la clé de chiffrement dans ton fichier `.env` :

```env
DB_ENCRYPTION_KEY=your_encrypted_key
```

### Modèle Eloquent

Le modèle `User` a été modifié pour inclure des mutateurs et des accesseurs pour le chiffrement et le déchiffrement :

```php
public function setEmailAttribute($value)
{
    $this->attributes['email'] = DB::raw("AES_ENCRYPT('{$value}', '" . env('DB_ENCRYPTION_KEY') . "')");
}

public function setPasswordAttribute($value)
{
    $this->attributes['password'] = DB::raw("AES_ENCRYPT('{$value}', '" . env('DB_ENCRYPTION_KEY') . "')");
}

public function getEmailAttribute($value)
{
    return DB::selectOne("SELECT AES_DECRYPT('{$value}', '" . env('DB_ENCRYPTION_KEY') . "') AS email")->email;
}

public function getPasswordAttribute($value)
{
    return DB::selectOne("SELECT AES_DECRYPT('{$value}', '" . env('DB_ENCRYPTION_KEY') . "') AS password")->password;
}
```

## Avantages et Inconvénients

### Avantages

-   **Sécurité renforcée :** Le chiffrement MySQL offre un niveau de sécurité pour les données sensibles stockées dans la base de données, en les protégeant contre les accès non autorisés.

### Inconvénients

-   **Complexité de mise en œuvre :** L'implémentation du chiffrement MySQL peut être complexe, surtout pour les débutants. Les mutateurs et accesseurs permettent de déchiffrer les données, mais cela nécessite des précautions supplémentaires pour s'assurer que les données sont correctement déchiffrées lors des requêtes.
-   **Chiffrement non transparent pour les autres requêtes :** Les données chiffrées doivent être explicitement déchiffrées avant de pouvoir être utilisées dans des requêtes autres que celles du modèle. Cela peut nécessiter des ajustements supplémentaires dans la logique de l'application.

## Observations

Le chiffrement direct avec MySQL est une solution de sécurité fiable, mais son implémentation peut être complexe pour les débutants. Les mutateurs et accesseurs permettent de gérer le chiffrement et le déchiffrement des données de manière pratique, mais il est important de rester vigilant pour s'assurer que toutes les opérations de lecture et d'écriture manipulent correctement les données chiffrées. Assure-toi de bien comprendre les implications du chiffrement sur les performances et la gestion des données dans ton application.

## Contribuer

Les contributions sont les bienvenues ! Si tu souhaites améliorer ce projet, n'hésite pas à soumettre des pull requests ou à ouvrir des issues.

## Licence

Ce projet est sous licence [MIT](LICENSE).
