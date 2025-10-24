<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;
use App\Core\Session;
use App\Helpers\Flash;
use App\Helpers\Validator;
use App\Services\TokenService;

class AuthController extends Controller
{
    private $validator;
    private static $loginUrl = 'login';
    private static $appUrl = 'app';
    private static $loginView = 'auth/login';
    private static $loginTitle = 'Login';
    private static $registerView = 'auth/register';
    private static $registerTitle = 'Register';
    private static $fakeUser = [
        'email' => 'admin@multipro.com',
        'password' => 'hvFwZGy1mr2wesSb7Y3$',
        'username' => 'admin',
    ];
    private $tokenService;

    public function __construct()
    {
        $this->validator = new Validator();
        $this->tokenService = new TokenService();
    }

    public function renderLogin(): Response
    {
        return $this->view(self::$loginView, ['title' => self::$loginTitle]);
    }

    public function renderRegister(): Response
    {
        return $this->view(self::$registerView, ['title' => self::$registerTitle]);
    }

    public function login(): Response
    {
        $email = $this->request->post['email'] ?? '';
        $password = $this->request->post['password'] ?? '';

        $error = "";
        if (!$this->validator->required($email)) {
            $error = "Email is required";
            return $this->view(self::$loginView, ['title' => self::$loginTitle], ['type' => 'error', 'msg' => $error]);
        }
        if (!$this->validator->required($password)) {
            $error = "Password is required";
            return $this->view(self::$loginView, ['title' => self::$loginTitle], ['type' => 'error', 'msg' => $error]);
        }
        if (!$this->validator->email($email)) {
            $error = "Email is invalid";
            return $this->view(self::$loginView, ['title' => self::$loginTitle], ['type' => 'error', 'msg' => $error]);
        }
        if (!$this->validator->minLength($password, 8)) {
            $error = "Password must be at least 8 characters";
            return $this->view(self::$loginView, ['title' => self::$loginTitle], ['type' => 'error', 'msg' => $error]);
        }

        if (empty($error)) {
            if ($email === self::$fakeUser['email'] && $password === self::$fakeUser['password']) {
                self::$fakeUser['token'] = $this->tokenService->createToken();
                Session::set('user', self::$fakeUser);
                Flash::add('success', 'Welcome back ' . self::$fakeUser['username']);
                return $this->redirect(self::$appUrl);
            } else {
                $error = "Invalid email or password";
                return $this->view(self::$loginView, ['title' => self::$loginTitle], ['type' => 'error', 'msg' => $error]);
            }
        }

        return $this->view(self::$loginView, ['title' => self::$loginTitle]);
    }

    public function logout(): Response
    {
        Session::destroy();

        return $this->redirect(self::$loginUrl);
    }
}
