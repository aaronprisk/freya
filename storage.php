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
                       <h1>Disk Management</h1>
                       <div class="row">
                         <div class="col-lg-6">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Disk Management Information</h6>
                                </div>
                                <div class="card-body">
                                 <p>Here you can find and manage your virtual machine disks.</p>
                                </div>
                            </div>
                        </div>

                        <!-- End Upload Card Section -->
                        </div>

                        <!-- Start Disk Images Section -->
                        <div class="row">
                         <div class="col-lg-6">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Disk Images</h6>
                                </div>
                                <div class="card-body">
';
                               $fileList = glob('/var/lib/libvirt/images/*');
                               foreach($fileList as $filename){
                                   if(is_file($filename)){
                                       echo $filename, '<br>';
                                    }
                               }
echo '

                                </div>
                            </div>
                        </div>

                        <!-- End Disk Images Section -->
                        </div>

                <!-- /.container-fluid -->
             </div>
            </div>
            <!-- End of Main Content -->
';

// INCLUDE HEADER FILE
include 'footer.php';
