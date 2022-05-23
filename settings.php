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

// INCLUDE HEADER FILE
include 'header.php';

// PAGE CONTENT
echo '
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800"></h1>

                       <!-- Start Settings Card Section -->
                       <h1>Settings</h1>
                       <div class="row">
                         <div class="col-lg-6">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Welcome</h6>
                                </div>
                                <div class="card-body">
                                 <p>Here is where you will find settings for Freya</p>
                                </div>
                            </div>
                        </div>

                        <!-- End Settings Card Section -->
                        </div>

                <!-- /.container-fluid -->
             </div>
            </div>
            <!-- End of Main Content -->
';

// INCLUDE HEADER FILE
include 'footer.php';
