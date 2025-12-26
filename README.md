# 🛍️ TrendyLux Theme

![WordPress](https://img.shields.io/badge/WordPress-%23117AC9.svg?style=for-the-badge&logo=WordPress&logoColor=white)
![WooCommerce](https://img.shields.io/badge/WooCommerce-96588a?style=for-the-badge&logo=woocommerce&logoColor=white)
![Vite](https://img.shields.io/badge/vite-%23646CFF.svg?style=for-the-badge&logo=vite&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/tailwindcss-%2338B2AC.svg?style=for-the-badge&logo=tailwind-css&logoColor=white)
![Alpine.js](https://img.shields.io/badge/alpinejs-%238BC0D0.svg?style=for-the-badge&logo=alpine.js&logoColor=white)
![PHP](https://img.shields.io/badge/php-%23777BB4.svg?style=for-the-badge&logo=php&logoColor=white)

## 📖 À propos du projet

**TrendyLux** est un thème WordPress sur mesure dédié au e-commerce de luxe. Il est construit sur une architecture hybride moderne combinant la robustesse de **WooCommerce** avec la rapidité de développement frontend offerte par **Vite** et **TailwindCSS**.

Ce projet a été conçu pour offrir une performance optimale (chargement des assets via Vite) et une expérience utilisateur fluide (AlpineJS pour l'interactivité légère).

---

## ⚙️ Prérequis

Avant de commencer, assurez-vous d'avoir l'environnement suivant :

- **Node.js** (v18+ recommandé)
- **PHP** (v8.0+ recommandé)
- **WordPress** installé en local (via LocalWP, XAMPP, Docker, etc.)
- **Composer** (optionnel, si des paquets PHP sont ajoutés ultérieurement)

---

## 🚀 Installation & Démarrage

### 1. Cloner le projet
Placez-vous dans le dossier `wp-content/themes/` de votre installation WordPress :

```bash
cd /path/to/wordpress/wp-content/themes/
git clone <repository-url> trendylux
cd trendylux
```

### 2. Installer les dépendances JS
Le thème utilise NPM pour gérer les dépendances frontend (Tailwind, Alpine, Vite).

```bash
npm install
```

### 3. Activer le thème
Dans l'administration WordPress, allez dans **Apparence > Thèmes** et activez **TrendyLux**.

### 4. Lancer le serveur de développement
Pour travailler sur le thème avec le Hot Module Replacement (HMR) et la compilation Tailwind en temps réel :

```bash
npm run dev
```
> **Note :** Le serveur Vite tourne par défaut sur `http://localhost:5173`. Le thème est configuré pour charger les assets depuis ce serveur tant qu'il est actif. Un plugin Vite custom (`php-refresh`) recharge automatiquement la page lors de la modification de fichiers PHP.

---

## 📂 Architecture du Projet

Voici une vue d'ensemble de la structure des fichiers pour vous orienter rapidement :

```
trendylux/
├── src/                  # 🎨 Sources Frontend (JS, CSS non compilé)
│   ├── main.js           # Point d'entrée principal JS/CSS
│   ├── filters.js        # Logique JS spécifique aux filtres produits
│   └── style.css         # CSS global (directives Tailwind)
├── dist/                 # 📦 Assets compilés pour la prod (généré par build)
├── inc/                  # 🧠 Logique Backend (Classes PHP, Includes)
│   ├── class-trendylux-seo.php    # Gestion SEO custom
│   ├── cpt-faq.php                # Custom Post Type FAQ
│   └── admin/                     # Options du thème (Back-office)
├── woocommerce/          # 🛒 Surcharges des templates WooCommerce
├── public/               # 🖼️ Images statiques et assets publics
├── functions.php         # ⚡ Point d'entrée PHP (Enqueue scripts, Supports)
├── tailwind.config.js    # 🎨 Configuration TailwindCSS & DaisyUI
└── vite.config.js        # 🛠️ Configuration du bundler Vite
```

### Points d'entrée Frontend
- **`src/main.js`** : Importe AlpineJS, Tarteaucitron, et les styles globaux.
- **`src/filters.js`** : Gère la logique AJAX pour les filtres de produits WooCommerce.

### Logique Backend (`inc/`)
Toute la logique PHP complexe est déportée dans le dossier `inc/` pour garder `functions.php` propre.
- **Nav Walker** : Classes personnalisées pour le menu (`class-trendylux-nav-walker.php`).
- **Admin** : Pages d'options pour gérer le Mega Menu et les réglages généraux.

---

## 🛠️ Commandes Disponibles

| Commande | Description |
| :--- | :--- |
| `npm run dev` | Lance le serveur de dev (HMR, watch mode). Indispensable pour le dev CSS/JS. |
| `npm run build` | Compile les assets JS/CSS dans le dossier `dist/` pour la production. |
| `npm run preview` | Prévisualise le build de production localement. |

---

## 🚢 Déploiement / Mise en Production

Le thème utilise un fichier `manifest.json` généré par Vite pour mapper les fichiers sources vers leurs versions hashées en production.

1. **Compiler les assets** :
   Avant tout déploiement, il est impératif de lancer la compilation :
   ```bash
   npm run build
   ```
   Cela va générer/mettre à jour le dossier `dist/`.

2. **Déployer les fichiers** :
   Envoyez l'ensemble du dossier du thème (y compris `dist/` et `vendor/` si applicable, mais **sans** `node_modules` ni `src`) sur le serveur.

   > Une CI/CD est déjà configurée pour la pré-production dans `.github/workflows/deploy-preprod.yml`.

---

## 📝 Notes de Transmission (Handover)

- **Styles & UI** : Le projet utilise **TailwindCSS v4** avec **DaisyUI**. La plupart des styles sont des classes utilitaires. Vérifiez `tailwind.config.js` pour les extensions de thème.
- **WooCommerce** : De nombreux templates sont surchargés dans `woocommerce/`. Si vous devez modifier la fiche produit, regardez `woocommerce/single-product.php` et `content-product.php`.
- **Performance** : Les images statiques (logos, placeholders) sont dans `public/`.
- **Menu** : Le menu principal est géré via un Custom Walker PHP pour intégrer les classes Tailwind.

---

# Documentation de Passation du Serveur Trendylux.fr

## 1. Introduction

Ce document a pour but de faciliter la reprise de la maintenance du serveur hébergeant le site web [trendylux.fr](https://trendylux.fr). Il décrit l'architecture technique du serveur, les services en cours d'exécution et les procédures de maintenance de base.

Le serveur est une machine virtuelle sous environnement Linux Ubuntu.

Il est hébergé chez OVHCloud dans la catégorie "Bare Metal Cloud" et "Serveurs Privés Virtuels".

Le modèle de l'offre est VPS-2 6 vCores 12Go RAM 100Go SSD nvme. Il est dans la zone Region OpenStack: os-gra6 et localisé à Gravelines (GRA) - France.

Une option Backup automatisé Premium à été souscrite ainsi qu'une option snapshot qui permet de prendre une "image" à un instant T de l'entièreté du serveur.

Il n'y a pas d'engagement et la date de renouvellement est au 1er de chaque mois pour un montant de 12.00€ ttc par mois..

## 2. Accès au Serveur

L'accès au serveur se fait via SSH.

-   **Utilisateur non-root :** `pierre`
-   **Répertoire personnel :** `/home/pierre/`

Il est fortement recommandé de se connecter avec l'utilisateur `pierre` et d'utiliser `sudo` pour les opérations nécessitant des privilèges root. L'accès direct en `root` devrait être désactivé si ce n'est pas déjà le cas.

**Recommandation :** Assurez-vous d'utiliser un accès par clé SSH et de désactiver l'authentification par mot de passe pour des raisons de sécurité.

## 3. Caractéristiques du Système

### 3.1. Matériel

-   **Processeur (CPU) :** 6 Cores (Intel Core Processor Haswell)
-   **Mémoire vive (RAM) :** 12 Go
-   **Disque principal :** 96 Go d'espace total, 49 Go utilisés (51%) sur la partition `/`.

### 3.2. Système d'exploitation

-   **Distribution :** Ubuntu 25.10
-   **Noyau Linux :** 6.17.0-6-generic

### 3.3. Réseau

-   **Adresse IPv4 publique :** `37.59.109.245`
-   **Adresse IPv6 publique :** `2001:41d0:305:2100::b8fd`
-   **Pare-feu (Firewall) :** Un pare-feu est probablement actif (ports 80 et 443 ouverts). La commande `sudo ufw status` permettra de lister les règles actives.

## 4. Stack Applicative (trendylux.fr)

Le site web est basé sur le CMS **WordPress**.

### 4.1. Serveur Web

-   **Service :** Nginx
-   **Fichier de configuration principal du site :** `/etc/nginx/sites-enabled/prod.trendylux.fr`
-   **Répertoire racine du site (Web Root) :** `/var/www/trendylux.fr`
-   **Logs Nginx :** Les journaux d'accès et d'erreur se trouvent dans `/var/log/nginx/`.

### 4.2. Application

-   **Langage :** PHP
-   **Version :** 8.4.11
-   **Service PHP :** La communication entre Nginx et PHP se fait via le socket FPM `/run/php/php8.4-fpm.sock`.

### 4.3. Base de données

-   **Service :** MariaDB (compatible MySQL)
-   **Accès :** La base de données n'est accessible que localement (`localhost:3306`).
-   **Identifiants :** Les identifiants de connexion à la base de données se trouvent dans le fichier de configuration de WordPress : `/var/www/trendylux.fr/wp-config.php`.

### 4.4. Cache

-   **Cache d'objets :** Un service **Redis** est actif et écoute sur `127.0.0.1:6379`. Il est probablement utilisé par WordPress via un plugin de cache (ex: "Redis Object Cache") pour accélérer le site.
-   **Cache d'images :** La configuration Nginx inclut des règles pour servir des images aux formats **WebP** et **AVIF** via le plugin "Converter for Media", ce qui optimise le temps de chargement.

### 4.5. Certificat SSL/TLS

-   **Service :** Les certificats HTTPS sont fournis par **Let's Encrypt** et gérés via l'outil **Certbot**.
-   **Configuration :** Les chemins vers les certificats sont définis dans le fichier de configuration Nginx du site.
-   **Renouvellement :** Le renouvellement des certificats est normalement automatisé via une tâche `cron` ou un `timer` systemd installé par Certbot. Il peut être lancé manuellement avec `sudo certbot renew`.

## 5. Guide de Maintenance

### 5.1. Mises à jour du système

Les mises à jour des paquets Ubuntu s'effectuent avec les commandes standards :
```bash
sudo apt update
sudo apt upgrade
```

### 5.2. Gestion des services

Voici les commandes `systemd` pour gérer les services principaux :
```bash
# Pour Nginx
sudo systemctl status nginx
sudo systemctl restart nginx
sudo systemctl reload nginx # Recharger la configuration sans coupure

# Pour MariaDB
sudo systemctl status mariadb
sudo systemctl restart mariadb

# Pour PHP-FPM
sudo systemctl status php8.4-fpm
sudo systemctl restart php8.4-fpm
```

### 5.3. Sauvegardes (Backups)

Aucun système de sauvegarde personnalisé n'a été détecté via le `crontab` de l'utilisateur. Il est **impératif** de :
1.  Vérifier les cronjobs système (`/etc/crontab`, `/etc/cron.d/`).
2.  **Mettre en place une stratégie de sauvegarde robuste** pour les fichiers du site (`/var/www/trendylux.fr`) et la base de données (via un `mysqldump`).

Bonne continuation !

*Généré le 26/12/2025.*
