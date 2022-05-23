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

    // ASSIGN SELECTED DOMAIN TO VARIABLE
    if (isset($_POST['seldom'])) {
        $seldom = $_POST['seldom'];
    }

    // VM FUNCTION
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'Shutdown':
                shutdown($conn, $seldom);
                break;
            case 'Start':
                startup($conn, $seldom);
                break;
        }
    }

    function shutdown($conn, $seldom) {
        echo "The Shutdown function is called.";
        $res = libvirt_domain_lookup_by_name($conn, $seldom);
        echo "Shutting Down VM...";
        libvirt_domain_shutdown($res);
        exit;
    }

    function startup($conn, $seldom) {
        echo "The Startup function is called.";
        $res = libvirt_domain_lookup_by_name($conn, $seldom);
        echo "Starting VM...";
        libvirt_domain_create($res);
        exit;
    }
?>
