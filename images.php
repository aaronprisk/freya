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

                       <!-- Start Upload Card Section -->
                       <h1>ISO Uploader</h1>
                       <div class="row">
                         <div class="col-lg-6">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Upload Image</h6>
                                </div>
                                <div class="card-body">
                                 Upload iso images to be used by your VMs. <strong>Max 10GB file size.</strong><hr>
';
                                 //UPLOAD PHP FORM
                                  if (isset($_SESSION['message']) && $_SESSION['message'])
                                  {
                                  printf('<b>%s</b>', $_SESSION['message']);
                                  unset($_SESSION['message']);
                                  }
echo '

                                 <form method="POST" action="upload.php" enctype="multipart/form-data">
                                   <div>
                                    <span>Upload a File:</span>
                                    <input type="file" name="uploadedFile" />
                                  </div>
                                  <input type="submit" name="uploadBtn" value="Upload" />
                                 </form>

                                </div>
                            </div>
                        </div>

                        <!-- End Upload Card Section -->
                        </div>

                        <!-- Start Uploaded File Section -->
                        <div class="row">
                         <div class="col-lg-6">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Uploaded Images</h6>
                                </div>
                                <div class="card-body">
';
                               $fileList = glob('uploads/*');
                               foreach($fileList as $filename){
                                   if(is_file($filename)){
                                       echo $filename, '<br>';
                                    }
                               }
echo '

                                </div>
                            </div>
                        </div>

                        <!-- End Uploaded File Section -->
                        </div>

                       <!-- Start KVM Tools section -->
                        <div class="row">
                          <div class="col-lg-6">
                           <div class="card mb-4">
                            <div class="card-header py-3">
                               <h6 class="m-0 font-weight-bold text-danger">KVM Guest Tools</h6>
                               </div>
                               <div class="card-body">
                                   <p>Download the latest stable KVM Guest Tools by clicking the button below.</p>
                                    <a class="btn btn-primary" href="tools-dl.php" role="button">Download</a>
                                </div>
                            </div>
                        </div>
                       <!-- End KVM Tools Section -->
                       </div>

                <!-- /.container-fluid -->
             </div>
            </div>
            <!-- End of Main Content -->
';

// INCLUDE HEADER FILE
include 'footer.php';

