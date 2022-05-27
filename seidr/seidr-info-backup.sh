#!/bin/bash
# Seidr Info Backup
# Gathers backup information for Freya Backup page

# BACKUP LOCATION
backup_dir=/mnt/backups/

# Set Total Backup Variable
tback = 0;

# Check for VMs with backup flag enabled and return size data
for VMS in $(virsh -c qemu:///system list --name --all);
do
    if [ $(virsh -c qemu:///system dumpxml $VMS | grep description) = "<description>true</description>" ]; then
        echo "<tr><td><a href='vm.php?vmid=$VMS'</a>$VMS</td>"
        echo "<td>"
        ls -l --b=G /var/lib/libvirt/images/$VMS.qcow2 | cut -d " " -f5
        disk=$(ls -l --b=G /var/lib/libvirt/images/$VMS.qcow2 | cut -d " " -f5 | grep -Eo '[0-9]{1,4}')
        tback=$((tback+$disk))
        echo "</td></tr>"
    fi
done

echo "</tbody></table>"
echo "<hr>"
echo "<strong>Total Backup Size:</strong> $tback GB"
