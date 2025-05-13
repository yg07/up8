<?php
if(!$_SESSION['loginned']){
    header(substr("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],0,strrpos("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],'/')));
}
?>