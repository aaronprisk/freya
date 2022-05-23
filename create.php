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

// PULL SERVER INFO FOR CREATION
$netflags= VIR_NETWORKS_ALL;
$net = libvirt_list_networks($conn, $netflags);

// INCLUDE HEADER FILE
include 'header.php';

// PAGE CONTENT
echo '

                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Create New VM</h1>
                    </div>
                    <p class="h4">Basic Info</p>
                   <form method="POST" action="vm-create.php" enctype="multipart/form-data">
                     <div class="form-group col-md-3">
                       <label for="InputName">Virtual Machine Name</label>
                       <input type="text" class="form-control" id="InputName" name="hostname" aria-describedby="nameHelp" placeholder="Enter Name">
                       <small id="nameHelp" class="form-text text-muted"></small>
                     </div>
                     <div class="form-group col-md-3">
                           <label for="InputName">Server</label>
                           <select class="custom-select" name="server">
                           <option value="0" selected>Freya (This Server)</option>
                           <option value="1">Odin</option>
                         </select>
                     </div>
                     <div class="form-group col-md-2">
                       <label for="InputMemory">Memory (MBs)</label>
                       <input type="text" class="form-control" id="InputMemory" name="memory" aria-describedby="memoryHelp" placeholder="1024">
                       <small id="memoryHelp" class="form-text text-muted">1GB = 1024MBs</small>
                     </div>
                     <div class="form-group col-md-2">
                       <label for="InputCPU">Virtual CPUs</label>
                       <input type="text" class="form-control" id="InputCPU" name="cpus" aria-describedby="cpuHelp" placeholder="1">
                       <small id="cpuHelp" class="form-text text-muted">Node has 8 avaiable CPUs</small>
                     </div>
                     <hr>
                     <p class="h4">Storage Info</p>
                     <strong>Create new virtual disk</strong>
                     <div class="form-group col-md-2">
                       <label for="InputDisk">Virtual Disk Size (GBs)</label>
                       <input type="text" class="form-control" id="InputDisk" name="newdisk" aria-describedby="diskHelp" placeholder="">
                       <small id="diskHelp" class="form-text text-muted"></small>
                     </div>
                     <strong>Or use existing virtual disk</strong>
                     <div class="form-group col-md-3">
                         <select class="custom-select" name="existdisk">
                           <option value="0" selected>Select Existing Disk Image</option>
';
                               $diskList = glob('/var/lib/libvirt/images/*.qcow2');
                               foreach($diskList as $filename){
                                   if(is_file($filename)){
                                       echo "<option value='$filename'>$filename</option>";
                                    }
                               }
echo '
                         </select>
                     </div>
                     <strong>Add VM to Backup Schedule</strong>
                     <div class="form-group col-md-3">
                       <div class="custom-control custom-checkbox small">
                         <input type="checkbox" class="custom-control-input" id="customCheck" name="backup">
                         <label class="custom-control-label" for="customCheck">Backup Enabled</label>
                       </div>
                     </div>
                     <hr>
                     <p class="h4">Network Info</p>
                     <div class="form-group col-md-3">
                         <select class="custom-select" name="network">
                           <option value="" selected>Select Network</option>
';
                           for ($i = 0, $n = count($net) ; $i < $n ; $i++)
                           {
                           echo "<option value='$net[$i]'>$net[$i]</option>";
                           }
echo '
                         </select>
                     </div>
                     <hr>
                     <p class="h4">Choose ISO</p>
                     <div class="form-group col-md-3">
                         <select class="custom-select" name="iso">
                             <option value="" selected>Select iso image</option>
';
                               $isoList = glob('uploads/*');
                               foreach($isoList as $filename){
                                   if(is_file($filename)){
                                       echo "<option value='$filename'>$filename</option>";
                                    }
                               }
echo '
                         </select>
                         <small id="isoHelp" class="form-text text-muted">Image not listed? Upload it <a href="images.php">here.</a></small>
                     </div>
                     <div class="form-group col-md-3">
                     <button type="submit" class="btn btn-primary">Create</button>
                     </div>
                   </form>


                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->
';
// INCLUDE HEADER FILE
include 'footer.php';

