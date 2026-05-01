# Rezilio Back

API REST du projet Rezilio — suivi de conformité NIS2.

Construit avec **Symfony 7.2** + **API Platform 4** + **Doctrine ORM** + **PostgreSQL**.

## Stack technique

- PHP 8.3
- Symfony 7.2
- API Platform 4
- Doctrine ORM
- PostgreSQL 16
- LexikJWTAuthenticationBundle (authentification JWT)
- NelmioApiDocBundle (documentation Swagger)

## Installation

```bash
composer install
cp .env .env.local
# Éditer .env.local avec vos paramètres BDD
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console lexik:jwt:generate-keypair
symfony server:start
```

## Structure

```
src/
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
```

## Endpoints principaux

| Méthode | Endpoint | Description |
|---|---|---|
| POST | `/api/auth/login` | Authentification JWT |
| GET | `/api/mesures` | Liste des mesures NIS2 |
| GET | `/api/mesures/{id}` | Détail d'une mesure |
| PATCH | `/api/mesures/{id}` | Mise à jour statut |
| GET | `/api/risques` | Liste des risques |
| GET | `/api/incidents` | Liste des incidents |
| POST | `/api/incidents` | Déclarer un incident |
| GET | `/api/fournisseurs` | Liste des fournisseurs |
| GET | `/api/conformite/score` | Score global NIS2 |

## Documentation API

Disponible sur `http://localhost:8000/api` (Swagger UI intégré API Platform).

## Variables d'environnement

```env
DATABASE_URL="postgresql://app:password@127.0.0.1:5432/rezilio?serverVersion=16&charset=utf8"
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=your_passphrase
CORS_ALLOW_ORIGIN=http://localhost:3000
```
