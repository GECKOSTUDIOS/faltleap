<?php

class LeapRequest
{
    public function __construct()
    {
        $this->request = $_SERVER["REQUEST_URI"];
        $this->method = $_SERVER["REQUEST_METHOD"];
        $this->headers = getallheaders();
        $this->body = file_get_contents("php://input");
    }

    public $request;
    public $method;
    public $headers;
    public $body;

    public function post(string $key)
    {
        $data = $_POST[$key];
        $result = isset($data[$key]) ? $data[$key] : null;
        return (object) $result;
    }

    public function get(string $key)
    {
        $data = $_GET[$key];
        $result = isset($data[$key]) ? $data[$key] : null;
        return (object) $result;
    }

    public function json(string $key)
    {
        $data = json_decode($this->body, true);
        $result = isset($data[$key]) ? $data[$key] : null;
        return (object) $result;
    }

    public function only(array $keys)
    {
        if ($this->isPost()) {
            $data = $_POST;
        } else {
            $data = json_decode($this->body, true);
        }
        $result = [];
        foreach ($keys as $key) {
            if (isset($data[$key])) {
                $result[$key] = $data[$key];
            }
        }
        $result = (object) $result;
        return $result;
    }

    public function has(string $key)
    {
        if ($this->isPost()) {
            return isset($_POST[$key]);
        } elseif ($this->isGet()) {
            return isset($_GET[$key]);
        } else {
            $data = json_decode($this->body, true);
            return isset($data[$key]);
        }
    }

    public function load(string $key)
    {
        if ($this->isPost()) {
            return $_POST[$key];
        } elseif ($this->isGet()) {
            return $_GET[$key];
        } else {
            $data = json_decode($this->body, true);
            return $data[$key];
        }
    }

    public function isPost()
    {
        return $this->method === "POST";
    }

    public function isGet()
    {
        return $this->method === "GET";
    }

    public function isPut()
    {
        return $this->method === "PUT";
    }

    public function isDelete()
    {
        return $this->method === "DELETE";
    }

    public function isPatch()
    {
        return $this->method === "PATCH";
    }

    public function isOptions()
    {
        return $this->method === "OPTIONS";
    }

    public function isHead()
    {
        return $this->method === "HEAD";
    }
}
