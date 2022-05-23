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

// PULL VM ID
$vmid = $_GET['vmid'];

// INCLUDE HEADER FILE
include 'header.php';

// PAGE CONTENT
echo '
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800"></h1>

                       <!-- Start Upload Card Section -->
                       <h1>Delete VM</h1>
                       <div class="row">
                         <div class="col-lg-6">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Delete Confirmation</h6>
                                </div>
                                <div class="card-body">
                                 <p>Are you sure you want to delete VM: <strong>' . $vmid . '</strong></p>
';
                                 //UPLOAD PHP FORM
                                  if (isset($_SESSION['message']) && $_SESSION['message'])
                                  {
                                  printf('<b>%s</b>', $_SESSION['message']);
                                  unset($_SESSION['message']);
                                  }
echo '
                                 <form method="POST" action="vm-delete.php" enctype="multipart/form-data">
                                   <div class="custom-control custom-checkbox small">
                                     <input type="checkbox" class="custom-control-input" id="customCheck" name="confirm">
                                     <label class="custom-control-label" for="customCheck">Check to Confirm</label>
                                   </div>
                                   <hr>
                                   <input type="submit" class="btn btn-danger button" name="delete" value="Delete" />
                                   <input type="hidden" name="vmid" value="' . $vmid . '" />
                                 </form>

                                </div>
                            </div>
                        </div>

                        <!-- End Confirmation Card Section -->
                        </div>


                <!-- /.container-fluid -->
             </div>
            </div>
            <!-- End of Main Content -->
';
// INCLUDE HEADER FILE
include 'footer.php';

