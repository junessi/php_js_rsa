php_js_rsa
==========

This is a demo that shows the communication based on RSA between php(server) and javascript(user)

The user submits a encrypted-URL which encrypted with the public key from the server, then the server decrypts 
the encrypted-URL and encrypts again a extra message includes the plain URL with the client public key, after user
received the encrypted-URL, he can decrypted it with users own private key.
