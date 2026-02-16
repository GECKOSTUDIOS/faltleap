<?php

declare(strict_types=1);

namespace FaltLeap;

class LeapController
{
    public $view;
    public $db;
    public $request;
    public $session;
    private $destruct;

    /**
     * Middleware to be applied to all methods in this controller.
     * Can be overridden in child controllers.
     *
     * Examples:
     * public array $middleware = ['auth'];
     * public array $middleware = ['auth', 'admin'];
     * public string $middleware = 'auth';
     *
     * @var array|string|null
     */
    public $middleware = null;

    public function __construct()
    {
        $this->db = new LeapDB();
        $this->request = new LeapRequest();
        $this->session = new LeapSession();
        $this->view = new LeapView($this->db, $this->request, $this->session);
    }
    public function redirect($url, $params = [])
    {
        $this->session->set("params", $params);
        header("Location: $url");
        exit;
    }
}
