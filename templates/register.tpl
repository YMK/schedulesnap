{* Smarty *}
{* The template for the index page *}
{extends file="mainPage.tpl"}

{block name="title" prepend}
Register
{/block}

{block name="body"}
    {$logerror}
    <form id='loginform' method='post' action='register.php'>
            <p class='label'>Username:</p>
            <input type='text' name='username' />
            <p class='label'>Password:</p>
            <input type='password' name='password' />
            <p class='label'>Full name:</p>
            <input type='text' name='name' />
            <input type='submit' name='register' value='register'/>
    </form>
{/block}

