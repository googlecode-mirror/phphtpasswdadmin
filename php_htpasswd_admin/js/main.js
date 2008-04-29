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