<?php
/**
 * Created by PhpStorm.
 * User: liutongyue
 * Date: 2018/4/2
 * Time: 上午11:01
 */

namespace UAuth\SDK\Library\Crypt;


class Aes
{
    protected $method = 'aes-256-cbc';

    protected $iv = '';
    protected $key = '';
    protected $options = 0;

    public function __construct($key, $method = 'aes-256-cbc', $iv = '1rminjuuFarsfQSk', $options = OPENSSL_RAW_DATA)
    {
        if (openssl_cipher_iv_length($method) != strlen($iv)) {
            throw new AesException('IV向量长度错误');
        }

        $this->iv = $iv;
        $this->key = $key;
        $this->options = $options;
    }

    /**
     * 加密
     * @param $data
     * @return string
     * @throws AesException
     */
    public function encrypt($data)
    {
        $encrypt_data = openssl_encrypt($data, $this->method, $this->key, $this->options, $this->iv);

        if ($encrypt_data === false) {
            throw new AesException(openssl_error_string(), 1102);
        }

        return base64_encode($encrypt_data);
    }

    /**
     * 解密
     * @param $payload
     * @return string
     * @throws AesException
     */
    public function decrypt($payload)
    {
        $encrypt_data = base64_decode($payload);

        $decrypt_data = openssl_decrypt($encrypt_data, $this->method, $this->key, $this->options, $this->iv);

        if ($decrypt_data === false) {
            throw new AesException(openssl_error_string(), 1103);
        }

        return $decrypt_data;
    }
}