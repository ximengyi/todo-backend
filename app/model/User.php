<?php

namespace app\model;

use support\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 用户模型
 */
class User extends Model
{
    use SoftDeletes;

    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'mysql';
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'password',
        'email',
        'nickname',
        'avatar',
        'status',
        'last_login_at',
        'last_login_ip',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'deleted_at',
    ];

    /**
     * 设置密码（自动加密）
     * @param string $value
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = password_hash($value, PASSWORD_DEFAULT);
    }

    /**
     * 验证密码
     * @param string $password
     * @return bool
     */
    public function verifyPassword($password)
    {
        return password_verify($password, $this->password);
    }
}

