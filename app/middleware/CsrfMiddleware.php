<?php
declare(strict_types=1);

namespace App\Middleware;

use App\Core\MiddlewareInterface;
use App\Core\Request;
use App\Core\RequestHandlerInterface;
use App\Core\Response;
use App\Core\Session;

/**
 * Class CsrfMiddleware
 *
 * Protects against CSRF attacks by validating request tokens.
 * - On GET requests: generates a CSRF token and stores it in session
 * - On POST/PUT/DELETE requests: validates the token
 */
class CsrfMiddleware implements MiddlewareInterface
{
    public string $tokenKey = '_csrf_token';

    public function __construct(private Response $response)
    {
    }

    /**
     * Handle the incoming request.
     *
     * @param array $options
     * @throws \Exception
     */
    public function process(Request $request, RequestHandlerInterface $next): Response
    {
        $method = strtoupper($_SERVER['REQUEST_METHOD']);

        try {
            if (in_array($method, ['POST', 'PUT', 'DELETE'], true)) {
                $this->validateToken();
            } else {
                $this->generateToken();
            }

            $this->response = $next->handle($request);
        } catch (\Exception $e) {
            $this->response->reload();
        }

        return $this->response;
    }

    /**
     * Generate a new CSRF token if not exists.
     */
    private function generateToken(): void
    {
        if (!Session::has($this->tokenKey)) {
            Session::set($this->tokenKey, bin2hex(random_bytes(32)));
        }
    }

    /**
     * Validate the CSRF token from request.
     *
     * @throws \Exception
     */
    private function validateToken(): void
    {
        $submitted = $_POST[$this->tokenKey]
            ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? null);

        $stored = Session::get($this->tokenKey);

        if (!$submitted || !$stored || !hash_equals($stored, $submitted)) {
            throw new \Exception("Invalid CSRF token.");
        }
    }

    /**
     * Get the CSRF token for forms.
     */
    public function token(): string
    {
        return Session::get($this->tokenKey) ?? '';
    }

    /**
     * Render a hidden input field for HTML forms.
     */
    public function inputField(): string
    {
        $token = $this->token();
        return "<input type='hidden' name='{$this->tokenKey}' value='{$token}'>";
    }
}