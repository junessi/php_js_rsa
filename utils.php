<?php

function make_public_key($n_hex, $e_hex)
{
	$header_hex = '30819f300d06092a864886f70d010101050003818d0030818902818100';
	$tag_hex = '0203';
	$hex = $header_hex.$n_hex.$tag_hex.$e_hex;
	$b64 = base64_encode(hex2bin($hex));
	$tmp = str_split($b64, 64);
	$b64 = implode($tmp, "\n");
	$pub_key =	"-----BEGIN PUBLIC KEY-----\n".
				$b64."\n".
				"-----END PUBLIC KEY-----";
	return $pub_key;
}


