<?php

include("../models/Reverseproxies.model.php");

class ReverseProxyController extends LeapController
{
    public function index()
    {
        $data = Reverseproxies::Where("1 = 1");
        $data = $this->db->query("SELECT * FROM reverseproxies r INNER JOIN users u ON r.idusers = u.idusers");
        //$this->view->flash("yo this werks");
        $this->view->data = $data;
        $this->view->render('reverse_proxy/index');
    }
}
