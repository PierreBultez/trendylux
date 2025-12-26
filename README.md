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

*Généré automatiquement le 26/12/2025.*
