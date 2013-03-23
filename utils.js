/*****************************************************
 * functions in this file:
 *	jsbn_encrypt(plain_text, n, e)
 *	jsbn_decrypt(decrypted_data, private_key)
 *	jsbn_generate(private_key)
 *	set_cookie
 *	get_cookie
 *	del_cookie
 ****************************************************/

/*****************************************************
 *	encrypt a plain text
 *	plaintext: the plain text would be encrypted
 *	n: n from public key
 *	e: e from public key
 *	return: return the ciphertext on success, otherwise ""
 *****************************************************/
function jsbn_encrypt(plain_text, n, e) {
	if(!plain_text.length || !n.length || !e.length)
		return "";

	var rsa = new RSAKey();
	rsa.setPublic(n, e);
	var res = rsa.encrypt(plain_text);
	if(res) {
		return res;
	}
	return "";
}

/*****************************************************
 *	decrypt a ciphertext
 *	ciphertext: encrypted data
 *	return: return plain text on success, otherwise ""
 *****************************************************/
function jsbn_decrypt(encrypted_data, n, e, d, p, q, dmp1, dmq1, coeff) {
	if(!encrypted_data.length || !n.length || !e.length || !d.length || !p.length || !q.length || !dmp1.length || !dmq1.length || !coeff.length)
		return "";

	var rsa = new RSAKey();
	rsa.setPrivateEx(n, e, d, p, q, dmp1, dmq1, coeff);
	var res = rsa.decrypt(encrypted_data);
	if(res)
		return res;

	return "";
}

/*****************************************************
 *	generate a RSA private key
 *	e and bits are required to do this
 *****************************************************/
function jsbn_generate_rsa(e, bits) {
	var rsa = new RSAKey();
	rsa.generate(parseInt(bits), e);
	set_cookie("n", rsa.n.toString(16));
	set_cookie("e", e);
	set_cookie("d", rsa.d.toString(16));
	set_cookie("p", rsa.p.toString(16));
	set_cookie("q", rsa.q.toString(16));
	set_cookie("dmp1", rsa.dmp1.toString(16));
	set_cookie("dmq1", rsa.dmq1.toString(16));
	set_cookie("coeff", rsa.coeff.toString(16));
	set_cookie("bits", bits);
}

function set_cookie(name, value) {
	document.cookie = name + "=" + escape(value);
}

function get_cookie(name) {
	var i, start, end;
	var str = name + "=";
	var value = "";
	i = document.cookie.indexOf(str);
	if(i >= 0) {
		start = i + str.length;
		end = document.cookie.indexOf(";", start);
		value = document.cookie.substr(start, end - start);
		if(value.length == 0)
			return "";
		return unescape(value);
	}

	return "";
}

function del_cookie(name) {
	set_cookie(name, "");
}


