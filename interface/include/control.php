<?php
$server_utor = str_replace("@utoronto.ca","",$_SERVER['REMOTE_USER']);
$loggedUser = new User($server_utor);
if(!$loggedUser->isUser()){
    die("You do not have permission to access this resource");
}
?>