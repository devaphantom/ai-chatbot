<?php
require 'functions.php';
redirectIfNotLoggedIn();   // Ensure user is logged in
$user_id = $_SESSION['user_id'];
$history = loadUserHistory($user_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>AI Chat Dashboard</title>
<style>
body { font-family: Arial, sans-serif; background: #f4f4f9; margin:0; padding:0; display:flex; flex-direction:column; align-items:center; }
h2 { margin-top:20px; }
#chat-box { border:1px solid #ccc; height:400px; width:90%; max-width:600px; overflow-y:auto; padding:10px; background:#fff; margin-bottom:10px; border-radius:8px; }
#chat-box p { margin:5px 0; }
#chat-box .user { font-weight:bold; color:#4e54c8; }
#chat-box .ai { font-weight:bold; color:#28a745; }
#input-container { width:90%; max-width:600px; display:flex; margin-bottom:20px; }
#user-msg { flex:1; padding:10px; border-radius:6px; border:1px solid #ccc; }
button { padding:10px 20px; margin-left:10px; border:none; border-radius:6px; background:#4e54c8; color:#fff; font-weight:bold; cursor:pointer; }
button:hover { background:#3b40a0; }
.logout { margin-bottom:20px; text-decoration:none; color:#fff; background:#dc3545; padding:10px 20px; border-radius:6px; }
.logout:hover { background:#a71d2a; }
</style>
</head>
<body>

<h2>AI Chat Dashboard</h2>

<div id="chat-box">
<?php
foreach($history as $msg){
    $class = ($msg['sender'] === 'User') ? 'user' : 'ai';
    echo "<p class='{$class}'><b>{$msg['sender']}:</b> {$msg['message']}</p>";
}
?>
</div>

<div id="input-container">
    <input type="text" id="user-msg" placeholder="Type your message...">
    <button onclick="sendMessage()">Send</button>
</div>

<a class="logout" href="logout.php">Logout</a>

<script>
function sendMessage(){
    let msg = document.getElementById('user-msg').value;
    if(!msg) return;

    let xhr = new XMLHttpRequest();
    xhr.open("POST","chat.php",true);
    xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xhr.onload = function(){
        document.getElementById('chat-box').innerHTML = this.responseText;
        document.getElementById('user-msg').value = '';
        document.getElementById('chat-box').scrollTop = document.getElementById('chat-box').scrollHeight;
    }
    xhr.send("message=" + encodeURIComponent(msg));
}
</script>

</body>
</html>
