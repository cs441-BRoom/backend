<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assignment extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'assignment_id';
    protected $fillable = [
        'workspace_id', 'title', 'description', 'due_date', 'created_by'
    ];

    // Relationships
    public function workspace()
    {
        return $this->belongsTo(Workspace::class, 'workspace_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignmentSubmission()
    {
        return $this->hasMany(AssignmentSubmission::class, 'assignment_id');
    }
}
