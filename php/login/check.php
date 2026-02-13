<?php
require "../include.php";

$msg = '';


$info = validUserAndGetDB();

echo fToJson(["success" => true, "message" => $msg, "expires_in"=>$info['expires_in'], "session"=>$_SESSION, "cookie"=>$_COOKIE]);
exit;
