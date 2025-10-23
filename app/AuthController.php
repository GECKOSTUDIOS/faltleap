<?php

include("../models/UsersModel.php");

class AuthController extends LeapController
{
    public function login()
    {
        if ($this->request->isPost()) {
            $this->authenticate();
        }
        return $this->view->single("auth/login");
    }
    public function logout()
    {
        $this->session->set('auth', null);
        $this->session->set('user', null);
        $_SESSION = null;
        $this->redirect("/login");
    }

    public function authenticate()
    {
        $user = $this->request->only(["username", "password"]);

        $model = Users::WhereOne("username = :username AND password = :password", [
            ":username" => $user->username,
            ":password" => $user->password
        ]);
        if (!$model) {
            $this->view->flash("Invalid Credentials");
            $this->redirect("/login");
        }

        if ($user->username == $model->username && sha1($user->password) == sha1($model->password)) {
            $this->session->set("user", $user);
            $this->session->set("auth", [$user->idusers, $user->username]);
            //$_SESSION['user'] = $user;
            return $this->redirect("/");
        }

        $this->view->flash("Invalid Credentials");

        $this->redirect("/login");
    }
}
