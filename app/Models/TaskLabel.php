<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskLabel extends Model
{
    protected $table = 'task_labels';
    public $timestamps = false;

    /**
     * Many to One Relation To Labels
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function label()
    {
        return $this->belongsTo(Label::class, 'label_id', 'id');
    }

    /**
     * Many to One Relation To Tasks
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id', 'id');
    }
}
