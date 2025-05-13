<?php session_start();
if (isset($_SESSION['username']) || isset($_COOKIE['username'])) { header('Location: client.php');exit(); }
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    if (isset($_POST['remember_me']) && $_POST['remember_me'] == 'y') { setcookie('username', $username, '/'); } 
    $_SESSION['username'] = $username; header('Location: client.php'); exit();
}?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>AxolotlSMP</title>
        <meta name="viewport" content="width=device-width">
        <meta name="theme-color" content="#9873ac">
        <meta name="canonical-url" content="https://ccube.gunawan092w1.eu.org">
        <meta name="description" content="AxolotlSMP Classic - Powered by ClassiCube">

        <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700' rel='stylesheet' type='text/css'>
        <link href="https://classicube.net/scss/v2/style/scss/style.scss?v=30" rel="stylesheet" type="text/css">
        
        <style>
            @import url("https://classicube.net/scss/v2/style/scss/dark.scss?v=30") (prefers-color-scheme: dark);
        </style>
    </head>
    <body>
        <div id="header">
            <div class="row">
                <a href="/"><h1 class="small-12 medium-1 columns">AxolotlSMP</h1></a>
            </div>
        </div>

        <div id="body">
        <div class="row">
			<br>
			<div style="text-align:center;"><h2>Log in</h2><p>Enter your username to start playing!</p></div>
			<form method="post" id="loginForm">
			  <div class="small-12 medium-6 medium-offset-3 columns">
				  <input id="username" name="username" placeholder="Username" required type="text" value=<?php echo isset($_COOKIE['username']) ? $_COOKIE['username'] : ''; ?>>
                <input type=submit value="Log in" class="button" style="float:right;">
    </body>
</html>