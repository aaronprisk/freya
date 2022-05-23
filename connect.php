<?php // CONNECT TO LOCAL LIBVIRT INSTANCE
$uri="qemu:///system";
//echo ("Connecting to KVM host via (URI:$uri)\n"."<br/>"."<br/>");
$conn=libvirt_connect($uri,false);
if ($conn==false)
{
echo ("Libvirt last error: ".libvirt_get_last_error()."\n");
exit;
}
?>