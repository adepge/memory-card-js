#!/bin/bash

# Wait for MySQL to be ready
until mysql -h db -u ecm1417 -pWebDev2021 -e "SELECT 1"; do
  echo "Waiting for MySQL to be ready..."
  sleep 1
done

# Run the SQL script to set up the database and tables
mysql -h db -u ecm1417 -pWebDev2021 pairs_game < /var/www/html/setup_database.sql

echo "Database and tables created successfully!"