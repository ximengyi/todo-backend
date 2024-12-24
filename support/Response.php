<?php
/**
 * This file is part of webman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link      http://www.workerman.net/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace support;

/**
 * Class Response
 * @package support
 */
class Response extends \Webman\Http\Response
{


    /**
     * Here is your custom functions.
     */
    public static function success( $data = null, string $msg = 'success'): Response
    {
        return json(['code' => 0, 'msg' => $msg, 'data'=>$data]);
    }

    /**
     * 失败返回
     * @param array $error ErrorCode常量数组 [code, message]
     * @param mixed $data 额外的错误数据
     * @return Response
     */
    public static function fail(array $error, $data = null): Response
    {
        return json([
            'code' => $error[0],  // 数组第一个元素是错误码
            'msg' => $error[1],   // 数组第二个元素是错误信息
            'data' => $data
        ]);
    }


}