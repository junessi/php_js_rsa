<?php

require_once 'utils.php';

$pri_key = openssl_pkey_get_private(file_get_contents('private.pem'));
$details = openssl_pkey_get_details($pri_key);

if(isset($_POST['ciphertext']))
{
	if(openssl_private_decrypt(hex2bin($_POST['ciphertext']), $decrypted_data, $pri_key))
	{
		/*
		echo 'Ciphertext: '.$_POST['ciphertext'].'<br>'.
				'n => '.$_POST['usr_pub_n'].'<br>'.
				'e => '.$_POST['usr_pub_e'].'<br>'.
				'URL => '.$decrypted_data;
				*/
		$usr_pub = openssl_pkey_get_public(make_public_key($_POST['usr_pub_n'], $_POST['usr_pub_e']));
		openssl_public_encrypt('Requested URL: '.$decrypted_data, $encrypted_data, $usr_pub);
	}
}

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>JavaScript RSA Cryptography Demo</title>
	<script language="JavaScript" type="text/javascript" src="scripts/jsbn.js"></script>
	<script language="JavaScript" type="text/javascript" src="scripts/jsbn2.js"></script>
	<script language="JavaScript" type="text/javascript" src="scripts/prng4.js"></script>
	<script language="JavaScript" type="text/javascript" src="scripts/rng.js"></script>
	<script language="JavaScript" type="text/javascript" src="scripts/rsa.js"></script>
	<script language="JavaScript" type="text/javascript" src="scripts/rsa2.js"></script>
	<script language="JavaScript" type="text/javascript" src="utils.js"></script>
	<script language="JavaScript">

	function init() {
		if(get_cookie("n") == "" || get_cookie("e") == "") {
			jsbn_generate_rsa(document.user_key.e.value, document.user_key.bits.value);
		}
	}

	function submit_url() {
		document.url.ciphertext.value = jsbn_encrypt(document.rsatest.url.value,
														document.server_key.n.value,
														document.server_key.e.value);
		document.url.usr_pub_n.value = get_cookie("n");
		document.url.usr_pub_e.value = get_cookie("e");
		document.url.submit();
	}

	function decrypt_url() {
		document.rsatest.dec_data.value = jsbn_decrypt(document.rsatest.enc_data.value,
														get_cookie("n"),
														get_cookie("e"),
														get_cookie("d"),
														get_cookie("p"),
														get_cookie("q"),
														get_cookie("dmp1"),
														get_cookie("dmq1"),
														get_cookie("coeff"));
	}

	function delete_cookies() {
		set_cookie("n", "");
		set_cookie("e", "");
		set_cookie("d", "");
		set_cookie("p", "");
		set_cookie("q", "");
		set_cookie("dmp1", "");
		set_cookie("dmq1", "");
		set_cookie("coeff", "");
		set_cookie("bits", "");
	}

	function show_cookies() {
		var i;
		var str = "", cookies = "";

		cookies = document.cookie.split(";");
		for(i = 0;i < cookies.length;i++) {
			str += cookies[i];
			str += "\n";
		}
		alert(str);
	}
	</script>
</head>
<body onload="init();">
<h1>Encrypt URL with RSA Demo</h1>

<form name="server_key">
	<input type="hidden" name="n" value="<?=isset($details['rsa']['n'])?bin2hex($details['rsa']['n']):'' ?>" />
	<input type="hidden" name="e" value="<?=isset($details['rsa']['e'])?bin2hex($details['rsa']['e']):'' ?>" />
</form>

<form name="rsatest" method="post">
	URL:
	<input name="url" value="<?=empty($decrypted_data)?'':$decrypted_data ?>" />
	<input type="button" value="GO" onClick="submit_url();" /><br><br>
	The encrypted data returned by the serevr:<br>
	<textarea name="enc_data" rows="8" cols="64"><?=empty($encrypted_data)?'':bin2hex($encrypted_data) ?></textarea><br><br>
	Click "decrypt" to show the plain message from the server:<br>
	<textarea name="dec_data" rows="8" cols="64"></textarea><br>
	<input type="button" value="decrypt" onclick="javascript:decrypt_url();" />
	<input type="button" value="generate key" onclick="javascript:jsbn_generate_rsa(document.user_key.e.value, document.user_key.bits.value);" />
	<input type="button" value="show cookies" onclick="javascript:show_cookies();" />
	<input type="button" value="delete cookies" onclick="javascript:delete_cookies();" />
</form> 
<form name="url" action="proxy.php" method="post">
	<input type="hidden" name="ciphertext" value="" />
	<input type="hidden" name="usr_pub_n" value="" />
	<input type="hidden" name="usr_pub_e" value="" />
</form> 
</body>
</html>
