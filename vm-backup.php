<?php
// START SESSION
session_start();

// LOGOUT REQUEST
if (isset($_POST["logout"])) { unset($_SESSION["user"]); }

// REDIRECT TO LOGIN PAGE IF NOT LOGGED IN
if (!isset($_SESSION["user"])) {
  header("Location: login.php");
  exit();
}

// INCLUDE CONNECT FILE
include 'connect.php';

// PULL VMID VARIABLE
$vmid = $_REQUEST['vmid'];

// CREATE BACKUP DIRECTORY
mkdir("/mnt/backups/" . $vmid, 0755);

// GENERATE BACKUP XML
$domainbackup = simplexml_load_file('/opt/seidr/vmbackup.xml');
$domainbackup->disks->disk[0]->target->attributes()->file="/mnt/backups/" . $vmid . '/' . $vmid . '-' . date('Y-m-d') . ".qcow2";
$domainbackup->asXML('/mnt/backups/' . $vmid . '/' . $vmid . '.xml');

// START VIRSH BACKUP
echo "Running backup...";
$shellout = shell_exec('virsh -c qemu:///system backup-begin ' . $vmid . ' /mnt/backups/' . $vmid . '/' . $vmid . '.xml');
echo "<pre>$shellout</pre>";
?>
