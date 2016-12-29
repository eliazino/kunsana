<?php
session_start();
session_destroy();
setcookie("UID1","",time()-3,"/");
setcookie("sk","",time()-3,"/");
echo "<script type='text/javascript'> 
location.replace('index'); 
</script>";
?>