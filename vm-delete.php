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

    // CONNECT
    $uri="qemu:///system";
    echo ("Connecting to KVM host via (URI:$uri)\n"."<br/>"."<br/>");
    $conn=libvirt_connect($uri,false);
    if ($conn==false)
    {
    echo ("Libvirt last error: ".libvirt_get_last_error()."\n");
    exit;
    }
    // ASSIGN SELECTED DOMAIN TO VARIABLE
    if (isset($_POST['vmid'])) {
        $vmid = $_POST['vmid'];
    }
    // CHECK FOR USER CONFIRMATION
     if (isset($_POST['confirm'])) {
        destroy($conn, $vmid);
    }
    // VM DESTROY FUNCTION
    function destroy($conn, $vmid) {
    echo "Deleting " . $vmid;
    $res = libvirt_domain_lookup_by_name($conn, $vmid);
    libvirt_domain_shutdown($res);
    libvirt_domain_undefine($res);
    header("Location: machines.php");
    }
?>
