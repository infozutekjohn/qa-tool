# Development Docker Setup
Use this for local development:
```bash
docker compose build --no-cache
docker compose up -d
docker compose exec -it app php artisan migrate
npm run dev
```
Open in browser:
```bash
http://localhost:8000
```

---

# Production Deployment (AWS Lightsail – Ubuntu 24.04)
This guide explains how to deploy the QA Tool to a production Lightsail instance using Docker.

## Create Lightsail Instance & Network
- Create a Lightsail instance
 - OS: Ubuntu 24.04
- Open firewall ports:
 - 22 – SSH
 - 80 – HTTP
 - 443 – HTTPS (optional)
- Allocate and attach a Static IP to the instance.
- Confirm public IP:
```bash
curl -s ifconfig.me && echo
```
## SSH & Install Docker
```bash
ssh ubuntu@<STATIC_IP>

sudo apt update
sudo apt install -y docker.io docker-compose-plugin
sudo systemctl enable --now docker

sudo usermod -aG docker $USER
newgrp docker

docker --version
docker compose version
```

## Free Port 80 (If Needed)
If required the docker to connect to port 80 by default. Usually apache are automatically connected to port 80, so we need to remove that
```bash
sudo ss -ltnp | grep ':80' || true
sudo systemctl stop apache2 2>/dev/null || true
sudo systemctl disable apache2 2>/dev/null || true
sudo ss -ltnp | grep ':80' || true
```

## Put Project on Server
```bash
git clone <REPO_URL> qa_tool
cd qa_tool
```

## Required Production Files
Create the file compose.prod.yml
```bash
services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    env_file:
      - .env
    environment:
      APP_ENV: production
      LOG_CHANNEL: stderr
      DB_HOST: db
      ALLURE_INTERNAL_BASE_URL: http://allure:5050
      ALLURE_PUBLIC_BASE_URL: http://<STATIC_IP>/allure
    depends_on:
      - db
      - allure
    expose:
      - "8080"
    restart: unless-stopped

  queue:
    build:
      context: .
      dockerfile: Dockerfile
    env_file:
      - .env
    environment:
      APP_ENV: production
      LOG_CHANNEL: stderr
      DB_HOST: db
      LOG_LEVEL: info
    depends_on:
      - db
      - allure
    command: php artisan queue:work --verbose --tries=1 --timeout=300
    restart: unless-stopped

  web:
    image: nginx:1.27
    ports:
      - "80:80"
    volumes:
      - ./docker/nginx.prod.conf:/etc/nginx/conf.d/default.conf:ro
    depends_on:
      - app
    restart: unless-stopped

  db:
    image: mysql:8.0
    environment:
      MYSQL_ROOT_PASSWORD: rootpass
      MYSQL_DATABASE: test_tool
      MYSQL_USER: user
      MYSQL_PASSWORD: pass
    volumes:
      - db_data:/var/lib/mysql
    restart: unless-stopped

  allure:
    image: frankescobar/allure-docker-service
    environment:
      CHECK_RESULTS_EVERY_SECONDS: "NONE"
      KEEP_HISTORY: "1"
    volumes:
      - allure_projects:/app/projects
    restart: unless-stopped

volumes:
  allure_projects:
  db_data:
```

@@ .env (Production)
Create the env file and must exist in project root. Make sure to update these fields
```bash
APP_ENV=production
APP_DEBUG=false
APP_URL=http://<STATIC_IP_OR_DOMAIN>

DB_HOST=db

ALLURE_INTERNAL_BASE_URL=http://allure:5050
```

## Generate nginx.prod.conf
Create the file docker/nginx.prod.conf
```bash
server {
    listen 80;
    server_name _;

    client_max_body_size 50m;

    location / {
        proxy_pass http://app:8080;
        proxy_http_version 1.1;

        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;

        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
    }

    location /allure/ {
        proxy_pass http://allure:5050/;
        proxy_http_version 1.1;
        proxy_set_header Host $host;
    }
}
```

## Build & Start Production Stack
```bash
docker compose -f compose.prod.yml up -d --build
docker compose -f compose.prod.yml ps
```
Verify nginx config:
```bash
docker compose -f compose.prod.yml exec web nginx -T | sed -n '1,200p'
```

## Laravel Bootstrapping (First Deploy Only)
```bash
docker compose -f compose.prod.yml exec app php artisan key:generate --force
docker compose -f compose.prod.yml exec app php artisan migrate --force
docker compose -f compose.prod.yml exec app php artisan queue:table
docker compose -f compose.prod.yml exec app php artisan migrate --force
```

## Queue Worker Sanity Checks
```bash
docker compose -f compose.prod.yml exec app php artisan config:clear
docker compose -f compose.prod.yml exec queue php artisan config:clear
docker compose -f compose.prod.yml restart queue

docker compose -f compose.prod.yml logs -f --tail=200 queue
```

## Connectivity Verification
Inside server:
```bash
curl -I http://127.0.0.1
curl -I http://localhost
```
From your browser:
-http://<STATIC_IP>/
-http://<STATIC_IP>/allure/

## Update / Redeploy Workflow
```bash
cd /home/ubuntu/qa_tool
git pull
docker compose -f compose.prod.yml up -d --build
```
