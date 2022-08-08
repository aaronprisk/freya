#!/bin/bash
# Seidr Thumbnailer
# Service that takes routine snapshots of running KVM domains to use as thumbnails in Freya interface

for VMS in $(virsh -c qemu:///system list --name);
    if [ ! -d /var/www/html/thumbs/$VMS ]
    then
        mkdir /var/www/html/thumbs/$VMS
    fi
    virsh -c qemu:///system screenshot $VMS /var/www/html/thumbs/$VMS/$VMS.ppm
    convert /var/www/html/thumbs/$VMS/$VMS.ppm /var/www/html/thumbs/$VMS/$VMS.png
done
