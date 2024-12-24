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
            return Response::fail(ErrorCode::PARAM_VALID_FAIL, $e->getMessage());
        }
        $data['user_id'] = 1;

        $todo = Todo::create($data);
        return Response::success($todo);
    }

    /**
     * 更新Todo
     * @param Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        try {
            $data = Validator::input($request->post(), [
                'content' => Validator::Length(1, 65535)->setName('内容'),
                'sort' => Validator::Digit()->setName('排序'),
                'status' => Validator::Digit()->setName('状态'),
                'group_id' => Validator::Digit()->setName('组id'),
            ]);
        } catch (ValidationException $e) {
            return Response::fail(ErrorCode::PARAM_VALID_FAIL, $e->getMessage());
        }

        $todo = Todo::find($id);
        if (!$todo) {
            return Response::fail(ErrorCode::TODO_NOT_FOUND);
        }
        
        if (!$todo->update($data)) {
            return Response::fail(ErrorCode::TODO_UPDATE_FAIL);
        }
        return Response::success($todo);
    }

    /**
     * 删除Todo
     * @param Request $request
     * @return Response
     */
    public function delete(Request $request, $id)
    {
        $todo = Todo::find($id);
        if (!$todo) {
            return Response::fail(ErrorCode::TODO_NOT_FOUND);
        }
        
        if (!$todo->delete()) {
            return Response::fail(ErrorCode::TODO_DELETE_FAIL);
        }
        return Response::success(null, '删除成功');
    }

    /**
     * 分页查询Todo列表
     * @param Request $request
     * @return Response
     */
    public function list(Request $request)
    {
        try {
            $page = (int)$request->input('page', 1);
            $pageSize = (int)$request->input('page_size', 15);
            $date = $request->input('date', date('Y-m-d')); // 默认当天
            
            $query = Todo::where('created_at', '>=', $date . ' 00:00:00')
                ->where('created_at', '<=', $date . ' 23:59:59');
                
            $total = $query->count();
            $todos = $query->orderBy('created_at', 'desc')
                ->offset(($page - 1) * $pageSize)
                ->limit($pageSize)
                ->get();
                
            return Response::success([
                'list' => $todos,
                'total' => $total,
                'page' => $page,
                'page_size' => $pageSize
            ]);
        } catch (\Exception $e) {
            return Response::fail(ErrorCode::SYSTEM_ERROR);
        }
    }

    /**
     * 切换Todo完成状态
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function toggleComplete(Request $request, $id)
    {
        $todo = Todo::find($id);
        if (!$todo) {
            return Response::fail(ErrorCode::TODO_NOT_FOUND);
        }
        
        // 切换状态 0->1 或 1->0
        $todo->status = $todo->status ? 0 : 1;
        
        if (!$todo->save()) {
            return Response::fail(ErrorCode::TODO_UPDATE_FAIL);
        }
        
        return Response::success($todo);
    }

    /**
     * 获取指定月份每天的Todo完成状态
     * @param Request $request
     * @return Response
     */
    public function monthlyStatus(Request $request)
    {
        try {
            $date = $request->input('date', date('Y-m')); // 格式: 2024-03
            $userId = 1; // 当前用户ID
            
            // 获取该月的开始和结束日期
            $startDate = $date . '-01 00:00:00';
            $endDate = date('Y-m-t 23:59:59', strtotime($startDate));
            
            // 查询该月每天的todo完成情况
            $todos = Todo::where('user_id', $userId)
                ->where('created_at', '>=', $startDate)
                ->where('created_at', '<=', $endDate)
                ->get();
                
            // 按天分组统计
            $dailyStatus = [];
            foreach ($todos as $todo) {
                $day = date('Y-m-d', strtotime($todo->created_at));
                if (!isset($dailyStatus[$day])) {
                    $dailyStatus[$day] = [
                        'total' => 0,
                        'completed' => 0
                    ];
                }
                $dailyStatus[$day]['total']++;
                if ($todo->status == 1) {
                    $dailyStatus[$day]['completed']++;
                }
            }
            
            // 计算每天的完成状态
            $result = [];
            foreach ($dailyStatus as $day => $status) {
                $result[$day] = $status['total'] > 0 && $status['total'] == $status['completed'];
            }
            
            return Response::success([
                'date' => $date,
                'daily_status' => $result
            ]);
        } catch (\Exception $e) {
            return Response::fail(ErrorCode::SYSTEM_ERROR);
        }
    }

}
