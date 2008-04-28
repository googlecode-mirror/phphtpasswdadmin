<?
require_once ("config.php");

class CItem {
	private $_name;
	private $_tag = "apr1";
	private $_salt;
	private $_passwd;

	function CItem($name = "", $salt = "", $passwd = "") {
		$this->_name = $name;
		$this->_salt = $salt;
		$this->_passwd = $passwd;
	}

	public function getTag() {
		return $this->_tag;
	}

	public function getName() {
		return $this->_name;
	}

	public function getSalt() {
		return $this->_salt;
	}

	public function getPasswd() {
		return $this->_passwd;
	}

	public function setPasswd($passwd) {
		$this->_passwd = $passwd;
	}
}

class CAuth {
	private $_list = array ();

	function __construct() {
		$this->loadAuthFile();
	}

	private function loadAuthFile() {
		global $AUTH_USER_FILE;

		$lines = split("\n", file_get_contents($AUTH_USER_FILE));

		for ($i = 0; $i < count($lines); ++ $i) {
			if (trim($lines[$i]) == "")
				continue;

			$tmp = explode(":", $lines[$i]);
			$_user = $tmp[0];

			$tmp = explode("$", $lines[$i]);
			$_salt = $tmp[2];
			$_passwd = $tmp[3];

			$item = new CItem($_user, $_salt, $_passwd);
			$this->_list[] = $item;
		}
	}

	public function size() {
		return count($this->_list);
	}

	function apr1_md5($plainpasswd, $salt) {
		// $salt = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789"), 0, 8);

		$len = strlen($plainpasswd);
		$text = $plainpasswd . '$apr1$' . $salt;
		$bin = pack("H32", md5($plainpasswd . $salt . $plainpasswd));
		for ($i = $len; $i > 0; $i -= 16) {
			$text .= substr($bin, 0, min(16, $i));
		}
		for ($i = $len; $i > 0; $i >>= 1) {
			$text .= ($i & 1) ? chr(0) : $plainpasswd {
				0 };
		}
		$bin = pack("H32", md5($text));
		for ($i = 0; $i < 1000; $i++) {
			$new = ($i & 1) ? $plainpasswd : $bin;
			if ($i % 3)
				$new .= $salt;
			if ($i % 7)
				$new .= $plainpasswd;
			$new .= ($i & 1) ? $bin : $plainpasswd;
			$bin = pack("H32", md5($new));
		}

		for ($i = 0; $i < 5; $i++) {
			$k = $i +6;
			$j = $i +12;
			if ($j == 16)
				$j = 5;
			$tmp = $bin[$i] . $bin[$k] . $bin[$j] . $tmp;
		}
		$tmp = chr(0) . chr(0) . $bin[11] . $tmp;
		$tmp = strtr(strrev(substr(base64_encode($tmp), 2)), "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/", "./0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz");

		//return "$"."apr1"."$".$salt."$".$tmp;
		return $tmp;
	}

	public function isValidPasswd($user, $passwd) {
		for ($i = 0; $i < $this->size(); ++ $i) {
			$item = $this->_list[$i];
			if ($item->getName() == $user) {
				if ($item->getPasswd() == $this->apr1_md5($passwd, $item->getSalt())) {
					return true;
				}

				break;
			}
		}

		return false;
	}

	public function updatePasswd($user, $passwd) {
		for ($i = 0; $i < $this->size(); ++ $i) {
			$item = $this->_list[$i];
			if ($item->getName() == $user) {
				$item->setPasswd($this->apr1_md5($passwd, $item->getSalt()));
				return true;
			}
		}

		return false;
	}

	public function save() {
		global $AUTH_USER_FILE;

		$fp = fopen($AUTH_USER_FILE, "w");

		for ($i = 0; $i < $this->size(); ++ $i) {
			$item = $this->_list[$i];
			$line = $item->getName() . ":$" . $item->getTag() . "$" . $item->getSalt() . "$" . $item->getPasswd();

			if ($i < $this->size() - 1)
				$line = $line . "\n";

			fwrite($fp, $line);
		}

		fclose($fp);
	}
}
?>
