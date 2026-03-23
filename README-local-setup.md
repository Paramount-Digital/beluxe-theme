# Beluxe Theme — Local Development Setup

Local WordPress environment using Docker. The theme directory is mounted directly into the container, so file edits are reflected instantly.

## Services

| Service    | URL                      | Purpose              |
|------------|--------------------------|----------------------|
| WordPress  | http://localhost:8080    | Main site            |
| phpMyAdmin | http://localhost:8889    | Database management  |
| MySQL      | localhost:3306           | Database (direct)    |

## Credentials

Defined in `.env`:

| Field    | Value          |
|----------|----------------|
| DB Name  | beluxe_wp      |
| DB User  | beluxe_user    |
| DB Pass  | beluxe_pass    |
| Root Pass| rootpassword   |

## Quick Start

```bash
# Start all services
docker compose up -d

# Check status
docker compose ps

# View WordPress logs
docker compose logs -f wordpress

# Stop (preserves data)
docker compose down

# Stop and delete all data (fresh start)
docker compose down -v
```

## Importing the Kinsta .sql Database

### Option A: phpMyAdmin (recommended for files < 512MB)

1. Open http://localhost:8889
2. Log in — Server: `db`, User: `root`, Password: `rootpassword`
3. Select the `beluxe_wp` database from the left sidebar
4. Click the **Import** tab
5. Choose your `.sql` file and click **Go**

### Option B: MySQL CLI (for large files)

```bash
# Copy the SQL file into the running db container
docker cp /path/to/your-export.sql $(docker compose ps -q db):/tmp/import.sql

# Import it
docker compose exec db mysql -u root -prootpassword beluxe_wp < /tmp/import.sql
```

Or pipe directly:

```bash
docker compose exec -T db mysql -u root -prootpassword beluxe_wp < /path/to/your-export.sql
```

## Update Site URL After Import

The Kinsta export will have the production URL in `wp_options`. You must update it to `localhost:8080`.

### Option A: phpMyAdmin

1. Open http://localhost:8889, select `beluxe_wp`
2. Open the `wp_options` table
3. Find and edit these two rows:
   - `siteurl` → `http://localhost:8080`
   - `home` → `http://localhost:8080`

### Option B: WP-CLI

```bash
# Start the WP-CLI service
docker compose --profile tools up -d wpcli

# Replace production URL with local URL (update the production domain below)
docker compose exec wpcli wp search-replace 'https://your-production-domain.com' 'http://localhost:8080' --all-tables

# Verify
docker compose exec wpcli wp option get siteurl
docker compose exec wpcli wp option get home
```

### Option C: Direct SQL

```sql
UPDATE wp_options SET option_value = 'http://localhost:8080' WHERE option_name IN ('siteurl', 'home');
```

Run this in phpMyAdmin's SQL tab on the `beluxe_wp` database.

## Install Parent Theme (GeneratePress) and Activate

### Option A: WP-CLI

```bash
# Start the WP-CLI service
docker compose --profile tools up -d wpcli

# Install GeneratePress parent theme
docker compose exec wpcli wp theme install generatepress --activate

# Activate beluxe child theme
docker compose exec wpcli wp theme activate beluxe-theme
```

### Option B: WordPress Admin

1. Go to http://localhost:8080/wp-admin/
2. Navigate to **Appearance → Themes → Add New**
3. Search for **GeneratePress**, click **Install**, then **Activate**
4. Go back to **Appearance → Themes**, find **Beluxe Theme**, click **Activate**

## Theme Volume Mount

The `docker-compose.yml` mounts this repo directory directly into the container at:

```
./  →  /var/www/html/wp-content/themes/beluxe-theme
```

Any file you edit locally is immediately available in WordPress — no rebuild or restart needed. Just refresh the browser.

## Troubleshooting

**"Error establishing a database connection"**
- Wait 10-15 seconds after `docker compose up` — MySQL takes time to initialize on first run.
- Check logs: `docker compose logs db`

**White screen / 500 error after DB import**
- Make sure you updated `siteurl` and `home` in `wp_options`.
- Check if GeneratePress parent theme is installed.

**phpMyAdmin upload too large**
- `UPLOAD_LIMIT` is set to 512MB. For larger files, use the MySQL CLI import method above.

**Permission issues with theme files**
- Run: `docker compose exec wordpress chown -R www-data:www-data /var/www/html/wp-content/themes/beluxe-theme`
