<?php
// FORCE POWER OFF OF VM

// PULL VMID VARIABLE
$vmid = $_REQUEST['vmid'];

// INCLUDE CONNECT FILE
include 'connect.php';

if (isset($_REQUEST['vmid'])) {
    echo "The Force Shutdown function is called.";
    $res = libvirt_domain_lookup_by_name($conn, $vmid);
    echo "Force Shutting Down VM...";
    libvirt_domain_destroy($res);
    header("Location: machines.php");
}

// IF VMID IS NOT PASSED RETURN TO MACHINES PAGE
header("Location: machines.php");

?>
