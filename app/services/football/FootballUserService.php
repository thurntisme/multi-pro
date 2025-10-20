<?php

namespace App\Services;

use App\Core\Service;

class FootballUserService extends Service
{
    public function __construct() {}

    public function getCurrentUser()
    {
        $user = $this->getUserData();
        $user['club'] = $this->getUserClub();
        $user['settings'] = $this->getUserSettings();
        $user['messages'] = $this->getUserMessages();
        $user['notifications'] = $this->getUserNotifications();

        return $user;
    }

    public function getUserData()
    {
        return [
            "id" => 1,
            "name" => "John Doe",
            "email" => "john.doe@example.com",
            "avatar" => "https://example.com/avatar.jpg",
            "plan" => "premium",
            "createdAt" => "2023-01-01T00:00:00.000Z",
            "updatedAt" => "2023-01-01T00:00:00.000Z",
        ];
    }

    public function getUserClub()
    {
        return [
            "id" => 1,
            "name" => "My Cub",
            "thumbnail" => "https://example.com/avatar.jpg",
            "budget" => 1000000,
            "coin" => 5000,
            "level" => 1,
            "server" => "europe-west",
            "createdAt" => "2023-01-01T00:00:00.000Z",
            "updatedAt" => "2023-01-01T00:00:00.000Z",
        ];
    }

    public function getUserSettings()
    {
        return [];
    }

    public function getUserMessages()
    {
        return [];
    }

    public function getUserNotifications()
    {
        return [];
    }
}
