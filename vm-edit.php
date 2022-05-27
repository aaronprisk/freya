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

// SET MESSAGE VAR
$message = '';

// PULL VMID VARIABLE
$vmid = $_REQUEST['vmid'];

// SET POST VARIABLES
$name = $_POST['hostname'];
$memory = intval($_POST['memory']) * 1024;
$maxmemMB = $_POST['memory'];
$vcpus = $_POST['cpus'];
$newdisk = $_POST['newdisk'];
$network = $_POST['network'];

// SHUTDOWN SELECTED VM
$res = libvirt_domain_lookup_by_name($conn, $vmid);
echo "Trying graceful shutdown...";
libvirt_domain_shutdown($res);
sleep(5);
echo "Forceful shutdown of VM...";
libvirt_domain_destroy($res);


// DUMP SELECTED VM XML
echo "Dumping XML...";
shell_exec('virsh -c qemu:///system dumpxml ' . $vmid . ' > /var/www/html/xml/' . $vmid . '.xml');
echo 'virsh dumpxml ' . $vmid . ' > /var/www/html/xml/' . $vmid . '.xml';

// LOAD AND EDIT DUMPED XML
echo "Applying changes...";
$domain = simplexml_load_file('xml/' . $vmid . '.xml');
if (isset($_POST['hostname'])) {
  $domain->name = $name;
}
if (isset($_POST['memory'])) {
  $domain->memory = $memory;
  $domain->currentMemory = $memory;
}
if (isset($_POST['cpus'])) {
  $domain->vcpu = $vcpus;
}
if ( $_POST['network'] != 0 ) {
  $domain->devices->interface[0]->source->attributes()->network = $network;
}
echo $domain->asXML('xml/' . $vmid . '.xml');

// UNDEFINE VM
echo "Undefining VM...";
shell_exec('virsh -c qemu:///system undefine ' . $vmid);

// REDEFINE VM
echo "Defining VM...";
shell_exec('virsh -c qemu:///system define /var/www/html/xml/' . $vmid . '.xml --validate');

// STARTING VM
if (isset($_POST['hostname'])) {
  echo "Staring VM...";
  shell_exec('virsh -c qemu:///system start ' . $name);
} else {
  echo "Starting VM...";
  shell_exec('virsh -c qemu:///system start ' . $vmid);
}

// RETURN TO MACHINES PAGE
header("Location: machines.php");
?>
