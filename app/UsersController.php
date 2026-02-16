<?php

declare(strict_types=1);

namespace App\Controllers;

use FaltLeap\LeapController;
use App\Models\Users;

class UsersController extends LeapController
{
    public function index()
    {
        // Use new Query builder for fetching all users
        $users = Users::Query()->get();
        $this->view->data = $users;
        $this->view->render('users/index');
    }

    public function edit($id = null)
    {
        $user = new Users();
        if ($this->request->isPost()) {
            if ($id) {
                // Use parameterized query with new query logic
                $user = Users::Query()
                    ->where("idusers = :id", [":id" => $id])
                    ->first();
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
            // Use parameterized query with new query logic
            $user = Users::Query()
                ->where("idusers = :id", [":id" => $id])
                ->first();
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
