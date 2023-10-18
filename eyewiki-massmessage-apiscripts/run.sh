#!/bin/bash

if [ ! -d "vendor" ]; then
  composer install --ignore-platform-reqs --no-scripts --no-interaction
fi

if [ ! -f "settings.php" ]; then
  echo "No settings.php file is present!"
  exit 1
fi

#echo "Checking if today is Friday..."
#if [ "$(date '+\%a')" = "Fri" ]; then
#  echo "It's Friday! Running the script..."
#  php MassMessageAPI.php
#else
#  echo "Nope, skipping..."
#fi

php MassMessageAPI.php
php stalepages.php
