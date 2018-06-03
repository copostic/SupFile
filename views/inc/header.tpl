<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Welcome to the SUPFile homepage!">
    <title>Home | SUPFile</title>

    <link href="/public/stylesheet/vendor/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="/public/stylesheet/style.css" rel="stylesheet">
    <link href="/public/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="/public/stylesheet/pages/explorer.css" rel="stylesheet" />
{*
    <base href="http://supfile.tk">
*}

</head>
<body>
<header>
    <nav class="navbar">
        <a href="/"><img src="/public/img/logo/logo_SUPFile.png" width="80px"></a>
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
                        <li><a href="">Profile</a></li>
                        <li><a href="">Upload</a></li>
                        <li><a href="">Download</a></li>
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
                    <div>Not registered ? <a href="/auth/login">sign up</a></div>
                </div>
            </div>
        {/if}
    </nav>
</header>
