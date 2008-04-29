<?
require_once("config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PHP htpasswd admin - V<?=$VERSION?></title>
<script type="text/javascript" src="js/main.js"></script>
<style type="text/css">
	@import url(css/style.css);
</style>
</head>
<body>

<form action="update.php" name="form1" method="post" onsubmit="return ck();">
<table>
<tr>
<td>Username</td>
<td><input type="text" id="username" name="username">*</td>
</tr>
<tr>
<td>Old Passwd</td>
<td><input type="password" id="old_passwd" name="old_passwd">*</td>
<tr>
<td>New Passwd</td>
<td><input type="password" id="new_passwd" name="new_passwd">*</td>
</tr>
</table>

<input type="submit" name="submit" value="Update Passwd" onclick="document.all.form1.submit();"/>
</form>

<br/>
Your password will be synced to bumblebee 5 min later.
<br/>Powered by <a href="http://code.google.com/p/phphtpasswdadmin/">php htpasswd admin</a>
</body>
</html>

