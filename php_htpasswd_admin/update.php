<?php

require_once("config.php");

function c($plainpasswd, $salt) {
    // $salt = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789"), 0, 8);

    $len = strlen($plainpasswd);
    $text = $plainpasswd.'$apr1$'.$salt;
    $bin = pack("H32", md5($plainpasswd.$salt.$plainpasswd));
    for($i = $len; $i > 0; $i -= 16) { $text .= substr($bin, 0, min(16, $i)); }
    for($i = $len; $i > 0; $i >>= 1) { $text .= ($i & 1) ? chr(0) : $plainpasswd{0}; }
    $bin = pack("H32", md5($text));
    for($i = 0; $i < 1000; $i++) {
        $new = ($i & 1) ? $plainpasswd : $bin;
        if ($i % 3) $new .= $salt;
        if ($i % 7) $new .= $plainpasswd;
        $new .= ($i & 1) ? $bin : $plainpasswd;
        $bin = pack("H32", md5($new));
    }
    for ($i = 0; $i < 5; $i++) {
        $k = $i + 6;
        $j = $i + 12;
        if ($j == 16) $j = 5;
        $tmp = $bin[$i].$bin[$k].$bin[$j].$tmp;
    }
    $tmp = chr(0).chr(0).$bin[11].$tmp;
    $tmp = strtr(strrev(substr(base64_encode($tmp), 2)),
    "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/",
    "./0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz");
    return "$"."apr1"."$".$salt."$".$tmp;
}

function update($user, $old_passwd, $new_passwd)
{
	global $AUTH_USER_FILE;
	
	$lines = split("\n", file_get_contents($AUTH_USER_FILE));
	
	for ($i=0; $i<count($lines); ++$i)
	{
		$tmp = split(":", $lines[$i]);
		$_user = $tmp[0];
		$_passwd = $tmp[1];

		if ($_user == $user)
		{
			$tmp = explode("$", $lines[$i]);
			$salt = $tmp[2];

			if (c($old_passwd, $salt) == $_passwd)
			{
				// update
				$lines[$i] = $user.":".c($new_passwd, $salt);
				$fp = fopen($AUTH_USER_FILE, "w");
				for ($j=0; $j<count($lines)-1; ++$j)
				{
					fwrite($fp, $lines[$j]."\n");
				}
				fwrite($fp, $lines[$j]);

				fclose($fp);
				
				return "Passwd Updated";
			}
			else
			{
				return "Incorrect Password";
			}
		}
	}
	
	return "User Not Found";
}


$username = $_POST['username'];
$old_passwd = $_POST['old_passwd'];
$new_passwd = $_POST['new_passwd'];

$result = update($username, $old_passwd, $new_passwd);

echo "<script>window.alert('".$result."');this.location.href='index.php'</script>";
?>

