#!/bin/bash

sshpass -p 333kaAm8C6xXD5N  ssh -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no connect_usr@134.209.160.243 "cd /var/www/connect.pwa4all.com/web/dashboard-api && git pull origin master && composer install"