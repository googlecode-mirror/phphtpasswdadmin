<?php
require_once("auth.php");

$username = $_POST['username'];
$old_passwd = $_POST['old_passwd'];
$new_passwd = $_POST['new_passwd'];

$auth = new CAuth();

$response = "";
if ($auth->isValidPasswd($username, $old_passwd) == false)
	$response = "Invalid username or password!";
else
{
	$auth->updatePasswd($username, $new_passwd);
	$auth->save();
	$response = "Password updated!";
}

echo "<script>window.alert('".$response."');this.location.href='index.php'</script>";
?>

