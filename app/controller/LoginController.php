<?php

namespace app\controller;

use app\constant\ErrorCode;
use app\model\LoginLog;
use app\model\User;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator;
use support\Request;
use support\Response;

/**
 * 登录控制器
 */
class LoginController
{
    /**
     * 用户登录
     * @param Request $request
     * @return Response
     */
    public function login(Request $request)
    {
        try {
            // 参数验证
            $data = Validator::input($request->post(), [
                'username' => Validator::notEmpty()->setName('用户名'),
                'password' => Validator::notEmpty()->setName('密码'),
            ]);
        } catch (ValidationException $e) {
            return Response::fail(ErrorCode::PARAM_VALID_FAIL, $e->getMessage());
        }

        $username = $data['username'];
        $password = $data['password'];
        $loginIp = $request->getRealIp();
        $userAgent = $request->header('User-Agent', '');

        try {
            // 查找用户
            $user = User::where('username', $username)->first();
            
            // 记录登录日志（无论成功失败）
            $loginLog = [
                'user_id' => $user ? $user->id : 0,
                'username' => $username,
                'login_ip' => $loginIp,
                'user_agent' => $userAgent,
                'status' => 0,
                'message' => '',
                'created_at' => date('Y-m-d H:i:s'),
            ];

            // 用户不存在
            if (!$user) {
                $loginLog['message'] = '用户不存在';
                LoginLog::create($loginLog);
                return Response::fail(ErrorCode::LOGIN_USER_NOT_FOUND);
            }

            // 用户被禁用
            if ($user->status != 1) {
                $loginLog['message'] = '用户已被禁用';
                $loginLog['user_id'] = $user->id;
                LoginLog::create($loginLog);
                return Response::fail(ErrorCode::LOGIN_USER_DISABLED);
            }

            // 验证密码
            if (!$user->verifyPassword($password)) {
                $loginLog['message'] = '密码错误';
                $loginLog['user_id'] = $user->id;
                LoginLog::create($loginLog);
                return Response::fail(ErrorCode::LOGIN_PASSWORD_ERROR);
            }

            // 登录成功，更新用户最后登录信息
            $user->last_login_at = date('Y-m-d H:i:s');
            $user->last_login_ip = $loginIp;
            $user->save();

            // 记录成功日志
            $loginLog['status'] = 1;
            $loginLog['message'] = '登录成功';
            $loginLog['user_id'] = $user->id;
            LoginLog::create($loginLog);

            // 设置session
            $session = $request->session();
            $session->set('user_id', $user->id);
            $session->set('username', $user->username);

            // 返回用户信息（不包含密码）
            $userData = [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'nickname' => $user->nickname,
                'avatar' => $user->avatar,
            ];

            return Response::success($userData, '登录成功');
        } catch (\Exception $e) {
            return Response::fail(ErrorCode::LOGIN_FAIL, $e->getMessage());
        }
    }

    /**
     * 用户注册
     * @param Request $request
     * @return Response
     */
    public function register(Request $request)
    {
        try {
            // 参数验证
            $data = Validator::input($request->post(), [
                'username' => Validator::notEmpty()->length(3, 50)->setName('用户名'),
                'password' => Validator::notEmpty()->length(6, 50)->setName('密码'),
                'email' => Validator::email()->setName('邮箱')->optional(),
                'nickname' => Validator::stringType()->length(0, 50)->setName('昵称')->optional(),
            ]);
        } catch (ValidationException $e) {
            return Response::fail(ErrorCode::PARAM_VALID_FAIL, $e->getMessage());
        }

        try {
            // 检查用户名是否已存在
            $existsUser = User::where('username', $data['username'])->first();
            if ($existsUser) {
                return Response::fail(ErrorCode::REGISTER_USERNAME_EXISTS);
            }

            // 检查邮箱是否已存在（如果提供了邮箱）
            if (!empty($data['email'])) {
                $existsEmail = User::where('email', $data['email'])->first();
                if ($existsEmail) {
                    return Response::fail(ErrorCode::REGISTER_EMAIL_EXISTS);
                }
            }

            // 创建用户
            $user = User::create([
                'username' => $data['username'],
                'password' => $data['password'], // 模型会自动加密
                'email' => $data['email'] ?? null,
                'nickname' => $data['nickname'] ?? null,
                'status' => 1,
            ]);

            // 返回用户信息（不包含密码）
            $userData = [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'nickname' => $user->nickname,
                'avatar' => $user->avatar,
            ];

            return Response::success($userData, '注册成功');
        } catch (\Exception $e) {
            return Response::fail(ErrorCode::REGISTER_FAIL, $e->getMessage());
        }
    }

    /**
     * 用户登出
     * @param Request $request
     * @return Response
     */
    public function logout(Request $request)
    {
        try {
            $session = $request->session();
            $session->forget('user_id');
            $session->forget('username');
            $session->flush();
            
            return Response::success(null, '登出成功');
        } catch (\Exception $e) {
            return Response::fail(ErrorCode::LOGOUT_FAIL, $e->getMessage());
        }
    }

    /**
     * 获取当前登录用户信息
     * @param Request $request
     * @return Response
     */
    public function info(Request $request)
    {
        try {
            $session = $request->session();
            $userId = $session->get('user_id');
            
            if (!$userId) {
                return Response::fail(ErrorCode::NOT_LOGIN);
            }

            $user = User::find($userId);
            if (!$user) {
                return Response::fail(ErrorCode::LOGIN_USER_NOT_FOUND);
            }

            // 返回用户信息（不包含密码）
            $userData = [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'nickname' => $user->nickname,
                'avatar' => $user->avatar,
                'last_login_at' => $user->last_login_at,
                'last_login_ip' => $user->last_login_ip,
            ];

            return Response::success($userData);
        } catch (\Exception $e) {
            return Response::fail(ErrorCode::SYSTEM_ERROR, $e->getMessage());
        }
    }
}

