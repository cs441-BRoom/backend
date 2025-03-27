<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes;
    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ["username", "firstname", "lastname", "email", "auth_type", "password"];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = ["password", "remember_token"];

    public function count(): int
    {
        return $this->count();
    }

    public function workspaces()
    {
        return $this->belongsToMany(Workspace::class, 'workspace_members', 'user_id', 'workspace_id')->withPivot('role');
    }

    public function news()
    {
        return $this->hasMany(News::class, 'created_by');
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'created_by');
    }

    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class, 'user_id');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            "email_verified_at" => "datetime",
            "password" => "hashed",
        ];
    }
}
