<?php
/**
 * Created by PhpStorm.
 * User: liutongyue
 * Date: 2018/4/3
 * Time: 下午9:45
 */

namespace UAuth\SDK;

use UAuth\SDK\Library\Crypt\Aes;

class UAuthManage
{
    public $config = [];

    protected $cryptModule;

    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * 获取加密模块
     *
     * @return Aes
     */
    public function getCryptModule()
    {
        if (!$this->cryptModule) {
            $this->cryptModule = new Aes($this->config['crypt_key']);
        }

        return $this->cryptModule;
    }

    /**
     * 生成用户登录SSO的跳转URL
     *
     * @param $redirect_back
     *
     * @return string
     */
    public function buildLoginRedirectUrl($redirect_back)
    {
        $host = $this->config['uauth_host'];

        $redirect_to = $host . '/sso/oauth?' . http_build_query($this->redirectPayload($redirect_back));

        return $redirect_to;
    }

    /**
     * 生成用户登出SSO的跳转URL
     *
     * @param $redirect_back
     *
     * @return string
     */
    public function buildLogoutRedirectUrl($redirect_back)
    {
        $host = $this->config['uauth_host'];

        $redirect_to = $host . '/sso/logout?' . http_build_query($this->redirectPayload($redirect_back));

        return $redirect_to;
    }

    /**
     * 构建跳转参数
     *
     * @param $redirect_back
     *
     * @return mixed
     */
    protected function redirectPayload($redirect_back)
    {
        $crypt_key = $this->config['crypt_key'];
        $params['app_id'] = $this->config['app_id'];
        $params['redirect_url'] = $redirect_back;
        $params['time'] = time();
        ksort($params);
        $params['sign_key'] = sha1($crypt_key . http_build_query($params));

        return $params;
    }

    /**
     * 用户SSO登录返回结果解析
     *
     * @param $data
     *
     * @return array
     */
    public function ssoBack($data)
    {
        $aes = $this->getCryptModule();

        return json_decode($aes->decrypt($data['access']), true);
    }

}