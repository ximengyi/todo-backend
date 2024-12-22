<?php

namespace app\controller;

use app\constant\ErrorCode;
use app\model\Todo;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator;
use support\Request;
use support\Response;

class TodoController
{


    public function create(Request $request)
    {
        try {

            $data = Validator::input($request->post(), [
                'content' => Validator::Length(1, 65535)->setName('内容'),
                'sort' => Validator::Digit()->setName('排序')->setDefault(0),
                'status' => Validator::Digit()->setName('状态')->setDefault(0),
                'group_id' => Validator::Digit()->setName('组id')->setDefault(0),
            ]);

        } catch (ValidationException $e) {
            return Response::fail(ErrorCode::PARAM_VALID_FAIL['code'], ErrorCode::PARAM_VALID_FAIL['msg'], $e->getMessage());
        }
        $data['user_id'] = 1;

        $todo = Todo::create($data);
        return Response::success($todo);
    }

    public function update(Request $request)
    {

    }


}
