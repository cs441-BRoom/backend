<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkspaceMember extends Model
{
    use HasFactory,SoftDeletes;

    protected $primaryKey = 'member_id';
    protected $fillable = [
        'user_id', 'workspace_id', 'role'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function workspace()
    {
        return $this->belongsTo(Workspace::class, 'workspace_id');
    }
}
