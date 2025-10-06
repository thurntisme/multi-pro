<?php
namespace App\Services;

use App\Core\Service;
use App\Core\Session;
use App\Core\Database;
use App\Models\UserModel;

class UserService extends Service
{
    private UserModel $userModel;

    public function __construct(Database $database)
    {
        $this->userModel = new UserModel($database);
    }

    public function getCurrentUser()
    {
        $user = Session::get('user');
        if (!$user) {
            return null;
        }

        return $this->userModel->findByEmail($user['email']);
    }

    public function getCurrentUserId(): ?int
    {
        $user = $this->getCurrentUser();
        return $user['id'] ?? null;
    }
}
