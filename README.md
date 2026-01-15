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

---

# Production Deployment using Virtual Machine (Vultr Cloud Compute)
This guide explains how to deploy the QA Tool in production using Virtual Machine (Vultr Cloud Compute).
- Virtual Machine Configurations:
  - Infrastructure Details:
    - Cloud Provider: Vultr
    - Compute Type: SharedCPU
    - Data Center Location: Singapore
    - Operating System: Ubuntu 22.04 LTS (64-bit)
  - Allocated Resources:
    - CPU: 4 vCPUs
    - Memory: 8GB RAM
    - Storage: 160GB SSD
    - Bandwidth: 4TB Monthly Transfer
    - Cost: USD$40 per month
  - Timezone: Asia/Manila
  - Firewall: Uncomplicated Firewall (UFW) using Default Firewall Policy
    - Inbound traffic: Denied (default)
    - Outbound traffic: Allowed (default)
    - Allowed Services:
      - OpenSSH
      - HTTP (Apache)
      - HTTPS (Apache Secure)
    - ICMP Blocking
      - Action
        - Comment out ICMP rules for:
          - INPUT
          - FORWARD
    - Logging: Enabled (Medium Verbosity Level)
  - SSH Hardening
    - Root Login Disabled
    - User Access Restriction
  - Network and Diagnostic Utilities
  - Apache HTTP Server
      - Firewall Rules:
        - Allow
          - HTTP Traffic (port 80)
          - HTTPS Traffic (port 443)
      - Security Hardening
        - Server Token Configuration
        - Server Signature Disabled
      - Modules: 
        - [mod_proxy](https://httpd.apache.org/docs/current/mod/mod_proxy.html)
        - mod_rewrite
  - Composer: 2.2+
  - NodeJs: 24.12.0
  - NPM: 11.6+
  - PHP: 8.3+
    - Extensions: [Laravel Server Requirements](https://laravel.com/docs/12.x/deployment#server-requirements)
  - MySQL: 8.0+
  - Docker: 29.1+

## Create subdomains and update DNS
Create subdomains with "A" records pointing to Virtual Machine's IP:
- qa-tool.domain.com
- allure.domain.com

## Setup Virtual Hosts
1. Create a .conf file for qa-tool for http (port 80): `/etc/apache2/sites-available/qa-tool-domain-com.conf`:
```bash
<VirtualHost *:80>
    ServerName qa-tool.domain.com
    ServerAdmin dev@domain.com
    DocumentRoot /var/www/qa-tool-domain-com
    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
    RemoteIPHeader CF-Connecting-IP
</VirtualHost>
<Directory /var/www/qa-tool-domain-com>
    Options FollowSymLinks
    AllowOverride All
    Require all granted
    FileETag None
</Directory>
```  
2. Create a .conf file for qa-tool for https (port 443): `/etc/apache2/sites-available/qa-tool-domain-com-ssl.conf`:
```bash
<VirtualHost *:443>
    ServerName qa-tool.domain.com
    ServerAdmin dev@domain.com
    DocumentRoot /var/www/qa-tool-domain-com
    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
    RemoteIPHeader CF-Connecting-IP
    SSLEngine on
    SSLCertificateFile /etc/apache2/certs/domain_com.crt
    SSLCertificateKeyFile /etc/apache2/certs/domain_com.key
    SSLCertificateChainFile /etc/apache2/certs/cloudflareca.crt
</VirtualHost>
<Directory /var/www/qa-tool-domain-com>
    Options FollowSymLinks
    AllowOverride All
    Require all granted
    FileETag None
</Directory>
```  
3. Create a .conf file for allure for http (port 80): `/etc/apache2/sites-available/allure-domain-com.conf`:
```bash
<VirtualHost *:80>
    ServerName allure.domain.com
    # Optional: Redirect HTTP to HTTPS
    RewriteEngine On
    RewriteRule ^(.*)$ https://%{HTTP_HOST}$1 [R=301,L]
</VirtualHost>
```  
4. Create a .conf file for allure for https (port 443): `/etc/apache2/sites-available/allure-domain-com-ssl.conf`:
```bash
<VirtualHost *:443>
    ServerName allure.domain.com

    # SSL Configuration (replace with your certificate paths)
    SSLEngine on
    SSLCertificateFile /etc/apache2/certs/domain_com.crt
    SSLCertificateKeyFile /etc/apache2/certs/domain_com.key
    SSLCertificateChainFile /etc/apache2/certs/cloudflareca.crt

    ProxyRequests Off
    ProxyPreserveHost On

    # Configure the reverse proxy for the /allure path
    ProxyPass / http://localhost:5050/
    ProxyPassReverse / http://localhost:5050/

    # WebSocket proxy if needed
    ProxyPass /allure-ws ws://localhost:5050/allure-ws/
    ProxyPassReverse /allure-ws ws://localhost:5050/allure-ws/
</VirtualHost>
``` 
4. Enable sites
```bash
a2ensite qa-tool-domain-com.conf
a2ensite qa-tool-domain-com-ssl.conf
a2ensite allure-domain-com.conf
a2ensite allure-domain-com-ssl.conf
```
5. Restart Apache
```bash
systemctl restart apache2
``` 

## Clone Project
```bash
git clone <REPO_URL> qa-tool
cd qa-tool
```

## Create .env file from example
```bash
cp .env.example .env
```

## Setup MySQL
1. Create MySQL Database and User
  ```bash
  mysql -u root -p

  CREATE DATABASE <DB_DATABASE>;
  CREATE USER '<DB_USERNAME>'@'localhost' IDENTIFIED BY '<DB_PASSWORD>';
  GRANT CREATE, ALTER, DROP, INSERT, UPDATE, DELETE, SELECT, REFERENCES, RELOAD, SUPER on *.* TO '<DB_USERNAME>'@'localhost' WITH GRANT OPTION;
  FLUSH PRIVILEGES;
  ```
2. Update .env
  ```bash
  DB_HOST=localhost
  DB_DATABASE=<DB_DATABASE>
  DB_USERNAME=<DB_USERNAME>
  DB_PASSWORD=<DB_PASSWORD>
  ```

## Setup Allure
1. Run on docker
```bash
 sudo docker-compose up -d allure
```

2. Update .env
```bash
ALLURE_INTERNAL_BASE_URL=https://allure.domain.com
ALLURE_PUBLIC_BASE_URL=https://allure.domain.com
ALLURE_USE_LOCAL=true
```

## Build (First Deploy Only)
```bash
composer install
php artisan key:generate
php artisan migrate
npm install
npm run build
```

## Deploy (Re-deployment workflow)
1. If there are new updates, pull the changes and re-build
```bash
# go to directory
cd /home/user/qa-tool
# pull new updates
git pull
# re-build frontend files
npm run build
```
2. Copy to Virtual Host's DocumentRoot folder
```bash
# copy files
sudo cp -R * /var/www/qa-tool-domain-com/
# copy hidden files
sudo cp .env /var/www/qa-tool-domain-com/
sudo cp -R .git /var/www/qa-tool-domain-com/
# change ownership
sudo cd /var/www/
sudo chown -R www-data:www-data qa-tool-domain-com/
```

