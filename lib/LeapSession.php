<?php

class LeapSession {
    private $session;

    public function __construct() {
        $this->session = &$_SESSION;
    }

    public function set($key, $value) {
        $this->session[$key] = $value;
    }

    public function get($key) {
        return $this->session[$key] ?? null;
    }

    public function destroy() {
        session_destroy();
    }
}
