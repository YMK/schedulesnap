{* Smarty *}
{* The template for the index page *}
{extends file="mainPage.tpl"}

{block name="title" prepend}
Account
{/block}


{block name="body"}
<form target="account.php" method="POST">
<div id="userAccount">
<p>{$error}</p>

<p class='label'>Name</p> <input type="text" name="name" value="{$name}" class="{$nameclass} text" />
<p class='label'>Username</p> <input type="text" name="username" value="{$username}" 
                    class="{$userclass} text" />
<p class='label'>New Password</p><input type="password" name="newPword" class="{$npclass} text" /> 
<p class='label'>Current Password *</p><input type="password" name="currentPword" class="{$cpclass} text"/>
<input type="submit" name="changeDetails" value="Save"/>

<p class='label' style='clear:both;'>Delete account:</p></li>
<p class='label'>Current Password</p> <input type="password" name="currentPword2" class="{$cp2class} text" />
<input type="submit" name="delete" value="Delete"/>
</div>
</form>
{/block}