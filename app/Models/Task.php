<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['project_id', 'title','details','priority','assignee_id','due_date','is_done'];

    protected $casts = ['due_date' => 'date', 'is_done' => 'boolean'];


    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee_id');
    
    }


}
