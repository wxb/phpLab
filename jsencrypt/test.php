<?php
    
$input = $_POST;

$private_key = '-----BEGIN RSA PRIVATE KEY-----
MIICXAIBAAKBgQCBWNoG5LJ3u44Gs8PWs1MaNUQQ+mOmh+9zWdzSt3ORbmfCDvU+
ssW/6QTTgXvWWx7+Wzq/a4fCCQp72zSqXeVhWkTVct9Hyp/iMo5K6qOEK76z9z+t
P/u99X6qazeXGVMWKkPiyZT4mKAGd/U8Mph9Z1Z5kOluA7g7heq8PPlE9wIDAQAB
AoGABPQwNX9gznEieWM9JuXrUt+jYbsVQfWG2DYi3Pclt/YwhyAniGU0aas1Ahy9
b3JB95/q2hX2Nxo9iozUsYmzFT99dm2HBsHDnpnUgpyDtGo9sXlhLktyey53UKRx
QJkW5dWWUQfssNrCe08N3vtLiDIy04lRQ8F0eJ/iklzk1HECQQC22pOz7V2K5/50
w9LA9UBSl7wWhTTY5G1gsBEm5tNmbM/ZqCJ1FXB4uuDgz0o0N0x8T8JkkPrRWH5q
GIHFRswVAkEAtRbV8PoLnyT73hxtCw0F17aaI8W5AGhvsbjdA6nMo6byBR5xKN+7
lalfXYEfXPnStHVNSnQVFjN3T06iJV6z2wJBAKj51rLYcLBT8XbQG+vK+FUa+WrK
UGr6tQU7z63mc4dcmLtoP+d5F4XKFNRLWyRj0d+zCU5MGCzrnW7IFOxMn30CQEtv
4N3K/C5mtLmZM9+npChxfBKs2l2OJAFwFjnhcUs3T5jMTq2NTlKRRRXppUwREjJ0
ryb15pbiB7C0/Bz/L4MCQC1AOKKjnqQEpINatjZLkyay0bXBih9GXovz3T1eAxaS
QOEzIC+hGjX+2x1z5jUwwKCgjVUaZdrx470SMJM2Js8=
-----END RSA PRIVATE KEY-----';

$public_key = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCBWNoG5LJ3u44Gs8PWs1MaNUQQ
+mOmh+9zWdzSt3ORbmfCDvU+ssW/6QTTgXvWWx7+Wzq/a4fCCQp72zSqXeVhWkTV
ct9Hyp/iMo5K6qOEK76z9z+tP/u99X6qazeXGVMWKkPiyZT4mKAGd/U8Mph9Z1Z5
kOluA7g7heq8PPlE9wIDAQAB
-----END PUBLIC KEY-----';

$pi_key =  openssl_pkey_get_private($private_key);//这个函数可用来判断私钥是否是可用的，可用返回资源id Resource id  
$pu_key = openssl_pkey_get_public($public_key);//这个函数可用来判断公钥是否是可用的  


$decrypted = "";  
openssl_private_decrypt(base64_decode($input['username']),$decrypted,$pi_key);//私钥解密  
echo $decrypted,"\n";  

