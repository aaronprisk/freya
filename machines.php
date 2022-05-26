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
                <meta http-equiv="refresh" content="10">
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Virtual Machines</h1>
                    <p><a href="create.php" class="btn btn-primary btn-icon-split">
                        <span class="icon text-white-50">
                            <i class="fas fa-plus"></i>
                        </span>
                        <span class="text">Create New VM</span>
                        </a></p>
';

// ADD IN JQUERY LIBRARY
echo "<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js'></script>";

// LIST VMS
$doms = libvirt_list_domains($conn);
echo "<table class='table'><thead><tr><th scope='col'>Active</th><th scope='col'>Name</th><th scope='col'>Controls</th></tr></thead><tbody>";

    for ($i = 0, $n = count($doms) ; $i < $n ; $i++)
    {
    echo "<tr><th scope='row'>";
    // DISPLAY ACTIVE ICON
    $res = libvirt_domain_lookup_by_name($conn, $doms[$i]) ;
    if (libvirt_domain_get_id($res) != "-1") {echo " " . "&#128994;";} else {echo " " . "&#128308;";} ;
    echo "</th>";
    // DISPLAY VM NAME AND INFO LINK
    echo "<td><a href='vm.php?vmid=" . $doms[$i] . "'>" . $doms[$i] . "</a></td><td>";
    // DISPLAY POWER BUTTONS
    if (libvirt_domain_get_id($res) == "-1") {echo "<input type='submit' class='btn btn-success button' name='start' data-value='" . $doms[$i] . "' value='Start' />";}
    else {echo "<input type='submit' class='btn btn-danger button' name='shutdown' data-value='" . $doms[$i] . "' value='Shutdown' />"; }
    echo "</td></tr>" ;
    }

// CLOSE TABLE
echo "</tbody></table>" ;

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




echo '

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->
';

// INCLUDE HEADER FILE
include 'footer.php';
