<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectActivity extends Model
{
    
    protected $table = 'project_activity';

    protected $fillable = [
        'user_id', 'entity_type', 'entity_id', 'action', 'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];
}