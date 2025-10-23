<?php

class HomeController extends LeapController {
    public function index() {
        if(!$this->session->get('auth')){
            $this->redirect('login');
        }
        $this->view->render('home/index');
    }
}
