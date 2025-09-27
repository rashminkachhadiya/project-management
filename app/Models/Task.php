<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CommonQueryScopes;

class Task extends Model
{
    use HasFactory;
    use CommonQueryScopes;

    protected $fillable = [
        'title', 'description', 'status', 'due_date', 'project_id', 'assigned_to'
    ];

    protected $casts = [
        'due_date' => 'date'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
