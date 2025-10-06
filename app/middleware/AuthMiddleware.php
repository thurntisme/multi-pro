<?php
declare(strict_types=1);

namespace App\Middleware;

use App\Core\MiddlewareInterface;
use App\Core\Request;
use App\Core\RequestHandlerInterface;
use App\Core\Response;
use App\Core\Session;

/**
 * Class AuthMiddleware
 *
 * Ensures that only authenticated users can access certain routes.
 * Optionally, it can also check if the user has a specific role.
 */
class AuthMiddleware implements MiddlewareInterface
{
    private ?array $user;

    public function __construct(private Response $response)
    {
        $this->user = Session::get('user');
    }

    /**
     * Check if user is authenticated.
     */
    private function isAuthenticated(): bool
    {
        return $this->user !== null;
    }

    /**
     * Check if user has the required role.
     */
    private function hasRole(string $role): bool
    {
        return isset($this->user['role']) && $this->user['role'] === $role;
    }

    /**
     * Redirect helper.
     */
    private function redirect(string $path): void
    {
        header("Location: {$path}");
        exit;
    }

    /**
     * Process request
     */
    public function process(Request $request, RequestHandlerInterface $next): Response
    {
        $this->response = $next->handle($request);

        // Redirect to login if not authenticated
        if (!$this->isAuthenticated()) {
            $this->response->redirect('/login');
        }

        return $this->response;
    }

}
