<?php
// START SESSION
session_start();

// HANDLE LOGIN
if (isset($_POST["user"]) && !isset($_SESSION["user"])) {
  // CHECK CREDENTIALS
   $users = [
    "freya" => "lady"
  ];

  // CHECK & VERIFY
  if (isset($users[$_POST["user"]])) {
    if ($users[$_POST["user"]] == $_POST["password"]) {
      $_SESSION["user"] = $_POST["user"];
    }
  }

  // FAILED LOGIN FLAG
  if (!isset($_SESSION["user"])) { $failed = true; }
}

// REDIRECT USER TO HOME PAGE IF SIGNED IN
if (isset($_SESSION["user"])) {
  header("Location: index.php");
  exit();
}
