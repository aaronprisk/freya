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

// PULL VMID VARIABLE
$vmid = $_REQUEST['vmid'];

// INCLUDE CONNECT FILE
include 'connect.php';

// PULL VM INFO
$res = libvirt_domain_lookup_by_name($conn, $vmid);
$info = libvirt_domain_get_info($res);
$vmname = libvirt_domain_get_xml_desc($res, '/domain/name');
$memgb = $info['maxMem']/1024;
$vcpu = $info['nrVirtCpu'];
$netflags= VIR_NETWORKS_ALL;
$net = libvirt_list_networks($conn, $netflags);
$curnet = libvirt_domain_get_xml_desc($res, '/domain/devices/interface/source/@network');
$disk = libvirt_domain_get_xml_desc($res, '/domain/devices/disk/source/@file');



// INCLUDE HEADER FILE
include 'header.php';

// PAGE CONTENT
echo '

                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Edit VM</h1>
                    </div>

                    <!-- Start Notice Card Section -->
                    <div class="row">
                      <div class="col-lg-6">
                         <div class="card shadow mb-4">
                             <div class="card-header py-3">
                                 <h6 class="m-0 font-weight-bold text-danger">Applying Changes</h6>
                             </div>
                             <div class="card-body">
                              Virtual Machines will be rebooted to apply changes.
                             </div>
                         </div>
                     </div>
                     <!-- End Notice Card Section -->
                     </div>

                    <p class="h4">Basic Info</p>
                   <form method="POST" action="vm-edit.php?vmid=' . $vmid . '" enctype="multipart/form-data">
                     <div class="form-group col-md-3">
                       <label for="InputName">Virtual Machine Name</label>
                       <input type="text" class="form-control" id="InputName" name="hostname" aria-describedby="nameHelp" placeholder="' . $vmname . '">
                       <small id="nameHelp" class="form-text text-muted"></small>
                     </div>
                     <div class="form-group col-md-2">
                       <label for="InputMemory">Memory (MBs)</label>
                       <input type="text" class="form-control" id="InputMemory" name="memory" aria-describedby="memoryHelp" placeholder="' . $memgb . '">
                       <small id="memoryHelp" class="form-text text-muted">1GB = 1024MBs</small>
                     </div>
                     <div class="form-group col-md-2">
                       <label for="InputCPU">Virtual CPUs</label>
                       <input type="text" class="form-control" id="InputCPU" name="cpus" aria-describedby="cpuHelp" placeholder="' . $vcpu . '">
                       <small id="cpuHelp" class="form-text text-muted">Node has 8 avaiable CPUs</small>
                     </div>
                     <hr>
                     <p class="h4">Storage Info</p>
                     <strong>Change Virtual Disk</strong>
                     <div class="form-group col-md-3">
                         <select class="custom-select" name="existdisk">
                           <option value="0" selected>' . $disk . '</option>
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
                     <hr>
                     <p class="h4">Network Info</p>
                     <div class="form-group col-md-3">
                         <select class="custom-select" name="network">
                           <option value="0" selected>' . $curnet . '</option>
';
                           for ($i = 0, $n = count($net) ; $i < $n ; $i++)
                           {
                           echo "<option value='$net[$i]'>$net[$i]</option>";
                           }
echo '
                         </select>
                     </div>
                     <hr>
                     <div class="form-group col-md-3">
                     <button type="submit" class="btn btn-primary">Apply Changes</button>
                     </div>
                   </form>


                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->
';
// INCLUDE HEADER FILE
include 'footer.php';
