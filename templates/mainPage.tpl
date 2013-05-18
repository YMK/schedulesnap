{* Smarty *}
<!DOCTYPE html>
<html>
<head>
{* Setting up the metatags for the html*}
<link href='http://fonts.googleapis.com/css?family=Merriweather' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="css/main.css" />
<link rel="stylesheet" type="text/css" href="css/images.css" />
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />
{block name="headers"}

{/block}
<script>
    {*this is purely a way of transferring some information to the javascript*}
    {block name="javascript"}
    
    {/block}
</script>
<script src="js/jquery-1.9.1.js"></script>
<script src="js/jquery-ui.js"></script>
<script data-main="js/main.js" type="text/javascript" src="js/require.js"></script>
<script type="text/javascript" src="http://www.appelsiini.net/download/jquery.jeditable.mini.js"> </script>

<title>{block name="title"} - Schedule Snap - Timetable Manager {/block}</title>
</head>

{* Body is after here *}
<body>
<div id='container'>

  <div id='header'>
    <div id='title'>
        <h1><a href='index.php'>Generic Timetabling</a></h1>
    </div> <!-- title -->
    <div id='account'>
        <p id='myaccountp'>{$myaccount}</p>
    </div> <!-- account -->
    {if $loggedin == 'true'}
    <div id='useraccount'>
        <ul style="text-align: right;">
            <li><a href='createRota.php' class='none'>New rota</a></li>
            <li><a href='index.php' class='none'>My rotas</a></li>
            <br />
            
            <li><a href='account.php' class='none'>Manage Account</a></li>
            <li><a href='logout.php' class='none'>Log out</a></li>
        </ul>
    </div>
    {else}
    <div id='login'>
        <form id="loginform" method="post" action="login.php">
            <input type="text" name="username" value="Username" 
                   onclick="this.value='';"/>
            <input type="password" name="password" value="Password" 
                   onclick="this.value='';" />
            <input type="hidden" name="url" value="{$url}" />
            <input type="submit" name="login" value="login" />
            <a href="register.php" style="float:right; clear:right;">Register</a>
        </form>
    </div>
    {/if}

</div> <!-- header -->
<div id='main'>

{block name="body"}

{/block}

</div> <!-- main -->

<div id='footer'>

</div> <!-- footer -->
</div> <!-- container -->
</body>
</html>

