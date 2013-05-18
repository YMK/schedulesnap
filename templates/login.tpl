{* Smarty *}
{* The template for the index page *}
{extends file="mainPage.tpl"}

{block name="title" prepend}
Login
{/block}

{block name="body"}
    {$logerror}
    <form id='loginform' method='post' action='login.php'>
            <p class='label'>Username:</p>
            <input type='text' name='username' />
            <p class='label'>Password:</p>
            <input type='password' name='password' />
            <a href='register.php'><p class='label'>Register</p></a>
            <input type='submit' name='login' value='login'/>
    </form>
{/block}