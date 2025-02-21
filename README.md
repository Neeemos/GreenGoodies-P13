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

## API

| **Route**  | **Méthode** | **Entité Concernée** | **Action ou Relation** |
|------------|-------------|-----------------------|-------------------------|
| À compléter... |

---

## Prérequis

À compléter...

---

## Installation et Lancement

À compléter...

