<?php
// DOWNLOAD LATEST KVM GUEST TOOLS FROM FEDORA GITHUB REPOS
// REQUIRES WGET TO BE INSTALLED ON SERVER

//START DOWNLOAD IN BACKGROUND
shell_exec('wget https://fedorapeople.org/groups/virt/virtio-win/direct-downloads/stable-virtio/virtio-win.iso -P /var/www/html/uploads > /dev/null &');
// RETURN TO IMAGES PAGE
header("Location: images.php");

?>
