<?php

declare(strict_types=1);

namespace App\Middleware;

use FaltLeap\LeapMiddleware;

class AuthMiddleware extends LeapMiddleware
{
    public function handle(callable $next): mixed
    {
        $auth = $this->session->get('auth');

        if (!$auth) {
            $this->redirect('/login', 'Please login to continue');
        }

        return $next();
    }
}
