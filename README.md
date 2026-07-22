# Malaysia States Directory

A PHP web app that queries a MySQL database and displays a styled directory of all 14 Malaysian states (*negeri*).

<img width="600" alt="image" src="https://github.com/user-attachments/assets/91aecb68-b0d3-4eb1-affc-6a3bb81273e9" />


## Overview

- **Language:** PHP 8.0+ with `mysqli`
- **Database:** MySQL (remote or local)
- **Server:** PHP built-in development server
- **Frontend:** Vanilla HTML/CSS (no framework)

The app connects to the `negeri` table in the `malaysia` database and renders the results in a responsive HTML table with a navigation bar and footer.

## Project Structure

```text
index.php       # Main PHP application (DB query + HTML output)
setup.sh        # Setup and launch script (installs deps, starts server)
.env            # Optional — local DB credentials (not committed)
```

## Quick Start

Run the setup script — it will detect or install PHP with `mysqli`, then start the server:

```sh
bash setup.sh
```

Then open your browser at:

```
http://localhost:8080
```

To use a custom port:

```sh
PORT=9000 bash setup.sh
```

## Database Configuration

The app reads connection settings from environment variables or a `.env` file in the project root. Create `.env` to override the defaults:

```ini
DB_HOST=127.0.0.1
DB_USER=root
DB_PASSWORD=rahsia
DB_NAME=malaysia
```

| Variable | Default | Description |
|---|---|---|
| `DB_HOST` | `188.166.208.200` | MySQL host |
| `DB_USER` | `root` | MySQL username |
| `DB_PASSWORD` | `rahsia` | MySQL password |
| `DB_NAME` | `malaysia` | Database name |

## Database Schema

The app expects a `negeri` table in the configured database:

```sql
CREATE TABLE negeri (
  id   INT AUTO_INCREMENT PRIMARY KEY,
  nama TEXT
);
```

## Requirements

- PHP 8.0 or higher
- `mysqli` extension (installed automatically by `setup.sh` on Debian/Ubuntu/macOS)
- Access to a MySQL instance with the `malaysia` database

