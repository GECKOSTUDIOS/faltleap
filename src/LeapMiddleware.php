<?php
declare(strict_types=1);

namespace FlatLeap;

/**
 * Abstract base class for all middleware in the Falt Leap Framework.
 *
 * Middleware are executed before controller methods and can:
 * - Check authentication/authorization
 * - Validate requests
 * - Log activity
 * - Modify request/response
 * - Redirect or terminate execution
 */
abstract class LeapMiddleware
{
    protected LeapDB $db;
    protected LeapRequest $request;
    protected LeapSession $session;

    public function __construct(LeapDB $db, LeapRequest $request, LeapSession $session)
    {
        $this->db = $db;
        $this->request = $request;
        $this->session = $session;
    }

    /**
     * Handle the middleware logic.
     *
     * @param callable $next The next middleware or controller in the chain
     * @return mixed Return value from next middleware/controller or redirect/exit
     */
    abstract public function handle(callable $next): mixed;

    /**
     * Helper method to redirect and terminate execution.
     *
     * @param string $url The URL to redirect to
     * @param string|null $flashMessage Optional flash message to display
     * @return void
     */
    protected function redirect(string $url, ?string $flashMessage = null): void
    {
        if ($flashMessage) {
            $this->session->set('flash', $flashMessage);
        }
        header("Location: $url");
        exit;
    }
}
