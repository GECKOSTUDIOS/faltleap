<?php

include("../models/Reverseproxies.model.php");

class ReverseProxyController extends LeapController
{
    public function index()
    {
        $data = Reverseproxies::Where("1 = 1");
        $data = $this->db->query("SELECT * FROM reverseproxies r INNER JOIN users u ON r.idusers = u.idusers");
        $this->view->data = $data;
        $this->view->render('reverse_proxy/index');
    }

    public function edit($id = null)
    {
        $data = new Reverseproxies();
        $data->idusers = $this->session->getUserId();
        if ($this->request->isPost()) {
            if ($id) {
                $data = Reverseproxies::WhereOne("idreverseproxies=" . $id);
                if (!$data) {
                    echo "No data found";
                    die();
                }
            }
            $data->loadFromRequest($this->request);
            $data->save();
            $this->view->flash("Reverse Proxy updated successfully");
            $this->redirect("/manage");
            return;
        }

        if ($id) {
            $data = Reverseproxies::WhereOne("idreverseproxies=" . $id);
            if (!$data) {
                echo "No data found";
                die();
            }
        }
        $this->view->data = $data;
        $this->view->render('/reverse_proxy/edit');
    }

    public function delete(int $id)
    {
        Reverseproxies::delete($id);
        $this->redirect("/manage");
    }
}
