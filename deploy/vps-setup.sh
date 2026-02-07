#!/bin/bash
# VPS Setup Script for Laravel E-Commerce
# Run this script on the VPS as root

echo "=== Starting VPS Setup for Laravel E-Commerce ==="

# Update system
echo "=== Updating System Packages ==="
apt update && apt upgrade -y

# Install required packages
echo "=== Installing PHP 8.2 and Extensions ==="
apt install -y software-properties-common
add-apt-repository -y ppa:ondrej/php
apt update
apt install -y php8.2 php8.2-fpm php8.2-cli php8.2-common php8.2-pgsql \
    php8.2-mbstring php8.2-xml php8.2-curl php8.2-zip php8.2-gd \
    php8.2-bcmath php8.2-tokenizer php8.2-fileinfo php8.2-intl php8.2-redis

# Install Composer
echo "=== Installing Composer ==="
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer

# Install Nginx
echo "=== Installing Nginx ==="
apt install -y nginx

# Install Node.js
echo "=== Installing Node.js 20 LTS ==="
curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
apt install -y nodejs

# Install Git
echo "=== Installing Git ==="
apt install -y git

# Configure firewall
echo "=== Configuring Firewall ==="
ufw allow OpenSSH
ufw allow 'Nginx Full'
ufw --force enable

# Create web directory
echo "=== Creating Application Directory ==="
mkdir -p /var/www/ecommerce
chown -R www-data:www-data /var/www/ecommerce

# Clone repository
echo "=== Cloning Repository ==="
cd /var/www/ecommerce
git clone git@github.com:intelliflowpvtltd/E-Commerce-Portal.git .

# Set permissions
chown -R www-data:www-data /var/www/ecommerce
chmod -R 755 /var/www/ecommerce
chmod -R 775 /var/www/ecommerce/storage
chmod -R 775 /var/www/ecommerce/bootstrap/cache

echo "=== VPS Setup Complete ==="
echo "Next steps:"
echo "1. Configure .env file"
echo "2. Run: composer install --optimize-autoloader --no-dev"
echo "3. Run: php artisan key:generate"
echo "4. Run: php artisan migrate --force"
echo "5. Configure Nginx virtual host"
echo "6. Install SSL certificate"
