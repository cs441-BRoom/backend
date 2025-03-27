<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Workspace extends Model
{
    use HasFactory,SoftDeletes;

    protected $primaryKey = 'workspace_id';

    protected $fillable = [
        'name',
        'description',
        'join_code',
        'created_by',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'workspace_members', 'workspace_id', 'user_id')->withPivot('role')->withTimestamps();
    }

}
