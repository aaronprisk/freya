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
$vmid = $_GET['vmid'];

// INCLUDE HEADER FILE
include 'header.php';

// INCLUDE CONNECT FILE
include 'connect.php';

// PAGE CONTENT
echo '

                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800"></h1>

';

// ADD IN JQUERY LIBRARY
echo "<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js'></script>";

// PULL VM INFO
$res = libvirt_domain_lookup_by_name($conn, $vmid);
$info = libvirt_domain_get_info($res);
$vncport = libvirt_domain_get_xml_desc($res, '/domain/devices/graphics/@port');
$memgb = $info['maxMem']/1000000;
echo "<h3>" . $vmid . "</h3>";


// VM CONTROLS
if (libvirt_domain_get_id($res) == "-1") {echo "<input type='submit' class='btn btn-success button' name='start' data-value='" . $vmid . "' value='Start' />";}
else {echo "<input type='submit' class='btn btn-danger button' name='shutdown' data-value='" . $vmid . "' value='Shutdown' />"; }
if (libvirt_domain_get_id($res) != "-1") {echo " <a class='btn  btn-primary' href='console.php?vncport=" . $vncport . "' target='_blank' role='button'>Console</a>";}
// echo " <a class='btn  btn-primary' href='console.php?vncport=" . $vncport . "' target='_blank' role='button'>Console</a>";
echo " <button class='btn btn-secondary dropdown-toggle' type='button'id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true'aria-expanded='false'>VM Actions</button>
        <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
          <a class='dropdown-item' href='edit.php?vmid=" . $vmid . "'>Edit VM</a>
          <a class='dropdown-item' href='vm-backup.php?vmid=" . $vmid . "'>Backup VM</a>
          <a class='dropdown-item' href='#'>Migrate VM</a>";
          if (libvirt_domain_get_id($res) != "-1") {echo "<a class='dropdown-item' href='destroy.php?vmid=" . $vmid . "'>Force Off VM</a>";}
echo "
          <hr class='sidebar-divider my-0'>
          <a class='dropdown-item text-danger' href='delete.php?vmid=" . $vmid . "'>Delete VM</a>
        </div>";
echo "<hr>";

// VM CONTROLS JS
echo "
<script>$(document).ready(function(){
    $('.button').click(function(){
        var clickBtnValue = $(this).val();
        var clickBtnDom = $(this).attr('data-value');
        var ajaxurl = 'vm-func.php';
        data =  {'action': clickBtnValue, 'seldom': clickBtnDom};
        $.post(ajaxurl, data, function (response) {
            // Response div goes here.
            location.reload();
        });
    });
});
</script>
";


?>
                <!-- VM Info Row -->
                <div class="row"
                    <!-- VM Info Card -->
                    <div class="col-lg-6">
                        <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Virtual Machine Info</h6>
                                </div>
                                <div class="card-body">

<?php

// DISPLAY VM INFO
$name = libvirt_domain_get_xml_desc($res, '/domain/name');
echo "<strong>Name: </strong>" . $name;
echo "</br>";
echo "<strong>Status:</strong> ";
if (libvirt_domain_get_id($res) != "-1") {echo "Online " . "&#128994;";} else {echo "Offline " . "&#128308;";} ;
echo "<br>";
$os = libvirt_domain_get_xml_desc($res, '/domain/metadata/*/*/@id');
echo "<strong>OS: </strong>" . $os;
echo "<hr>";
echo "<strong>Memory:</strong> " . number_format($memgb,2) . " GB";
echo "<br>";
$cpu = libvirt_domain_get_xml_desc($res, '/domain/cpu/@mode');
echo "<strong>CPU Model: </strong>" . $cpu;
echo "<br>";
echo "<strong>Virtual CPUs:</strong> " . $info['nrVirtCpu'];
echo "<br>";
echo "<hr>";
// DISPLAY VM NIC INFO
$nicxml = libvirt_domain_get_xml_desc($res, '/domain/devices/interface/model/@type');
echo "<strong>NIC Type: </strong>" . $nicxml;
echo "<br>";
$mac = libvirt_domain_get_xml_desc($res, '/domain/devices/interface/mac/@address');
echo "<strong>MAC Address: </strong>" . $mac;
// DISPLAY VM STORAGE
echo "<hr>";
echo "<strong>Disks:</strong> ";
$disk = libvirt_domain_get_xml_desc($res, '/domain/devices/disk/source/@file');
echo $disk;
// DISPLAY VM METADATA
$meta = libvirt_domain_get_xml_desc($res, '/domain/description');
echo "<br>";
echo "<strong>Backup Enabled:</strong> " . $meta;


echo '
                   <!-- End VM Info Card -->
                 </div>
               </div>
             </div>
            <! -- VM Preview Card -->
             <div class="col-lg-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">VM Preview</h6>
                        </div>
                        <div class="card-body">';
                        // DISPLAY VM PREVIEW IF VM IS ACTIVE
                        if (libvirt_domain_get_id($res) == "-1") {echo "VM Offline";} else {
                          $thumbnail = "thumbs/" . $vmid . "/" . $vmid . ".png";
                          if (file_exists($thumbnail))
                          {
                             echo '<img class="img-fluid" src="thumbs/'. $vmid . '/' . $vmid . '.png" />';
                           }
                        }
echo '
                            </div>
                        </div>
                   </div>
               </div>
               <!-- End Top Info Row -->

                <!-- VM Info Row -->
                <div class="row"
                    <!-- VM Info Card -->
                    <div class="col-lg-12">
                        <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Backup and Snapshots</h6>
                                </div>
                                <div class="card-body">
';

                               $backupList = glob('/mnt/backups/' . $vmid . '/*.qcow2');
                               foreach($backupList as $filename){
                                   if(is_file($filename)){
                                       echo $filename, '<br>';
                                    }
                               }

echo '
                        <!-- End Backup Info Card -->
                    </div>
                  </div>
                  <!-- End Top Info Row -->
                </div>
              </div>
            </div>


                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->
';

// INCLUDE HEADER FILE
include 'footer.php';

