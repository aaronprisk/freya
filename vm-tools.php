<?php
    // CONNECT
    $uri="qemu:///system";
    echo ("Connecting to KVM host via (URI:$uri)\n"."<br/>"."<br/>");
    $conn=libvirt_connect($uri,false);
    if ($conn==false)
    {
    echo ("Libvirt last error: ".libvirt_get_last_error()."\n");
    exit;
    }

    // ASSIGN SELECTED DOMAIN TO VARIABLE
    if (isset($_REQUEST['vmid'])) {
        $vmid = $_REQUEST['vmid'];
        mounttools($vmid);
    }

    // ATTACH KVM TOOL ISO
    function mounttools($vmid) {
    //    shell_exec('cd /tmp/ && virsh attach-disk ' . $vmid . ' /tmp/virtio-win.iso sda --type cdrom --mode readonly');
        header("Location: vm.php?vmid=" . $vmid);
    //header("Location: index.php");
    }
?>
