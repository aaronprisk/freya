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

// PULL VM VNC PORT
$vncport = $_GET['vncport'];

// KILL ANY RUNNING WEBSOCKIFY SESSIONS TO PREVENT CONFLICTS
$killstr = shell_exec('killall websockify > /dev/null 2>/dev/null &');

// CREATE NEW WEBSOCKIFY SESSION
$vncstr = shell_exec('/var/www/html/noVNC/utils/novnc_proxy --vnc localhost:' . $vncport . ' --idle-timeout 10 > /dev/null 2>/dev/null &');

// OPEN NOVNC DISPLAY PAGE
header('location: http://10.9.1.5:6080/vnc.html');
?>
