# GreenGoodies
## Site et API de GreenGoodies

Ce projet consiste à réaliser l'application **GreenGoodies** dans le cadre du projet n°13 d'OpenClassrooms.

---

## Entités et Relations

### **Utilisateurs (User)**

Représente les utilisateurs de l'application.

| Champ      | Type       | Description                                            |
|------------|------------|--------------------------------------------------------|
| `id`       | UUID       | Identifiant unique.                                    |
| `email`    | string     | Email unique de l'utilisateur.                        |
| `password` | string     | Mot de passe sécurisé (hashé).                      |
| `surname`  | string     | Nom de l'utilisateur.                                 |
| `name`     | string     | Prénom de l'utilisateur.                              |

---

### **Produits (Product)**

Contient les informations sur les produits.

| Champ         | Type               | Description                                           |
|--------------|--------------------|------------------------------------------------------|
| `id`        | UUID               | Identifiant unique.                                 |
| `name`      | string             | Nom du produit.                                     |
| `description` | string           | Description du produit.                            |
| `price`     | decimal(10,2)      | Prix du produit.                                   |
| `image`     | string             | URL ou nom d'une image affichée sur la fiche produit. |

---

### **Commandes (Order)**

Contient les informations des commandes passées sur le site.

| Champ      | Type           | Description                                    |
|------------|---------------|------------------------------------------------|
| `id`       | UUID          | Identifiant unique.                           |
| `user_id`  | UUID          | Identifiant unique de l'utilisateur.          |
| `price`    | decimal(10,2) | Prix total de la commande.                    |
| `date`     | date          | Date de passage de la commande.               |

**Relations** :
- Une commande est liée à un utilisateur (**ManyToOne**).

---
---

### **Liste commandes (order_product)**

Entité intermédiaire pour lier les produits aux commandes.

| Champ      | Type           | Description                                    |
|------------|---------------|------------------------------------------------|
| `id`       | UUID          | Identifiant unique.                           |
| `order_id`  | UUID          | Identifiant unique de la commande.          |
| `product_id`    | UUID | Identifiant unique d'un produit                  |
| `quantity`     | Decimal          | Quantité passer dans la commande pour le produit              |

**Relations** :
- Une entrée order_product est liée à une commande (ManyToOne avec Order).
- Une entrée order_product est liée à un produit (ManyToOne avec Product).
- Une commande peut contenir plusieurs produits (OneToMany avec order_product).
- Un produit peut être présent dans plusieurs commandes (OneToMany avec order_product).



---

## API

| **Route**  | **Méthode** | **Body/Auth** | **Reponse** | **Success code** | **Error code**
|------------|-------------|-----------|------------|------|------|
| /api/login      | POST          |  { "email" : "email@email.fr", "password": "password" }|{“token”: “...”}   | 200 | 401,403
| /api/products  | GET | Bearer Token : "..." | [ { "id": 41, "name": "..", "price": "1", "description": "..." },..]     | 200 | 401

---


## Prérequis

- Php.init
```bash
extension=openssl
```
- Openssl doit être accèssible en global


## Installation et Lancement

- Installer les dépendances avec Composer
```bash
composer install

```
- Configurer les variables d'environnement
```bash
# In all environments, the following files are loaded if they exist,
# the latter taking precedence over the former:
#
#  * .env                contains default values for the environment variables needed by the app
#  * .env.local          uncommitted file with local overrides
#  * .env.$APP_ENV       committed environment-specific defaults
#  * .env.$APP_ENV.local uncommitted environment-specific overrides
#
# Real environment variables win over .env files.
#
# DO NOT DEFINE PRODUCTION SECRETS IN THIS FILE NOR IN ANY OTHER COMMITTED FILES.
# https://symfony.com/doc/current/configuration/secrets.html
#
# Run "composer dump-env prod" to compile .env files for production use (requires symfony/flex >=1.2).
# https://symfony.com/doc/current/best_practices.html#use-environment-variables-for-infrastructure-configuration

###> symfony/framework-bundle ###
APP_ENV=dev
APP_SECRET=
###< symfony/framework-bundle ###

###> doctrine/doctrine-bundle ###
# Format described at https://www.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html#connecting-using-a-url
# IMPORTANT: You MUST configure your server version, either here or in config/packages/doctrine.yaml
#
# DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"
# DATABASE_URL="mysql://app:!ChangeMe!@127.0.0.1:3306/app?serverVersion=8.0.32&charset=utf8mb4"
# DATABASE_URL="mysql://app:!ChangeMe!@127.0.0.1:3306/app?serverVersion=10.11.2-MariaDB&charset=utf8mb4"
 DATABASE_URL="mysql://root:@127.0.0.1:3306/ggoodies?serverVersion=10.4.32-MariaDB&charset=utf8mb4"
###< doctrine/doctrine-bundle ###

###> symfony/messenger ###
# Choose one of the transports below
# MESSENGER_TRANSPORT_DSN=amqp://guest:guest@localhost:5672/%2f/messages
# MESSENGER_TRANSPORT_DSN=redis://localhost:6379/messages
MESSENGER_TRANSPORT_DSN=doctrine://default?auto_setup=0
###< symfony/messenger ###

###> symfony/mailer ###
MAILER_DSN=null://null
###< symfony/mailer ###

###> lexik/jwt-authentication-bundle ###
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=
###< lexik/jwt-authentication-bundle ###


```

-  Créer et mettre à jour la base de données
```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate

```

-  Créer la configuration SSL pour le JWT
```bash
php bin/console lexik:jwt:generate-keypai
```

-  Lancer le serveur Symfony
```bash
symfony serve -d

```

