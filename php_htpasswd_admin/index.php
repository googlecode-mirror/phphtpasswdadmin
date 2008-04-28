<html>
<head>
<title>Update Passwd</title>
<script>
function ck()
{
	var u = document.getElementById("username");
	var op = document.getElementById("old_passwd");
	var np = document.getElementById("new_passwd");

	if (u.value == '' || op.value == '' || np.value == '')
	{
		alert('Invalid Username or Password');
		return false;
	}

	return true;
}
</script>
</head>
<body>

<form action="update.php" name="form1" method="post" onsubmit="return ck();">
<table>
<tr>
<td>Username</td>
<td><input type="text" id="username" name="username"></td>
</tr>
<tr>
<td>Old Passwd</td>
<td><input type="password" id="old_passwd" name="old_passwd"></td>
<tr>
<td>New Passwd</td>
<td><input type="password" id="new_passwd" name="new_passwd"></td>
</tr>
</table>

<input type="submit" name="submit" value="Update Passwd" onclick="document.all.form1.submit();"/>
</form>

<br/>
Your password will be synced to bumblebee 5 min later.
<br/>Powered by <a href="http://code.google.com/p/phphtpasswdadmin/">php htpasswd admin</a>
</body>
</html>

