<?php

namespace app\middleware;

use app\constant\ErrorCode;
use support\Response;
use Webman\Http\Request;
use Webman\MiddlewareInterface;

/**
 * 登录认证中间件
 * 检查用户是否已登录
 */
class AuthCheck implements MiddlewareInterface
{
    /**
     * 处理请求
     * @param Request $request
     * @param callable $handler
     * @return Response
     */
    public function process(Request $request, callable $handler): Response
    {
        // 获取 session
        $session = $request->session();
        $userId = $session->get('user_id');

        // 检查是否已登录
        if (!$userId) {
            // 未登录，返回错误响应
            return Response::fail(ErrorCode::NOT_LOGIN);
        }

        // 已登录，继续处理请求
        return $handler($request);
    }
}

