<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'tasks';
    protected $fillable = ['name', 'description', 'status_id', 'created_by_id', 'assigned_to_id'];

    /**
     * Many to One Relation To TaskStatuses
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status()
    {
        return $this->belongsTo(TaskStatus::class, 'status_id', 'id');
    }

    /**
     * Many to One Relation To Users
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'assigned_to_id', 'id')->withDefault();
    }

    /**
     * Many to One Relation To Users
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'created_by_id', 'id');
    }
}
