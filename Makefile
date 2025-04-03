# WikiRadio - Makefile
.PHONY: help install start stop restart build clear-cache test lint fix-cs db-create db-drop db-migrate db-fixtures db-reset security docker-up docker-down phpcs phpstan phpmd phpcpd psalm php-metrics before-commit pest

# Colors
GREEN = \033[0;32m
YELLOW = \033[0;33m
RED = \033[0;31m
NC = \033[0m

# Variables
SYMFONY = symfony
CONSOLE = $(SYMFONY) console
COMPOSER = composer
PHP = php
NPM = npm
DOCKER_COMPOSE = docker compose
DOCKER_RUN = docker run
PHPQA = jakzal/phpqa:php8.2
PHPQA_RUN = $(DOCKER_RUN) --init --rm -v $(PWD):/project -w /project $(PHPQA)
PEST = ./vendor/bin/pest

# Default target
.DEFAULT_GOAL = help

## —— 📚 Help ——————————————————————————————————————————————
help: ## Affiche cette aide
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

## —— 🔧 Installation ——————————————————————————————————————
install: ## Installe les dépendances du projet
	@echo "$(GREEN)Installation des dépendances...$(NC)"
	$(COMPOSER) install
	@if [ -f package.json ]; then \
		echo "$(GREEN)Installation des dépendances Node.js...$(NC)"; \
		$(NPM) install; \
	fi

## —— 🚀 Serveur de développement ——————————————————————————
start: ## Démarre le serveur de développement Symfony
	$(SYMFONY) serve -d
	$(MAKE) docker-up

stop: ## Arrête le serveur de développement Symfony
	$(SYMFONY) server:stop
	$(MAKE) docker-down

restart: stop start ## Redémarre le serveur de développement Symfony

## —— 🏗️ Compilation des assets ———————————————————————————
build: ## Compile les assets (si Webpack Encore est installé)
	@if [ -f package.json ] && grep -q "encore" package.json; then \
		$(NPM) run build; \
	else \
		echo "$(YELLOW)Webpack Encore n'est pas installé.$(NC)"; \
	fi

## —— 🧹 Cache & Logs ——————————————————————————————————————
clear-cache: ## Vide le cache
	$(CONSOLE) cache:clear

cc: clear-cache ## Alias pour clear-cache

## —— ✅ Tests & Qualité ———————————————————————————————————
test: ## Exécute les tests
	@if [ -d "tests" ]; then \
		$(PHP) bin/phpunit; \
	else \
		echo "$(YELLOW)Aucun test trouvé.$(NC)"; \
	fi

pest: ## Exécute les tests avec Pest
	@if [ -f "$(PEST)" ]; then \
		$(PEST) --colors=always; \
	else \
		echo "$(YELLOW)Pest n'est pas installé. Installation...$(NC)"; \
		$(COMPOSER) remove phpunit/phpunit; \
		$(COMPOSER) require pestphp/pest --dev --with-all-dependencies; \
		$(PEST) --init; \
		echo "$(GREEN)Pest a été installé. Vous pouvez maintenant écrire vos tests.$(NC)"; \
	fi

## —— 🔍 Analyse de qualité (PHPQA) ——————————————————————————
phpcs: ## Vérifie le style du code avec PHP_CodeSniffer
	@echo "$(GREEN)Vérification du style du code avec PHP_CodeSniffer...$(NC)"
	$(PHPQA_RUN) phpcs --standard=PSR12 src

phpcbf: ## Corrige le style du code avec PHP_CodeSniffer
	@echo "$(GREEN)Correction du style du code avec PHP_CodeSniffer...$(NC)"
	$(PHPQA_RUN) phpcbf --standard=PSR12 src

phpstan: ## Analyse statique du code avec PHPStan
	@echo "$(GREEN)Analyse statique du code avec PHPStan...$(NC)"
	$(PHPQA_RUN) phpstan analyse -l 5 src

phpmd: ## Détecte les problèmes potentiels avec PHP Mess Detector
	@echo "$(GREEN)Détection des problèmes potentiels avec PHP Mess Detector...$(NC)"
	$(PHPQA_RUN) phpmd src text cleancode,codesize,controversial,design,naming,unusedcode --ignore-violations-on-exit

phpcpd: ## Détecte le code dupliqué avec PHP Copy/Paste Detector
	@echo "$(GREEN)Détection du code dupliqué avec PHP Copy/Paste Detector...$(NC)"
	$(PHPQA_RUN) phpcpd src

psalm: ## Analyse statique du code avec Psalm
	@echo "$(GREEN)Analyse statique du code avec Psalm...$(NC)"
	@if [ ! -f psalm.xml ]; then \
		echo "$(YELLOW)Initialisation de Psalm...$(NC)"; \
		$(PHPQA_RUN) psalm --init src 3; \
	fi
	$(PHPQA_RUN) psalm --show-info=true

php-metrics: ## Génère des métriques de qualité de code avec PHP Metrics
	@echo "$(GREEN)Génération des métriques de qualité de code avec PHP Metrics...$(NC)"
	$(PHPQA_RUN) phpmetrics --report-html=var/phpmetrics src

lint: phpcs phpstan ## Exécute les vérifications de qualité de code principales

fix-cs: phpcbf ## Corrige les problèmes de style de code

qa-all: phpcs phpstan phpmd phpcpd psalm php-metrics ## Exécute toutes les analyses de qualité

## —— 🚀 Pre-commit ——————————————————————————————————————————
before-commit: ## Exécute toutes les vérifications nécessaires avant un commit
	@echo "$(GREEN)🔍 Exécution des vérifications pré-commit...$(NC)"
	@echo "$(YELLOW)Vérification des dépendances vulnérables...$(NC)"
	$(COMPOSER) audit
	@echo "$(YELLOW)Vérification des failles de sécurité...$(NC)"
	$(PHPQA_RUN) local-php-security-checker
	@echo "$(YELLOW)Vérification de la syntaxe des fichiers PHP...$(NC)"
	$(PHPQA_RUN) parallel-lint --exclude vendor --exclude var .
	@echo "$(YELLOW)Vérification du style de code (PSR-12)...$(NC)"
	$(PHPQA_RUN) phpcs --standard=PSR12 src
	@echo "$(YELLOW)Analyse statique du code (PHPStan niveau 5)...$(NC)"
	$(PHPQA_RUN) phpstan analyse -l 5 src
	@echo "$(YELLOW)Analyse statique du code (Psalm)...$(NC)"
	@if [ ! -f psalm.xml ]; then \
		echo "$(YELLOW)Initialisation de Psalm...$(NC)"; \
		$(PHPQA_RUN) psalm --init src 3; \
	fi
	$(PHPQA_RUN) psalm --show-info=false
	@echo "$(YELLOW)Vérification des problèmes potentiels (PHPMD)...$(NC)"
	$(PHPQA_RUN) phpmd src text cleancode,codesize,controversial,design,naming,unusedcode --ignore-violations-on-exit
	@echo "$(YELLOW)Détection du code dupliqué...$(NC)"
	$(PHPQA_RUN) phpcpd src
	@echo "$(YELLOW)Vérification des règles de codage (PSR-12)...$(NC)"
	$(PHPQA_RUN) phpcs --standard=PSR12 src || true
	@echo "$(YELLOW)Vérification des tests unitaires...$(NC)"
	@if [ -d "tests" ]; then \
		if [ -f "$(PEST)" ]; then \
			echo "$(YELLOW)Exécution des tests avec Pest...$(NC)"; \
			$(PEST) --colors=always || true; \
		else \
			echo "$(YELLOW)Exécution des tests avec PHPUnit...$(NC)"; \
			$(PHP) bin/phpunit --testdox || true; \
		fi; \
	else \
		echo "$(YELLOW)Aucun test trouvé.$(NC)"; \
	fi
	@echo "$(YELLOW)Vérification des fichiers Twig...$(NC)"
	$(CONSOLE) lint:twig templates || true
	@echo "$(YELLOW)Vérification des fichiers YAML...$(NC)"
	$(CONSOLE) lint:yaml config || true
	@echo "$(YELLOW)Vérification des fichiers de conteneur...$(NC)"
	$(CONSOLE) lint:container || true
	@echo "$(GREEN)✅ Toutes les vérifications sont terminées !$(NC)"
	@echo "$(GREEN)👉 Vous pouvez maintenant commit et push votre code.$(NC)"

## —— 🗃️ Base de données ————————————————————————————————————
db-create: ## Crée la base de données
	$(CONSOLE) doctrine:database:create --if-not-exists

db-drop: ## Supprime la base de données
	$(CONSOLE) doctrine:database:drop --force --if-exists

db-migrate: ## Exécute les migrations
	$(CONSOLE) doctrine:migrations:migrate --no-interaction

db-fixtures: ## Charge les fixtures
	$(CONSOLE) doctrine:fixtures:load --no-interaction

db-reset: db-drop db-create db-migrate db-fixtures ## Réinitialise la base de données

## —— 🔒 Sécurité ———————————————————————————————————————————
security: ## Vérifie les vulnérabilités de sécurité
	$(COMPOSER) audit
	$(PHPQA_RUN) local-php-security-checker

## —— 📦 Dépendances ——————————————————————————————————————
check-deps: ## Vérifie les dépendances obsolètes
	$(COMPOSER) outdated

update-deps: ## Met à jour les dépendances
	$(COMPOSER) update

## —— 🐳 Docker ——————————————————————————————————————————
docker-up: ## Démarre les conteneurs Docker
	@echo "$(GREEN)Démarrage des conteneurs Docker...$(NC)"
	$(DOCKER_COMPOSE) -f compose.yaml up -d

docker-down: ## Arrête les conteneurs Docker
	@echo "$(GREEN)Arrêt des conteneurs Docker...$(NC)"
	$(DOCKER_COMPOSE) -f compose.yaml down

docker-logs: ## Affiche les logs des conteneurs Docker
	@echo "$(GREEN)Affichage des logs Docker...$(NC)"
	$(DOCKER_COMPOSE) -f compose.yaml logs -f

docker-ps: ## Liste les conteneurs Docker en cours d'exécution
	@echo "$(GREEN)Liste des conteneurs Docker...$(NC)"
	$(DOCKER_COMPOSE) -f compose.yaml ps

docker-status-frankenphp: ## Vérifie si FrankenPHP fonctionne correctement
	@echo "$(GREEN)Vérification du statut de FrankenPHP...$(NC)"
	@if [ "$$($(DOCKER_COMPOSE) -f compose.yaml ps -q frankenphp 2>/dev/null)" ]; then \
		if [ "$$(docker inspect -f {{.State.Running}} $$($(DOCKER_COMPOSE) -f compose.yaml ps -q frankenphp))" = "true" ]; then \
			echo "$(GREEN)✅ FrankenPHP est en cours d'exécution$(NC)"; \
			echo "$(GREEN)📊 Informations sur le conteneur:$(NC)"; \
			docker inspect $$($(DOCKER_COMPOSE) -f compose.yaml ps -q frankenphp) | grep -E '"IPAddress"|"Status"|"StartedAt"|"Image"|"Name"' | sed 's/,//g' | sed 's/"//g' | sed 's/^ *//g'; \
			echo "$(GREEN)🌐 Accès à l'application:$(NC)"; \
			echo "   - HTTP: http://localhost:8080"; \
			echo "$(GREEN)📋 Logs récents:$(NC)"; \
			$(DOCKER_COMPOSE) -f compose.yaml logs --tail=10 frankenphp; \
			echo "$(GREEN)💻 Pour voir tous les logs: make docker-logs$(NC)"; \
		else \
			echo "$(RED)❌ FrankenPHP est arrêté$(NC)"; \
		fi; \
	else \
		echo "$(RED)❌ Le conteneur FrankenPHP n'existe pas$(NC)"; \
		echo "$(YELLOW)Démarrez les conteneurs avec: make docker-up$(NC)"; \
	fi

docker-logs-frankenphp: ## Affiche les logs de FrankenPHP
	@echo "$(GREEN)Affichage des logs de FrankenPHP...$(NC)"
	$(DOCKER_COMPOSE) -f compose.yaml logs -f frankenphp

docker-symfony-debug: ## Affiche les informations de débogage Symfony dans FrankenPHP
	@echo "$(GREEN)Affichage des informations de débogage Symfony...$(NC)"
	$(DOCKER_COMPOSE) -f compose.yaml exec frankenphp bin/console debug:router
	@echo "\n$(GREEN)Informations sur le cache:$(NC)"
	$(DOCKER_COMPOSE) -f compose.yaml exec frankenphp bin/console cache:pool:list
	@echo "\n$(GREEN)Informations sur le serveur:$(NC)"
	$(DOCKER_COMPOSE) -f compose.yaml exec frankenphp php -i | grep -E 'PHP Version|memory_limit|display_errors|error_reporting|date.timezone|opcache'

docker-exec-php: ## Exécute un shell dans le conteneur PHP
	@echo "$(GREEN)Connexion au conteneur FrankenPHP...$(NC)"
	$(DOCKER_COMPOSE) -f compose.yaml exec frankenphp sh

docker-exec-db: ## Exécute un shell dans le conteneur MySQL
	@echo "$(GREEN)Connexion au conteneur MySQL...$(NC)"
	$(DOCKER_COMPOSE) -f compose.yaml exec database bash

docker-build: ## Reconstruit les images Docker
	@echo "$(GREEN)Reconstruction des images Docker...$(NC)"
	$(DOCKER_COMPOSE) -f compose.yaml build

docker-restart: docker-down docker-up ## Redémarre les conteneurs Docker

docker-env: ## Copie le fichier .env.docker vers .env.local pour utiliser la configuration Docker
	@echo "$(GREEN)Configuration de l'environnement Docker...$(NC)"
	cp .env.docker .env.local
	@echo "$(GREEN)Fichier .env.local créé avec la configuration Docker.$(NC)"

## —— 🧰 Outils utiles ——————————————————————————————————————
validate-schema: ## Valide le schéma de la base de données
	$(CONSOLE) doctrine:schema:validate

routes: ## Liste toutes les routes de l'application
	$(CONSOLE) debug:router

services: ## Liste tous les services de l'application
	$(CONSOLE) debug:container

translations: ## Liste toutes les traductions de l'application (nécessite symfony/translation)
	@if $(CONSOLE) list | grep -q "debug:translation"; then \
		$(CONSOLE) debug:translation fr; \
	else \
		echo "$(YELLOW)La commande debug:translation n'est pas disponible. Installez symfony/translation-bundle avec:$(NC)"; \
		echo "$(GREEN)composer require symfony/translation$(NC)"; \
	fi