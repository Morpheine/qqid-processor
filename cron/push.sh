#!/bin/bash -l
File="$1"
Command="lftp -c 'open -u scsbbuser,N0t4Any12C! sftp://142.150.183.147; put -O ~ /var/www/html/applications/qqid/cron/"
Command+="$File"
Command+=";echo uploaded successfully'"
eval "$Command"

