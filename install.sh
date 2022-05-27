#!/bin/bash
# Freya Installer
# Version 0.1

# Prompt for sudo privileges
if [ $EUID != 0 ]; then
    sudo "$0" "$@"
    exit $?
fi

echo "   ______                      "
echo "   / ____/_______  __  ______ _"
echo "  / /_  / ___/ _ \/ / / / __  /"
echo " / __/ / /  /  __/ /_/ / /_/ / "
echo "/_/   /_/   \___/\__, /\__,_/  "
echo "                /____/         "
echo
echo "Welcome to Freya!"
echo "Freya Server - Primary virtualization host and management server."
echo "Valkyrie Node - Secondary host to be managed by existing Freya Server."
echo "1) Freya Server"
echo "2) Valkyrie Node (Coming Soon)"
echo "---------------------------------"

while true; do
    read -p "Which server type do you wish to setup? (1 or 2?): " servtype
    case $servtype in
        [1]* ) echo "You chose Freya Server"; break;;
        [2]* ) echo "You chose Valkyire Server"; break;;
        * ) echo "Please answer 1 or 2.";;
    esac
done

echo
echo "Installer will now download required packages..."
sudo apt-get update >> /dev/null
sudo apt-get install git wget -y

echo "Installing web server stack..."
sudo apt-get install php apache2 libapache2-mod-php php-libvirt-php php-xml -y >> /dev/null

echo "Installing virtualization tools..."
sudo apt-get install bridge-utils cpu-checker libvirt-clients libvirt-daemon libvirt-daemon-system qemu qemu-kvm virtinst -y

echo "Downloading latest noVNC version..."
wget https://github.com/novnc/noVNC/archive/refs/tags/v1.3.0.tar.gz -P /tmp
tar -xvf /tmp/v1.3.0.tar.gz -C /tmp
sudo mkdir /var/www/html/noVNC
sudo cp -R /tmp/noVNC-1.3.0/* /var/www/html/noVNC/

echo "Creating service directories..."
sudo mkdir /opt/seidr >> /dev/null
sudo mkdir /mnt/backups >> /dev/null

echo "Copying server files to webroot..."
sudo cp -R * /var/www/html/ >> /dev/null
sudo mv /var/www/html/seidr/* /opt/seidr/
sudo rm -r /var/www/html/seidr
sudo rm /var/www/html/index.html

echo "Configuring user and group permissions..."
sudo chown -R www-data:www-data /var/www/html >> /dev/null
sudo chown -R www-data:www-data /opt/seidr >> /dev/null
sudo chown -R www-data:www-data /mnt/backups >> /dev/null

echo "Setting directory permissions..."
sudo adduser www-data libvirt >> /dev/null
sudo adduser www-data kvm >> /dev/null
sudo chmod -R 755 /var/lib/libvirt/images
sudo chmod +x /opt/seidr/seidr-info-backup.sh
sudo chmod +x /opt/seidr/seidr-thumb.sh

echo "Configuring libvirt..."
sudo sed -i 's/#listen_tcp = 1/listen_tcp = 1/g' /etc/libvirt/libvirtd.conf
sudo sed -i 's/#listen_addr = "192.168.0.1"/listen_addr = "0.0.0.0"/g' /etc/libvirt/libvirtd.conf

echo "Configuring PHP..."
sudo sed -i 's/post_max_size = 8M/post_max_size = 10000M/g' /etc/php/8.1/apache2/php.ini
sudo sed -i 's/upload_max_filesize = 2M/upload_max_filesize = 10000M/g' /etc/php/8.1/apache2/php.ini

echo "Restarting services..."
sudo systemctl restart libvirtd
sudo systemctl restart apache2

echo "---------------------------------"
while true; do
    read -p "To complete installation, this server needs to be rebooted. Reboot now?(y/n): " bootreq
    case $bootreq in
        [y]* ) sudo reboot; break;;
        [n]* ) echo "Please reboot prior to using Freya"; break;;
        * ) echo "Please answer y or n.";;
    esac
done
