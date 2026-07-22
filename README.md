# Malaysia States Directory (PHP + MySQL + Docker)

Simple PHP web app that reads a MySQL table and displays a list of Malaysian states (negeri).

## Overview

- Backend: PHP with `mysqli`
- Database: MySQL
- Orchestration: Docker Compose
- Seed data: `mysql/negeri.sql`

The app queries the `negeri` table from database `malaysia` and renders the results as an HTML list.

## Project Structure

```text
docker-compose.yml      # Runs PHP-Apache and MySQL services
index.php               # Root PHP entrypoint (local/dev compatible)
mysql/
    negeri.sql            # DB schema + seed data for negeri table
php/
    src/
        index.php           # Docker-served web entrypoint
```

## Quick Start (Docker)

1. Start containers:

```sh
docker compose up -d
```

2. Open in browser:

```text
http://localhost:8090
```

3. Stop containers when done:

```sh
docker compose down
```

## Running Root Script Locally (Optional)

You can run the root script directly if you already have MySQL running:

```sh
php index.php
```

Optional environment variables for DB connection:

- `DB_HOST` (default: `localhost`)
- `DB_USER` (default: `root`)
- `DB_PASS` (default: `rahsia`)
- `DB_NAME` (default: `malaysia`)

Example:

```sh
DB_HOST=127.0.0.1 DB_USER=root DB_PASS=rahsia DB_NAME=malaysia php index.php
```

## Notes

- Docker app uses MySQL service host `db` inside the compose network.
- MySQL root password is configured in `docker-compose.yml`.
- Database data is persisted using the `mysql-php` named volume.


