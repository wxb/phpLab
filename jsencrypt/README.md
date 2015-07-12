##博客园加密登录--jsencrypt

####问题由来   
前几天在做项目的时候，发现一般做登录的时候只是一个非常简单的`form`表单，但是这样肯定是不安全的！所以想去看看其他比较流行的网站是怎么实现的。说到安全，我第一个想到的就是去看支付宝，毕竟人家那么大的系统并且管理的是money啊! 结果，支付宝的登录确实复杂，chrome的F12竟然找不到。算了，看看我大博客园吧！

下面就是从博客园登录页面copy下来的js登录代码片段
博客园登录时采用的ajax发信http登录请求，就是采用jsencypt加密，以保护用户登录账号安全，一下是js片段代码
```js
 var return_url = 'http://home.cnblogs.com/u/wxb0328/';
        var ajax_url = '/user' + '/signin';
        var enable_captcha = false;
        var is_in_progress = false;

        function setFocus() {
            document.getElementById('input1').focus();
        }

        function check_enter(event) {
            if (event.keyCode == 13) {
                var target = event.target || event.srcElement;
                if (target.id == "input1") {
                    if (document.getElementById('input1').value == '') {
                        $('#tip_input1').html("请输入登录用户名");
                        return;
                    }
                    else if (document.getElementById('input2').value == '') {
                        document.getElementById("input2").focus();
                        return;
                    }
                }
                if (target.id == "input2") {
                    if (document.getElementById('input2').value == '') {
                        $('#tip_input2').html("请输入密码");
                        return;
                    }
                }
                signin_go();
            }
        }

        function signin_go() {
            if(is_in_progress){
                return;
            }

            $('#tip_input1').html('');
            $('#tip_input2').html('');

            var input1 = $.trim($('#input1').val());
            if (!input1) {
                $('#tip_input1').html("请输入登录用户名");
                $('#input1').focus();
                return;
            }
            var input2 = $.trim($('#input2').val());
            if (!input2) {
                $('#tip_input2').html("请输入密码");
                $('#input2').focus();
                return;
            }

            if(enable_captcha)
            {
                var captchaCode = $.trim($('#captcha_code_input').val());
                if (!captchaCode)
                {
                    $('#tip_captcha_code_input').html("请输入验证码");
                    $('#captcha_code_input').focus();
                    return;
                }
            }

            $('#tip_btn').html('提交中...');

            var encrypt = new JSEncrypt();
            encrypt.setPublicKey('MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCp0wHYbg/NOPO3nzMD3dndwS0MccuMeXCHgVlGOoYyFwLdS24Im2e7YyhB0wrUsyYf0/nhzCzBK8ZC9eCWqd0aHbdgOQT6CuFQBMjbyGYvlVYU2ZP7kG9Ft6YV6oc9ambuO7nPZh+bvXH0zDKfi02prknrScAKC0XhadTHT3Al0QIDAQAB');
            var encrypted_input1 = encrypt.encrypt($('#input1').val());
            var encrypted_input2 = encrypt.encrypt($('#input2').val());
            var ajax_data = {
                input1: encrypted_input1,
                input2: encrypted_input2,
                remember: $('#remember_me').prop('checked')
            };

            if(enable_captcha){
                var captchaObj = $("#captcha_code_input").get(0).Captcha;
                ajax_data.captchaId = captchaObj.Id;
                ajax_data.captchaInstanceId = captchaObj.InstanceId;
                ajax_data.captchaUserInput = $("#captcha_code_input").val();
            }
            is_in_progress = true;
            $.ajax({
                url: ajax_url,
                type: 'post',
                data: JSON.stringify(ajax_data),
                contentType: 'application/json; charset=utf-8',
                dataType: 'json',
                headers: {
                    'VerificationToken': 'v8o6Zbt1bkghAprMyn-NuKazyejPBAjz21CtWG81iKijaJ5u4u37q22GSdkbB9aqIc9deUoBETwjJxGFh0jLPZTSnXc1:EnF2TIFu9G2Kd0NgQuSFsXzcWtXcEj7AdxylV6Rlt7k5rcmZOu3j9bcoPO1kiBKGjg1V0WFB9dj8rWXIW34di1ONV6c1'
                },
                success: function (data) {                    
                    if (data.success) {
                        $('#tip_btn').html('登录成功，正在重定向...');
                        location.href = return_url;
                    } else {
                        $('#tip_btn').html(data.message + "<br/><br/>联系 contact@cnblogs.com");
                        is_in_progress = false;
                        if(enable_captcha)
                        {
                            captchaObj.ReloadImage();
                        }
                    }
                },
                error: function (xhr) {
                    is_in_progress = false;
                    $('#tip_btn').html('抱歉！出错！联系 contact@cnblogs.com');
                }
            });
        }

        $(function () {
            $('#signin').bind('click', function () {
                signin_go();
            }).val('登 录');

        });
```

可以看到博客园使用了一个js加密类库：jsencrypt，下面就来了解一下这个类库！

####js的一个使用OpenSSL加密，解密，秘钥生成类库      
**A Javascript library to perform OpenSSL RSA Encryption, Decryption, and Key Generation*** 
这里不对jsencrypt这个js类库做详细的介绍了，大家有兴趣可以去它的github主页学习，其实这个类库还是相对比较简单的，只要稍微看看就应该可以掌握。

[github主页](https://github.com/travist/jsencrypt)    
[官方网站](http://travistidwell.com/jsencrypt/)    

####PHP关于OpenSSL相关资料

[PHP手册OpenSSL](http://php.net/manual/zh/book.openssl.php)  

####关于后台PHP解密  

1. 学习PHP手册：[PHP手册OpenSSL](http://php.net/manual/zh/book.openssl.php)  
2. 参考博客：[php rsa加密解密实例](http://blog.csdn.net/clh604/article/details/20224735)   

####下面贴出我自己写的测试demo
**test.html**
 ```html
 <!doctype html>
<html>
  <head>
    <title>JavaScript RSA Encryption</title>
    <script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
    <script src="./jsencrypt.min.js"></script>
    <script type="text/javascript">
// 使用jsencrypt类库加密js方法，
function encryptRequest(reqUrl, data, publicKey){
    var encrypt = new JSEncrypt();
    encrypt.setPublicKey(publicKey);
    // ajax请求发送的数据对象
    var sendData = new Object();
    // 将data数组赋给ajax对象
    for(var key in data){
        sendData[key] = encrypt.encrypt(data[key]);
    }

      $.ajax({
            url: reqUrl,
            type: 'post',
            data: sendData,
            dataType: 'json',
            success: function (data) {                    
                console.info(data);
            },
            error: function (xhr) {
                console.error('出错了');
            }
        });

}

      // Call this code when the page is done loading.
      $(function() {

        $('#testme').click(function() {
            
            var data = [];
            data['username']= $('#username').val();
            data['passwd']= $('#passwd').val();

            var pkey = $('#pubkey').val();
            encryptRequest('./test.php', data, pkey);
        });
      });
    </script>
  </head>
  <body>
    <label for="pubkey">Public Key</label><br/>
    <textarea id="pubkey" rows="15" cols="65">-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCBWNoG5LJ3u44Gs8PWs1MaNUQQ
+mOmh+9zWdzSt3ORbmfCDvU+ssW/6QTTgXvWWx7+Wzq/a4fCCQp72zSqXeVhWkTV
ct9Hyp/iMo5K6qOEK76z9z+tP/u99X6qazeXGVMWKkPiyZT4mKAGd/U8Mph9Z1Z5
kOluA7g7heq8PPlE9wIDAQAB
-----END PUBLIC KEY-----</textarea><br/>
    <label for="input">Text to encrypt:</label><br/>
    name:<input id="username" name="username" type="text"></input><br/>
    password:<input id="passwd" name="passwd" type="password"></input><br/>
    <input id="testme" type="button" value="submit" /><br/>
  </body>
</html>

 ```
 
  ```php
 <?php
    
// 接收客户端发送过来的经过加密的登录信息
$input = $_POST;
// 私钥是放在服务器端的，用以验证和解密客户端经过公钥加密后的信息
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

// 公钥一般存放在登录页面中的一个隐藏域中，但是请注意：公钥和私钥一定要配对，且必须保证私钥的安全
$public_key = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCBWNoG5LJ3u44Gs8PWs1MaNUQQ
+mOmh+9zWdzSt3ORbmfCDvU+ssW/6QTTgXvWWx7+Wzq/a4fCCQp72zSqXeVhWkTV
ct9Hyp/iMo5K6qOEK76z9z+tP/u99X6qazeXGVMWKkPiyZT4mKAGd/U8Mph9Z1Z5
kOluA7g7heq8PPlE9wIDAQAB
-----END PUBLIC KEY-----';

/**
 * 使用PHP OpenSSL时，最好先看看手册，了解如何开启OpenSSL 和 其中的一些方法的使用
 *  具体如何使用这里不做赘述，大家去看看PHP手册，什么都就解决了
 */ 
$pi_key =  openssl_pkey_get_private($private_key);//这个函数可用来判断私钥是否是可用的，可用返回资源id Resource id  
$pu_key = openssl_pkey_get_public($public_key);//这个函数可用来判断公钥是否是可用的  

$decrypted = "";  
openssl_private_decrypt(base64_decode($input['username']),$decrypted,$pi_key);//私钥解密  
// 这里的这个 $decrypted就是解密客户端发送过来的用户名，至于后续连接数据库验证登录信息的代码，这里也就省略了
echo json_encode($decrypted);

  
  ```

####总结

使用OpenSSL的方式加密可以达到比较安全的级别，在平时项目中非常有用！在以后工作中可以多多学习、使用、积累经验。

