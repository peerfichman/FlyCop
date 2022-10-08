<?php 
include "urldefine.php";
session_start();
session_destroy();
header('Location: '. URL);
?>