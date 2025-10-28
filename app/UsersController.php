<?php

include('../models/Users.model.php');

class UsersController extends LeapController
{
    public function index()
    {
        $users = Users::Where("1 = 1");
        $this->view->data = $users;
        $this->view->render('users/index');
    }

    public function edit($id = null)
    {
        $user = new Users();
        if ($this->request->isPost()) {
            if ($id) {
                $user = Users::WhereOne("idusers=" . $id);
                if (!$user) {
                    echo "No user found";
                    die();
                }
            }
            $user->loadFromRequest($this->request);
            if ($this->request->post('password') != "") {
                $pw = (string)$this->request->post('password');
                $user->password = sha1($pw);
            } else {
                unset($user->password);
            }
            $user->save();
            $this->view->flash("User updated successfully");
            $this->redirect("/users");
            return;
        }

        if ($id) {
            $user = Users::WhereOne("idusers=" . $id);
            if (!$user) {
                echo "No user found";
                die();
            }
        }
        $this->view->data = $user;
        $this->view->render('users/edit');
    }

    public function delete(int $id)
    {
        Users::delete($id);
        $this->redirect("/users");
    }
}
