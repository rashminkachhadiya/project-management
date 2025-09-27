<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CommonQueryScopes;

class Project extends Model
{
    use HasFactory;
    use CommonQueryScopes;

    protected $fillable = ['title','description','start_date','end_date','created_by'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
