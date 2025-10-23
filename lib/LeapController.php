<?php

class LeapController
{
    public $view;
    public $db;
    public $request;
    public $session;
    private $destruct;
    public function __construct()
    {
        $this->db = new LeapDB();
        $this->request = new LeapRequest();
        $this->session = new LeapSession();
	$this->view = new LeapView($this->db,$this->request,$this->session);
    }
    public function redirect($url, $params = [])
    {
        $this->session->set("params", $params);
        header("Location: $url");
        exit;
    }
}
