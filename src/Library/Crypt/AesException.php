<?php
/**
 * Created by PhpStorm.
 * User: liutongyue
 * Date: 2018/4/2
 * Time: 上午11:01
 */

namespace UAuth\SDK\Library\Crypt;


use Throwable;

class AesException extends \Exception
{
    public function __construct($message = "", $code = 1101, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}