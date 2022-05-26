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

// PULL VM COUNTS AND INFO
$odoms = libvirt_list_active_domains($conn);
$idoms = libvirt_list_inactive_domains($conn);
$odomcount = count($odoms);
$idomcount = count($idoms);
// PLACEHOLDER FOR NODE SUPPORT
$onodecount = 1;
$inodecount = 0;

// INCLUDE HEADER FILE
include 'header.php';

// PAGE CONTENT
echo '
                <meta http-equiv="refresh" content="10">
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                    </div>
                    Virtual Machines
                    <!-- Start of Top Row -->
                    <div class="row">

                        <!-- Online VMs Card  -->
                        <div class="col-xl-6 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Online VMs</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">' . $odomcount . '</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-desktop fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Offline VMs Card -->
                        <div class="col-xl-6 col-md-6 mb-4">
                            <div class="card border-left-danger shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Offline VMs</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">' . $idomcount . '</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-desktop fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <!-- End of Top Row -->
                    </div>

                    <!-- Start Bottom Section -->

                    <div class="row">

                        <!-- Online VM List -->
                        <div class="col-xl-6 col-lg-6">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Online Virtual Machines</h6>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">';


// PULL ACTIVE VM LIST
echo "<table><tbody>";
    for ($i = 0, $n = count($odoms) ; $i < $n ; $i++)
    {
    echo "<tr><th scope='row'>";
    // DISPLAY ACTIVE ICON
    $res = libvirt_domain_lookup_by_name($conn, $odoms[$i]) ;
    if (libvirt_domain_get_id($res) != "-1") {echo " " . "&#128994;";} else {echo " " . "&#128308;";} ;
    echo "</th>";
    // DISPLAY VM NAME AND INFO LINK
    echo "<td><a href='vm.php?vmid=" . $odoms[$i] . "'>" . $odoms[$i] . "</a>";
    echo "</td></tr>" ;
    }
echo "</tr></tbody></table>" ;

echo '
                                </div>
                            </div>
                        </div>

                        <!-- Offline VM List -->
                        <div class="col-xl-6 col-lg-6">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                   <h6 class="m-0 font-weight-bold text-primary">Offline Virtual Machines</h6>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">';

// PULL INACTIVE VM LIST
    echo "<table><tbody>";
    for ($i = 0, $n = count($idoms) ; $i < $n ; $i++)
    {
    echo "<tr><th scope='row'>";
    // DISPLAY INACTIVE ICON
    $res = libvirt_domain_lookup_by_name($conn, $idoms[$i]) ;
    if (libvirt_domain_get_id($res) != "-1") {echo " " . "&#128994;";} else {echo " " . "&#128308;";} ;
    echo "</th>";
    // DISPLAY VM NAME AND INFO LINK
    echo "<td><a href='vm.php?vmid=" . $idoms[$i] . "'>" . $idoms[$i] . "</a>";
    echo "</td></tr>" ;
    }
echo "</tr></tbody></table>" ;
echo '
                                </div>
                            </div>
                        </div>



                    <!-- End of bottom section -->
                    </div>

                    <!-- Mid page divider -->
                    <hr>

Freya Nodes
 <!-- Start of Top Row -->
 <div class="row">

    <!-- Online VMs Card  -->
    <div class="col-xl-6 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Online Nodes</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">' . $onodecount . '</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-server fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Offline VMs Card -->
    <div class="col-xl-6 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Offline Nodes</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">' . $inodecount . '</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-server fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- End of Top Row -->
</div>

<!-- Start Bottom Row -->

<div class="row">

    <!-- Online VM List -->
    <div class="col-xl-6 col-lg-6">
        <div class="card shadow mb-4">
          <div class="card-header py-3">
           <h6 class="m-0 font-weight-bold text-primary">Online Freya Nodes</h6>
          </div>
            <!-- Card Body -->
            <div class="card-body">
              &#128994; FREYA (localhost)
            </div>
        </div>
    </div>

    <!-- Offline VM List -->
    <div class="col-xl-6 col-lg-6">
        <div class="card shadow mb-4">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Offline Freya Nodes</h6>
          </div>
            <!-- Card Body -->
            <div class="card-body">

            </div>
        </div>
    </div>
    <!-- End of Botto, Row -->
</div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->
';

// INCLUDE HEADER FILE
include 'footer.php';

