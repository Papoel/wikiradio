# WikiRadio - Makefile
.PHONY: help install start stop restart build clear-cache test lint fix-cs db-create db-drop db-migration db-migrate db-fixtures db-entity db-reset security docker-up docker-down phpcs phpstan phpmd phpcpd psalm php-metrics before-commit pest

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
	$(PHPQA_RUN) phpcs src

phpcbf: ## Corrige le style du code avec PHP_CodeSniffer
	@echo "$(GREEN)Correction du style du code avec PHP_CodeSniffer...$(NC)"
	$(PHPQA_RUN) phpcbf src

phpstan: ## Analyse statique du code avec PHPStan
	@echo "$(GREEN)Analyse statique du code avec PHPStan...$(NC)"
	$(PHPQA_RUN) phpstan analyse -l max src

phpmd: ## Détecte les problèmes potentiels avec PHP Mess Detector
	@echo "$(GREEN)Détection des problèmes potentiels avec PHP Mess Detector...$(NC)"
	$(PHPQA_RUN) phpmd src text cleancode,codesize,controversial,design,naming,unusedcode --ignore-violations-on-exit

phpcpd: ## Détecte le code dupliqué avec PHP Copy/Paste Detector
	@echo "$(GREEN)Détection du code dupliqué avec PHP Copy/Paste Detector...$(NC)"
	$(PHPQA_RUN) phpcpd src

psalm: ## Exécute Psalm pour l'analyse statique du code
	@echo "$(GREEN)Analyse statique du code (Psalm)...$(NC)"
	@if [ ! -f psalm.xml ]; then \
		echo "$(YELLOW)Initialisation de Psalm...$(NC)"; \
		$(PHPQA_RUN) psalm --init src 3; \
	fi
	@$(PHPQA_RUN) psalm --show-info=false

php-metrics: ## Génère des métriques de qualité de code avec PHP Metrics
	@echo "$(GREEN)Génération des métriques de qualité de code avec PHP Metrics...$(NC)"
	$(PHPQA_RUN) phpmetrics --report-html=var/phpmetrics src

lint: phpcs phpstan ## Exécute les vérifications de qualité de code principales

fix-cs: phpcbf ## Corrige les problèmes de style de code

qa-all: phpcs phpstan phpmd phpcpd psalm php-metrics ## Exécute toutes les analyses de qualité

## —— 🚀 Pre-commit ——————————————————————————————————————————
before-commit: ## Exécute toutes les vérifications avant de commit
	@echo "$(GREEN)🔍 Exécution des vérifications pré-commit...$(NC)"
	@echo "$(YELLOW)Préparation du fichier TODO...$(NC)"
	@echo "# TODO avant de commit\n\nCe fichier a été généré automatiquement par \`make before-commit\` le $(shell date +%d-%m-%Y) à $(shell date +%Hh%M).\n\n## Liste des problèmes à corriger\n" > TODO-BEFORE-COMMIT.md
	@echo "$(YELLOW)Vérification des dépendances vulnérables...$(NC)"
	@$(COMPOSER) audit
	@echo "$(YELLOW)Vérification des failles de sécurité...$(NC)"
	@$(PHPQA_RUN) local-php-security-checker
	@echo "$(YELLOW)Vérification de la syntaxe des fichiers PHP...$(NC)"
	@$(PHPQA_RUN) parallel-lint --exclude vendor --exclude var .
	@echo "$(YELLOW)Correction automatique du style de code...$(NC)"
	@$(PHPQA_RUN) phpcbf src || true
	@echo "$(YELLOW)Vérification du style de code...$(NC)"
	@$(PHPQA_RUN) phpcs src > phpcs-output.tmp 2>&1 || true
	@if grep -q "ERROR\|WARNING" phpcs-output.tmp; then \
		echo "### Problèmes de style de code (PSR-12)\n" >> TODO-BEFORE-COMMIT.md; \
		grep -E "ERROR|WARNING" phpcs-output.tmp | sed 's/^/- [ ] /' >> TODO-BEFORE-COMMIT.md; \
		echo "\n" >> TODO-BEFORE-COMMIT.md; \
	fi
	@rm phpcs-output.tmp
	@echo "$(YELLOW)Analyse statique du code (PHPStan niveau max)...$(NC)"
	@$(PHPQA_RUN) phpstan analyse -l max src > phpstan-output.tmp 2>&1 || true
	@if grep -q "ERROR\|Line" phpstan-output.tmp; then \
		echo "### Problèmes détectés par PHPStan\n" >> TODO-BEFORE-COMMIT.md; \
		current_file=""; \
		while IFS= read -r line; do \
			if [[ $$line == *"Line   "* ]]; then \
				current_file=$$(echo "$$line" | awk '{print $$2}'); \
				echo "\n#### [$$current_file](src/$$current_file)\n" >> TODO-BEFORE-COMMIT.md; \
			elif [[ $$line =~ ^[[:space:]]*([0-9]+)[[:space:]]+(.*) ]]; then \
				line_num=$${BASH_REMATCH[1]}; \
				message=$${BASH_REMATCH[2]}; \
				echo "- [ ] [**Ligne $$line_num**](src/$$current_file#L$$line_num): $$message" >> TODO-BEFORE-COMMIT.md; \
				if grep -A 2 "$$line_num " phpstan-output.tmp | grep -q "never read, only written"; then \
					echo "  💡 [Documentation](https://phpstan.org/developing-extensions/always-read-written-properties)" >> TODO-BEFORE-COMMIT.md; \
				fi; \
				echo "" >> TODO-BEFORE-COMMIT.md; \
			fi; \
		done < phpstan-output.tmp; \
	fi
	@cat phpstan-output.tmp
	@rm phpstan-output.tmp
	@echo "$(YELLOW)Analyse statique du code avec Psalm...$(NC)"
	@if [ ! -f psalm.xml ]; then \
		echo "$(YELLOW)Initialisation de Psalm...$(NC)"; \
		$(PHPQA_RUN) psalm --init src 3; \
	fi
	@$(PHPQA_RUN) psalm --show-info=false > psalm-output.tmp 2>&1 || true
	@if grep -q "ERROR" psalm-output.tmp; then \
		echo "### Problèmes détectés par Psalm\n" >> TODO-BEFORE-COMMIT.md; \
		grep -E "ERROR:" psalm-output.tmp | sed 's/^/- [ ] /' >> TODO-BEFORE-COMMIT.md; \
		echo "\n" >> TODO-BEFORE-COMMIT.md; \
	fi
	@cat psalm-output.tmp
	@rm psalm-output.tmp
	@echo "$(YELLOW)Vérification des problèmes potentiels (PHPMD)...$(NC)"
	@$(PHPQA_RUN) phpmd src text cleancode,codesize,controversial,design,naming,unusedcode --ignore-violations-on-exit > phpmd-output.tmp 2>&1 || true
	@if [ -s phpmd-output.tmp ]; then \
		echo "### Problèmes détectés par PHPMD\n" >> TODO-BEFORE-COMMIT.md; \
		grep "/project/src/" phpmd-output.tmp | sed -E 's|/project/src/([^:]+):([0-9]+)[[:space:]]+([^[:space:]]+)[[:space:]]+(.+)|- [ ] [**\1:\2**](src/\1#L\2): \3 - \4|' >> TODO-BEFORE-COMMIT.md; \
	fi
	@cat phpmd-output.tmp
	@rm phpmd-output.tmp
	@echo "$(YELLOW)Détection du code dupliqué...$(NC)"
	@$(PHPQA_RUN) phpcpd src > phpcpd-output.tmp 2>&1 || true
	@if grep -q "Found" phpcpd-output.tmp; then \
		echo "### Code dupliqué détecté par PHPCPD\n" >> TODO-BEFORE-COMMIT.md; \
		echo "- [ ] **Duplication entre fichiers** :\n" >> TODO-BEFORE-COMMIT.md; \
		echo "  \`\`\`" >> TODO-BEFORE-COMMIT.md; \
		grep "Found" phpcpd-output.tmp >> TODO-BEFORE-COMMIT.md; \
		echo "" >> TODO-BEFORE-COMMIT.md; \
		cat phpcpd-output.tmp | grep "  - " | sed -E 's|  - /project/src/([^:]+):([0-9]+)-([0-9]+) \(([0-9]+) lines\)|  - [src/\1:\2-\3 (\4 lines)](src/\1#L\2-L\3)|' >> TODO-BEFORE-COMMIT.md; \
		echo "  \`\`\`" >> TODO-BEFORE-COMMIT.md; \
		echo "\n" >> TODO-BEFORE-COMMIT.md; \
	fi
	@cat phpcpd-output.tmp
	@rm phpcpd-output.tmp
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
	@echo "$(GREEN)📝 Un fichier TODO-BEFORE-COMMIT.md a été généré avec la liste des problèmes à corriger.$(NC)"
	@echo "$(GREEN)👉 Vous pouvez maintenant corriger ces problèmes avant de commit et push votre code.$(NC)"

## —— 🗃️ Base de données ————————————————————————————————————
db-create: ## Crée la base de données
	$(CONSOLE) doctrine:database:create --if-not-exists

db-drop: ## Supprime la base de données
	$(CONSOLE) doctrine:database:drop --force --if-exists

db-migration: ## Génère une migration
	$(CONSOLE) make:migration

db-migrate: ## Exécute les migrations
	$(CONSOLE) doctrine:migrations:migrate --no-interaction

db-fixtures: ## Charge les fixtures
	@if $(CONSOLE) list | grep -q "doctrine:fixtures:load"; then \
		if [ -d "src/DataFixtures" ] && [ "$$(find src/DataFixtures -name "*Fixture.php" | wc -l)" -gt 0 ]; then \
			echo "$(GREEN)🌱 Chargement des fixtures...$(NC)"; \
			$(CONSOLE) doctrine:fixtures:load --no-interaction; \
		else \
			echo "$(YELLOW)⚠️ Aucune fixture trouvée dans src/DataFixtures, étape ignorée.$(NC)"; \
		fi; \
	else \
		echo "$(YELLOW)⚠️ Le bundle DoctrineFixturesBundle n'est pas installé, étape ignorée.$(NC)"; \
	fi

db-entity: ## Génère les entités
	$(CONSOLE) make:entity

db-reset: db-drop db-create db-migrate ## Réinitialise la base de données
	@$(MAKE) db-fixtures

table-count: ## Affiche le nombre d'enregistrements dans une table
	@echo "$(GREEN)📊 Comptage des enregistrements dans une table...$(NC)"
	@TABLES=$$(docker exec wikiradio_mysql mysql -u root -proot wikiradio -e "SHOW TABLES;" | grep -v "Tables_in_" | sort); \
	if [ -z "$$TABLES" ]; then \
		echo "$(RED)❌ Aucune table trouvée dans la base de données.$(NC)"; \
		exit 1; \
	fi; \
	echo "$(YELLOW)Quelles tables voulez-vous compter ?$(NC)"; \
	INDEX=0; \
	for TABLE in $$TABLES; do \
		echo "[$${INDEX}] $${TABLE}"; \
		INDEX=$$((INDEX+1)); \
	done; \
	read -p "> " CHOICE; \
	INDEX=0; \
	for TABLE in $$TABLES; do \
		if [ "$$INDEX" = "$$CHOICE" ]; then \
			COUNT=$$(docker exec wikiradio_mysql mysql -u root -proot wikiradio -e "SELECT COUNT(*) FROM $${TABLE};" | grep -v "COUNT"); \
			echo "$(GREEN)> $${COUNT} enregistrements dans la table \"$${TABLE}\"$(NC)"; \
			break; \
		fi; \
		INDEX=$$((INDEX+1)); \
	done

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