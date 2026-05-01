# Rezilio Back

API REST du projet Rezilio — suivi de conformité NIS2.

Construit avec **Symfony 7.4** + **API Platform 4** + **Doctrine ORM** + **PostgreSQL 16**.

## Stack technique

| Composant | Version |
|---|---|
| PHP | 8.3 |
| Symfony | 7.4 (LTS) |
| API Platform | 4.x |
| Doctrine ORM | 3.x |
| PostgreSQL | 16 |
| LexikJWTAuthenticationBundle | 3.x |
| NelmioCorsBundle | 2.x |

## Prérequis

- PHP 8.3+
- Composer 2
- PostgreSQL 16
- [Symfony CLI](https://symfony.com/download) (recommandé)

## Installation

```bash
git clone https://github.com/Rezilio/back.git
cd back
composer install
cp .env .env.local
# Éditer .env.local avec vos paramètres BDD et JWT
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console lexik:jwt:generate-keypair
symfony server:start
```

## Structure

```
src/
├── Controller/       # Contrôleurs custom (ex: ConformiteController)
├── Entity/           # Entités Doctrine (NIS2)
│   ├── User.php
│   ├── MesureConformite.php
│   ├── Risque.php
│   ├── Incident.php
│   └── Fournisseur.php
├── Repository/       # Repositories Doctrine
├── State/            # State Providers/Processors API Platform 4
├── Security/         # Voters, Guards
├── EventListener/    # Listeners Symfony
└── DataFixtures/     # Fixtures de démonstration
tests/
├── bootstrap.php
└── ...               # Tests fonctionnels et unitaires
```

## Endpoints principaux

| Méthode | Endpoint | Description | Sécurité |
|---|---|---|---|
| POST | `/api/auth/login` | Authentification JWT | Public |
| GET | `/api/mesures` | Liste des mesures NIS2 | JWT |
| GET | `/api/mesures/{id}` | Détail d'une mesure | JWT |
| PATCH | `/api/mesures/{id}` | Mise à jour statut | RSSI / Admin |
| GET | `/api/risques` | Liste des risques | JWT |
| POST | `/api/risques` | Créer un risque | JWT |
| GET | `/api/incidents` | Liste des incidents | JWT |
| POST | `/api/incidents` | Déclarer un incident | JWT |
| GET | `/api/fournisseurs` | Liste des fournisseurs | JWT |
| GET | `/api/conformite/score` | Score global NIS2 | JWT |

## Modules NIS2 couverts

| Code | Module |
|---|---|
| `gouvernance` | Gouvernance et politique de sécurité |
| `gestion_risques` | Gestion des risques |
| `incidents` | Gestion et notification des incidents |
| `supply_chain` | Sécurité de la chaîne d'approvisionnement |
| `continuite` | Continuité d'activité |
| `cryptographie` | Cryptographie et chiffrement |
| `controle_acces` | Contrôle d'accès |
| `vulnerabilites` | Gestion des vulnérabilités |

## Documentation API

Disponible sur `http://localhost:8000/api` (Swagger UI intégré API Platform 4).

## Tests

```bash
php bin/phpunit
```

## Variables d'environnement

```env
DATABASE_URL="postgresql://app:password@127.0.0.1:5432/rezilio?serverVersion=16&charset=utf8"
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=your_passphrase
JWT_TTL=3600
CORS_ALLOW_ORIGIN='^https?://(localhost|127\.0\.0\.1)(:[0-9]+)?\$'
```

## CI/CD

Le pipeline GitHub Actions (`.github/workflows/ci.yml`) exécute à chaque push :
- `composer audit` — audit des dépendances
- `php-cs-fixer` — style de code
- `phpstan` — analyse statique
- Tests PHPUnit sur PostgreSQL 16
