<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Welcome to the SUPFile homepage!">
    <title>{$title|default:'Home'} | SUPFile</title>

    <link href="/public/stylesheet/vendor/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="/public/stylesheet/style.css" rel="stylesheet">
    <link href="/public/stylesheet/fonts/font-awesome/css/font-awesome.min.css" rel="stylesheet">

    <link href="/public/stylesheet/vendor/fine-uploader/fine-uploader-gallery.min.css" rel="stylesheet">

    <script src="/public/js/vendor/fine-uploader/fine-uploader.min.js"></script>
    <script type="text/template" id="qq-template">{include file='../inc/fine-uploader-template.tpl'}</script>

</head>
<body>
<header>
    <nav class="navbar">
        <a href="/"><img src="/public/img/logo/logo_supfile_blanc.png" width="80px"></a>
        {if !empty($session.connected)}
            <div class="dropdown">
                <div class="dropbtn">
                    <div>Hello, {$session.first_name}
                        <div>Your account</div>
                        <i class="fa fa-caret-down"></i>
                    </div>
                </div>
                <div class="dropdown-content">
                    <div id="arrow-container">
                        <div id="arrow"></div>
                    </div>
                    <ul>
                        <li><a href="/account/information">Profile</a></li>
                        <li><a href="/explorer">File Explorer</a></li>
                        <li><a href="/auth/logout">Disconnect</a></li>
                    </ul>
                </div>
            </div>
        {else}
            <div class="dropdown">
                <div class="dropbtn">
                    Hello, Sign In
                    <span>Your Account</span>
                    <i class="fa fa-caret-down"></i>
                </div>
                <div class="dropdown-content">
                    <div id="arrow-container">
                        <div id="arrow"></div>
                    </div>
                    <a class="btn-dropbtn" href="/auth/login">SIGN IN</a>
                    <div>Not registered ? <a href="/auth/register">sign up</a></div>
                </div>
            </div>
        {/if}
    </nav>
</header>
