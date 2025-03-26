<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Traits\SimpleCRUD;

class UserRepository
{
    use SimpleCRUD;

    private string $model = User::class;

}
