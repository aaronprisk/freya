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

    // PULL BACKUP DAY VALUES
    if(!empty($_POST['day'])) {
        foreach($_POST['day'] as $day) {
            $dayarray .= $day . ",";
        }
    }

    // PULL RETENTION VARIABLE
    $retention = $_POST['retention'];

    // CREATE SYSTEMD TIMER STRING
    $timer = substr_replace($dayarray ,"",-1) . ' *-*-* ' . $_POST['time'] . ':00';
    echo $timer;

    // OPEN FREYA-BACKUP.XML
    $backup = simplexml_load_file('/opt/seidr/backup.xml');
    if (isset($retention)) {
      $backup->retention = $retention;
    }
    if (isset($timer)) {
      $backup->daytime = $timer;
    }

    // APPLY UPDATE TO BACKUP.XML
    echo $backup->asXML('/opt/seidr/backup.xml');

    // RETURN TO BACKUP PAGE
    header("Location: backup.php");
?>
