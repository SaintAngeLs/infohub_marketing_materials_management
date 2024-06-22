<?php

namespace App\Contracts;

interface IUserService
{
    public function getAllUsers();
    public function createUser(array $userData);
    public function getEditUserDTO($userId);
    public function updateUser($userId, array $userData);
    public function getUserById($userId);
}
