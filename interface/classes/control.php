<?php
// Shibboleth Login Script #2
//$server_utor = str_replace("@utoronto.ca","",$_SERVER['REMOTE_USER']);
$server_utor = str_replace("@utoronto.ca", "", "aguirreb@utoronto.ca");
$loggedMem = new Member($server_utor);
?>
