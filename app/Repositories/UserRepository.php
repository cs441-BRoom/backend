<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Traits\SimpleCRUD;

class UserRepository
{
    use SimpleCRUD;
    protected string|User $model = User::class;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function findByUsername(string $username)
    {
        return $this->model::where('username', $username)->first();
    }

    public function findByEmail(string $email)
    {
        return $this->model::where('email', $email)->first();
    }
}
