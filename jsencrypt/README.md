##jsencrypt

####js的一个使用OpenSSLice加密，解密，秘钥生成类库      
**A Javascript library to perform OpenSSL RSA Encryption, Decryption, and Key Generation*** 

[github主页](https://github.com/travist/jsencrypt)    
[官方网站](http://travistidwell.com/jsencrypt/)    

####PHP关于OpenSSL相关资料

[PHP手册OpenSSL](http://php.net/manual/zh/book.openssl.php)   

####问题由来

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

前台的js参考jsencrypt类库使用说明和结合上面的博客园登录js，就能很快学会，比较简单！

####关于后台PHP解密  

1. 学习PHP手册：[PHP手册OpenSSL](http://php.net/manual/zh/book.openssl.php)  
2. 参考博客：[php rsa加密解密实例](http://blog.csdn.net/clh604/article/details/20224735)
  ```php
  <?php
$private_key = '-----BEGIN RSA PRIVATE KEY-----
MIICXQIBAAKBgQC3//sR2tXw0wrC2DySx8vNGlqt3Y7ldU9+LBLI6e1KS5lfc5jl
TGF7KBTSkCHBM3ouEHWqp1ZJ85iJe59aF5gIB2klBd6h4wrbbHA2XE1sq21ykja/
Gqx7/IRia3zQfxGv/qEkyGOx+XALVoOlZqDwh76o2n1vP1D+tD3amHsK7QIDAQAB
AoGBAKH14bMitESqD4PYwODWmy7rrrvyFPEnJJTECLjvKB7IkrVxVDkp1XiJnGKH
2h5syHQ5qslPSGYJ1M/XkDnGINwaLVHVD3BoKKgKg1bZn7ao5pXT+herqxaVwWs6
ga63yVSIC8jcODxiuvxJnUMQRLaqoF6aUb/2VWc2T5MDmxLhAkEA3pwGpvXgLiWL
3h7QLYZLrLrbFRuRN4CYl4UYaAKokkAvZly04Glle8ycgOc2DzL4eiL4l/+x/gaq
deJU/cHLRQJBANOZY0mEoVkwhU4bScSdnfM6usQowYBEwHYYh/OTv1a3SqcCE1f+
qbAclCqeNiHajCcDmgYJ53LfIgyv0wCS54kCQAXaPkaHclRkQlAdqUV5IWYyJ25f
oiq+Y8SgCCs73qixrU1YpJy9yKA/meG9smsl4Oh9IOIGI+zUygh9YdSmEq0CQQC2
4G3IP2G3lNDRdZIm5NZ7PfnmyRabxk/UgVUWdk47IwTZHFkdhxKfC8QepUhBsAHL
QjifGXY4eJKUBm3FpDGJAkAFwUxYssiJjvrHwnHFbg0rFkvvY63OSmnRxiL4X6EY
yI9lblCsyfpl25l7l5zmJrAHn45zAiOoBrWqpM5edu7c
-----END RSA PRIVATE KEY-----';

$public_key = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC3//sR2tXw0wrC2DySx8vNGlqt
3Y7ldU9+LBLI6e1KS5lfc5jlTGF7KBTSkCHBM3ouEHWqp1ZJ85iJe59aF5gIB2kl
Bd6h4wrbbHA2XE1sq21ykja/Gqx7/IRia3zQfxGv/qEkyGOx+XALVoOlZqDwh76o
2n1vP1D+tD3amHsK7QIDAQAB
-----END PUBLIC KEY-----';

//echo $private_key;
$pi_key =  openssl_pkey_get_private($private_key);//这个函数可用来判断私钥是否是可用的，可用返回资源id Resource id
$pu_key = openssl_pkey_get_public($public_key);//这个函数可用来判断公钥是否是可用的
print_r($pi_key);echo "\n";
print_r($pu_key);echo "\n";


$data = "aassssasssddd";//原始数据
$encrypted = ""; 
$decrypted = ""; 

echo "source data:",$data,"\n";

echo "private key encrypt:\n";

openssl_private_encrypt($data,$encrypted,$pi_key);//私钥加密
$encrypted = base64_encode($encrypted);//加密后的内容通常含有特殊字符，需要编码转换下，在网络间通过url传输时要注意base64编码是否是url安全的
echo $encrypted,"\n";

echo "public key decrypt:\n";

openssl_public_decrypt(base64_decode($encrypted),$decrypted,$pu_key);//私钥加密的内容通过公钥可用解密出来
echo $decrypted,"\n";

echo "---------------------------------------\n";
echo "public key encrypt:\n";

openssl_public_encrypt($data,$encrypted,$pu_key);//公钥加密
$encrypted = base64_encode($encrypted);
echo $encrypted,"\n";

echo "private key decrypt:\n";
openssl_private_decrypt(base64_decode($encrypted),$decrypted,$pi_key);//私钥解密
echo $decrypted,"\n";
  
  ```

####总结

使用OpenSSL的方式加密可以达到比较安全的级别，在平时项目中非常有用！在以后工作中可以多多学习、使用、积累经验。

