## Quick start (Docker)

- Requirements: Docker Desktop (or Docker Engine) and Docker Compose.
- Run:
  - Linux/macOS/WSL:
    ```bash
    docker compose up -d --build
    ```
  - Windows (PowerShell):
    ```powershell
    docker compose up -d --build
    ```

App will be available at:
- http://localhost:8000

The container will:
- Generate APP_KEY and JWT secret if missing
- Run database migrations and seeders

## Default admin

- Email: admin@admin.com
- Password: password

Created by the seeder on first boot.

## Database

- MySQL service runs inside Docker
- Connection (from the app): host `db`, port `3306`, database `laravel`, user `laravel`, password `laravel`

If you need to connect from your host with a GUI, add a port mapping under `db` in `docker-compose.yml`, for example:
```yaml
ports:
  - "3307:3306"
```
Then connect to 127.0.0.1:3307.

## Postman collection

A ready-made collection is included:
- `storage/postman/api_collection.json`
- It uses `{{base_url}}` = `http://localhost:8000`
