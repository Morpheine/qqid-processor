#!/bin/bash

function makeBackup() {
    DateTimeStamp=$(date '+%d_%m_%y_%H_%M')
    Original=$1
    FileName=$(basename $Original)
    Directory=$(dirname $Original)
    cp $Original ${Directory}/backup/${FileName}_${DateTimeStamp}
    echo "Backup generated at: ${Directory}/backup/${FileName}_${DateTimeStamp}"
}

File="$1"
makeBackup /var/www/html/applications/qqid/cron/imports/$File