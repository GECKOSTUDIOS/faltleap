<?php

declare(strict_types=1);

namespace FaltLeap;

/**
 * Manages the execution chain of middleware.
 *
 * This class builds and executes a pipeline of middleware, passing control
 * from one to the next until reaching the final controller action.
 */
class LeapMiddlewareStack
{
    private LeapDB $db;
    private LeapRequest $request;
    private LeapSession $session;
    private array $middlewareAliases = [];

    public function __construct(LeapDB $db, LeapRequest $request, LeapSession $session)
    {
        $this->db = $db;
        $this->request = $request;
        $this->session = $session;

        // Register built-in middleware aliases
        $this->registerDefaultMiddleware();
    }

    /**
     * Register default middleware aliases.
     */
    private function registerDefaultMiddleware(): void
    {
        $this->middlewareAliases = [
            'auth' => \App\Middleware\AuthMiddleware::class,
            'admin' => \App\Middleware\AdminMiddleware::class,
            'guest' => \App\Middleware\GuestMiddleware::class,
            'role' => \App\Middleware\RoleMiddleware::class,
            'menuitem' => \App\Middleware\MenuItemMiddleware::class,
        ];
    }

    /**
     * Register a custom middleware alias.
     *
     * @param string $alias The middleware alias (e.g., 'auth')
     * @param string $className The fully qualified middleware class name
     */
    public function register(string $alias, string $className): void
    {
        $this->middlewareAliases[$alias] = $className;
    }

    /**
     * Execute the middleware stack and final action.
     *
     * @param array $middlewareList Array of middleware aliases or class names
     * @param callable $finalAction The controller method to execute after all middleware
     * @return mixed The result from the final action
     */
    public function execute(array $middlewareList, callable $finalAction): mixed
    {
        // Build the middleware chain from right to left
        $pipeline = array_reduce(
            array_reverse($middlewareList),
            function ($next, $middlewareName) {
                return function () use ($middlewareName, $next) {
                    $middleware = $this->resolveMiddleware($middlewareName);
                    return $middleware->handle($next);
                };
            },
            $finalAction
        );

        // Execute the pipeline
        return $pipeline();
    }

    /**
     * Resolve a middleware instance from its alias or class name.
     *
     * @param string $middlewareName The middleware alias or class name
     * @return LeapMiddleware The middleware instance
     * @throws \RuntimeException If middleware class not found
     */
    private function resolveMiddleware(string $middlewareName): LeapMiddleware
    {
        // Check if it's a registered alias
        if (isset($this->middlewareAliases[$middlewareName])) {
            $className = $this->middlewareAliases[$middlewareName];
        } else {
            // Assume it's a fully qualified class name
            $className = $middlewareName;
        }

        // Verify the class exists
        if (!class_exists($className)) {
            throw new \RuntimeException("Middleware class not found: $className");
        }

        // Instantiate and return the middleware
        return new $className($this->db, $this->request, $this->session);
    }

    /**
     * Get all registered middleware aliases.
     *
     * @return array
     */
    public function getRegisteredMiddleware(): array
    {
        return $this->middlewareAliases;
    }
}
