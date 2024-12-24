<?php
namespace app\constant;

class ErrorCode
{
    // 系统级别错误码
    const SYSTEM_ERROR = [500, '系统错误'];
    const PARAM_VALID_FAIL = [400, '参数验证失败'];
    
    // 业务级别错误码
    const DATA_NOT_FOUND = [404, '数据不存在'];
    const DATA_ALREADY_EXISTS = [1001, '数据已存在'];
    const DATA_UPDATE_FAIL = [1002, '数据更新失败'];
    const DATA_DELETE_FAIL = [1003, '数据删除失败'];
    
    // Todo相关错误码
    const TODO_NOT_FOUND = [2001, '待办事项不存在'];
    const TODO_CREATE_FAIL = [2002, '创建待办事项失败'];
    const TODO_UPDATE_FAIL = [2003, '更新待办事项失败'];
    const TODO_DELETE_FAIL = [2004, '删除待办事项失败'];
}
