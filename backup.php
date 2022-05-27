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

include 'connect.php';

// INCLUDE HEADER FILE
include 'header.php';

// SET BACKUP LOCATION VARIABLE
// TEMP PLACEHOLDER STRING
$backdir = "/mnt/backups";

// CHECK BACKUP DIR CAPACITY AND USAGE
$df = intval(disk_free_space("/") / 1000000000) ;
$ds = intval(disk_total_space("/") / 1000000000) ;
$da = ($ds - $df);

// PAGE CONTENT
echo '
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800"></h1>

                       <!-- Start Settings Card Section -->
                       <h1>Backup</h1>
                       <div class="row">
                         <div class="col-lg-6">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Backup Location</h6>
                                </div>
                                <div class="card-body">
                                 <p><strong>Current backup location:</strong> ' . $backdir . '
                                 <p><strong>Backup Used:</strong> ' . $da . 'GB / ' . $ds . ' GB<p>
                                </div>
                            </div>
                        </div>

                        <!-- End Settings Card Section -->
                        </div>

                       <!-- Start Settings Card Section -->
                       <div class="row">
                         <div class="col-lg-6">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Backup Enabled VMs</h6>
                                </div>
                                <div class="card-body">
                                  <table class="table"><thead><tr><th scope="col">Name</th><th scope="col">Size</th></tr></thead><tbody>';

// EXPORT VM LIST AND SIZE DATA
$shellout = shell_exec('/opt/seidr/seidr-info-backup.sh');
echo "$shellout";

echo '
                                  </tbody></table>
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
