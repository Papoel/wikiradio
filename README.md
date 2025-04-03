# WikiRadio

<div align="center">
  <img src="https://img.shields.io/badge/Symfony-7.2-green" alt="Symfony 7.2">
  <img src="https://img.shields.io/badge/PHP-8.2+-blue" alt="PHP 8.2+">
  <img src="https://img.shields.io/badge/License-Proprietary-red" alt="License">
  <img src="https://img.shields.io/badge/Docker-Ready-blue" alt="Docker Ready">
  <img src="https://img.shields.io/badge/FrankenPHP-Powered-purple" alt="FrankenPHP">
</div>

<div align="center">
  <h3>Système de gestion des archives radiographiques pour centrales nucléaires</h3>
</div>

---

## 📋 Table des Matières

- [Présentation](#présentation)
- [Fonctionnalités](#fonctionnalités)
- [Technologies](#technologies)
- [Prérequis](#prérequis)
- [Installation](#installation)
- [Utilisation](#utilisation)
- [Structure du Projet](#structure-du-projet)
- [Tests](#tests)
- [Contribution](#contribution)
- [Licence](#licence)
- [Contact](#contact)

## 🔍 Présentation

**WikiRadio** est une application web moderne conçue pour la gestion des archives de films radiographiques dans les centrales nucléaires. Cette plateforme permet de suivre efficacement l'inventaire, les emprunts et les restitutions des boîtes de radiogrammes, assurant ainsi une traçabilité complète des films radiographiques.

## ✨ Fonctionnalités

- **Gestion complète des archives** : Opérations CRUD (Création, Lecture, Mise à jour, Suppression) sur la base de données des films radiographiques
- **Suivi par QR Code** : Traçabilité des sorties et restitutions de boîtes de radiogrammes via système de QR Code
- **Système de notifications** : Envoi automatique d'emails de relance lorsqu'une boîte est empruntée au-delà d'une durée configurable
- **Tableau de bord** : Vue d'ensemble des archives disponibles et empruntées
- **Gestion des utilisateurs** : Contrôle des accès et des permissions pour le personnel autorisé
- **Historique des mouvements** : Journalisation complète des emprunts et restitutions pour audit

## 🛠️ Technologies

- **Backend** : Symfony 7.2, PHP 8.2+
- **Base de données** : MySQL 8.0
- **Cache** : Redis
- **Serveur Web** : FrankenPHP (basé sur Caddy)
- **Conteneurisation** : Docker & Docker Compose
- **Outils de développement** : Makefile, Composer, Pest/PHPUnit
- **Qualité de code** : PHPStan, Psalm, PHP_CodeSniffer (PSR-12)
- **Emails** : Intégration avec Mailhog pour les tests

## 📋 Prérequis

- Docker et Docker Compose
- Make (pour utiliser le Makefile)
- Git

## 🚀 Installation

1. Clonez le dépôt :

```bash
git clone https://github.com/votre-organisation/wikiradio.git
cd wikiradio
```

2. Lancez l'environnement Docker :

```bash
make docker-up
```

3. Installez les dépendances :

```bash
make install
```

4. Configurez la base de données :

```bash
make db-setup
```

5. Accédez à l'application :

```
http://localhost:8080
```

## 💻 Utilisation

Le Makefile fournit de nombreuses commandes utiles pour le développement :

```bash
# Démarrer l'environnement Docker
make docker-up

# Arrêter l'environnement Docker
make docker-down

# Vérifier le statut de FrankenPHP
make docker-status-frankenphp

# Voir les logs de FrankenPHP
make docker-logs-frankenphp

# Exécuter les tests
make tests

# Vérifier la qualité du code avant un commit
make before-commit
```

## 📁 Structure du Projet

```text
wikiradio/
├── bin/                  # Exécutables et scripts
├── config/               # Configuration de l'application
├── data/                 # Donnés d'entrée brut 
│   ├── TR1TRAVEE1/       # Films radiographiques de la tranche 1 dans la travée 1
│     ├── Fichier xls     # Données brut des films radiographiques à importer dans la BDD.
│   │── TR1TRAVEE2/       # Films radiographiques de la tranche 1 dans la travée 2
│   │── TR1TRAVEE3/       # Films radiographiques de la tranche 1 dans la travée 3
│   │── TR1TRAVEE4/       # Films radiographiques de la tranche 1 dans la travée 4
├── docker/               # Configuration Docker
├── public/               # Point d'entrée web
├── src/                  # Code source de l'application
│   ├── Controller/       # Contrôleurs
│   ├── Entity/           # Entités et modèles
│   ├── Repository/       # Accès aux données
│   └── Service/          # Services métier
├── templates/            # Templates Twig
├── tests/                # Tests automatisés
├── var/                  # Fichiers temporaires et cache
├── vendor/               # Dépendances (géré par Composer)
├── .env                  # Variables d'environnement
├── composer.json         # Gestion des dépendances
├── docker-compose.yml    # Configuration Docker Compose
└── Makefile              # Automatisation des tâches
```

## 🧪 Tests

Le projet utilise Pest, un framework de tests moderne pour PHP :

```bash
# Exécuter tous les tests
make tests

# Exécuter uniquement les tests unitaires
./vendor/bin/pest --group=unit

# Exécuter uniquement les tests fonctionnels
./vendor/bin/pest --group=feature
```

## 👥 Contribution

Les contributions sont les bienvenues ! Pour contribuer :

1. Forkez le projet
2. Créez une branche pour votre fonctionnalité (`git checkout -b feature/amazing-feature`)
3. Committez vos changements (`git commit -m 'Add some amazing feature'`)
4. Poussez vers la branche (`git push origin feature/amazing-feature`)
5. Ouvrez une Pull Request

Veuillez vous assurer que votre code respecte les normes de qualité en exécutant `make before-commit` avant de soumettre votre contribution.

## 📄 Licence

Ce projet est sous licence propriétaire. Tous droits réservés.

## 📞 Contact

Pour toute question ou suggestion, n'hésitez pas à nous contacter :

- **Email** : <contact@example.com>
- **Site Web** : <https://www.example.com>
- **GitHub** : <https://github.com/votre-organisation>

---

<div align="center">
  <p>Développé avec ❤️ par Votre Équipe</p>
</div>
