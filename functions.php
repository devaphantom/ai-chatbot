<?php
session_start();
function isLoggedIn(){return isset($_SESSION['user_id']);}
function redirectIfNotLoggedIn(){if(!isLoggedIn()){header("Location: login.html"); exit;}}
function getUserHistoryFile($user_id){$dir=__DIR__.'/user_data'; if(!is_dir($dir)) mkdir($dir,0755); return "$dir/$user_id.json";}
function loadUserHistory($user_id){$file=getUserHistoryFile($user_id); return file_exists($file)?json_decode(file_get_contents($file),true):[];}
function saveUserHistory($user_id,$history){$file=getUserHistoryFile($user_id); file_put_contents($file,json_encode($history,JSON_PRETTY_PRINT));}
?>
