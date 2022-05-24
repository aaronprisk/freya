#!/bin/bash
# Seidr Thumbnailer
# Service that takes routine snapshots of running KVM domains to use as thumbnails in Freya interface

for VMS in $(virsh list --name);
do
    mkdir /var/www/html/thumbs/$VMS
    virsh screenshot $VMS /var/www/html/thumbs/$VMS/$VMS.ppm
    convert /var/www/html/thumbs/$VMS/$VMS.ppm /var/www/html/thumbs/$VMS/$VMS.png
done
