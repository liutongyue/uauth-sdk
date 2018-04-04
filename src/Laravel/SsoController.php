<?php
/**
 * Created by PhpStorm.
 * User: liutongyue
 * Date: 2018/4/3
 * Time: 下午9:33
 */

namespace UAuth\SDK\Laravel;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;
use UAuth\SDK\UAuthManage;

/**
 * SSO相关的控制
 *
 * @package UAuth\SDK\Laravel
 */
class SsoController extends Controller
{
    /**
     * 注册UAuth的web路由
     */
    public static function route()
    {
        if (app()->routesAreCached()) {
            return;
        }

        $config = app('config')->get('entrust');

        \Route::get($config['route_prefix'] . '/to-login', '\\' . static::class . '@toLogin')->name('uauth.to_login');
        \Route::get($config['route_prefix'] . '/login-back', '\\' . static::class . '@loginBack')->name('uauth.login_back');
        \Route::get($config['route_prefix'] . '/logout', '\\' . static::class . '@logout')->name('uauth.logout');
    }

    /**
     * 生成SSO登录跳转
     *
     * @param Request $request
     * @param UAuthManage $authManage
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function toLogin(Request $request, UAuthManage $authManage)
    {
        $red_to = $authManage->buildLoginRedirectUrl(route('uauth.login_back'));

        $local_redirect = $request->input('referer', $request->header('referer', url('/')));

        //跳转到SSO登录页面
        return redirect($red_to)->with('local_redirect', $local_redirect);
    }

    /**
     * SSO登录成功跳转返回
     *
     * @param Request $request
     * @param UAuthManage $authManage
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|string
     */
    public function loginBack(Request $request, UAuthManage $authManage)
    {
        $config = app('config')->get('entrust');

        if (!$request->has('access')) {
            return '返回错误，请重新<a href="' . $config['index_url'] . '">登录</a>';
        }

        //获取用ID
        $loginInfo = $authManage->ssoBack($request->all());

        //登录
        if ($loginInfo && isset($loginInfo['user_id']) && Auth::loginUsingId($loginInfo['user_id'])) {

            //跳转回原来的页面
            return redirect($request->session()->get('local_redirect', '/'));
        }

        return '致命错误，请联系认证系统管理员';
    }

    /**
     * 登出
     *
     * @param Request $request
     * @param UAuthManage $authManage
     * @return mixed
     */
    public function logout(Request $request, UAuthManage $authManage)
    {
        $config = app('config')->get('entrust');

        //本地登出
        Auth::guard()->logout();

        //跳转SSO登出
        return redirect($authManage->buildLogoutRedirectUrl(url($config['index_url'])));
    }
}
