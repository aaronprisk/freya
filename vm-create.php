<?php
// START CREATE SESSION
session_start();

// INCLUDE CONNECT FILE
include 'connect.php';

// SET MESSAGE VAR
$message = '';

// SET POST VARIABLES
$name = escapeshellarg($_POST['hostname']);
$arch = escapeshellarg("");
$memory = escapeshellarg($_POST['memory']);
$maxmemMB = escapeshellarg($_POST['memory']);
$vcpus = escapeshellarg($_POST['cpus']);
$newdisk = escapeshellarg($_POST['newdisk']);
$iso_image = escapeshellarg($_POST['iso']);
$network = escapeshellarg($_POST['network']);

// CHECK FOR DISK CREATION
if (isset($_POST['existdisk']) && $_POST['newdisk'] == "")  {
    echo "Using existing disk...";
    $disk=escapeshellarg($_POST['existdisk']);
    echo $disk;
}

if ($_POST['newdisk'] != "") {
    echo "Creating new disk...";
    $disk='/var/lib/libvirt/images/' . $name . '.qcow2,size=' . $newdisk . ' ';
    echo $disk;
}

// CHECK FOR BACKUP CONFIRMATION
if (isset($_POST['backup'])) {
    $backup = "true";
} else {
    $backup = "false";
}


// CREATE VM DOMAIN USING USER INPUT
$command = shell_exec('
virt-install \
             --connect qemu:///system \
             --virt-type kvm \
             --name ' . $name . '\
             --ram ' . $memory . '\
             --vcpus ' . $vcpus . '\
             --disk path=' . $disk . '\
             --vnc \
             --cdrom ' . $iso_image . ' \
             --network network=' . $network . ' \
             --metadata description=' . $backup . ' \
             --osinfo detect=on \
> /dev/null 2>/dev/null &');

// ECHO COMMAND
// echo "<pre>$command</pre>";

// CREATE INITIAL SCREENSHOT FROM SEIDR-THUMB
shell_exec('/opt/seidr/seidr-thumb.sh');

//$_SESSION['message'] = $message;
header("Location: machines.php");
?>
