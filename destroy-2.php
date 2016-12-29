<?php
session_start();
session_destroy();
setcookie("UID2","",time()-3,"/");
setcookie("sk2","",time()-3,"/");
echo "<script type='text/javascript'> 
location.replace('artist-2'); 
</script>";
?>