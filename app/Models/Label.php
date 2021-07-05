<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    protected $table = 'labels';
    protected $fillable = ['name', 'description'];

    /**
     * Many to Many Relation To Tasks
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tasks()
    {
        return $this->hasMany(TaskLabel::class, 'label_id', 'id');
    }
}
