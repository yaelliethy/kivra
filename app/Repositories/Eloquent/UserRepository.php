<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\UserException;

class UserRepository implements UserRepositoryInterface
{
    public function filter(array $filters)
    {
        $query = User::query();
        if(isset($filters['name'])){
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }
        if(isset($filters['email'])){
            $query->where('email', 'like', '%' . $filters['email'] . '%');
        }
        if(isset($filters['page_number']) && isset($filters['per_page'])){
            return $query->paginate(perPage: $filters['per_page'], page: $filters['page_number']);
        }
        return $query->get();
    }

    public function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function getById(string $id)
    {
        $user = User::find($id);
        if(!$user){
            throw new UserException('User not found');
        }
        return $user;
    }

    public function update(array $data, User $user)
    {
        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);
        return $user;
    }

    public function updatePassword(array $data, string $id)
    {
        $user = $this->getById($id);
        if(!Hash::check($data['old_password'], $user->password)){
            throw new UserException('Old password is incorrect');
        }
        $user->update(['password' => Hash::make($data['password'])]);
        return $user;
    }

    public function delete(string $id)
    {
        $user = $this->getById($id);
        $user->delete();
    }
}
