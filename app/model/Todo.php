<?php

namespace app\model;

use support\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
/**
 *
 */
class Todo extends Model
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
    protected $table = 'todolist';

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

    protected $fillable = [
        'content',
        'user_id',
        'sort',
        'status',
        'group_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];


    
    
}
