<?php

class LeapView
{
    var $db;
    var $request;
    var $session;
    var $data;

    public function __construct(LeapDB $db, LeapRequest $request, LeapSession $session)
    {
        if (!array_key_exists("view", $_SESSION)) {
            $_SESSION['view'] = ['flash_shown' => false];
        }
        // if (array_key_exists('flash', $_SESSION['view'])) {
        //     if ($_SESSION['view']['flash_shown'] == true) {
        //         unset($_SESSION['view']['flash']);
        //         $_SESSION['view'] = ['flash_shown' => false];
        //     }
        // }
    }

    public function __destruct()
    {
        if (array_key_exists('flash', $_SESSION['view'])) {
            $_SESSION['view']['flash_shown'] = 1;
            unset($_SESSION['view']['flash']);
        }
    }

    public function single(string $view)
    {
        $viewcontent = "";
        if (file_exists("../views/" . $view . ".leap.php")) {
            $viewcontent = $this->load_template(
                "../views/" . $view . ".leap.php",
            );
        } else {
            $viewcontent = "view not found:" . $view . ".leap.php";
        }
        $viewcontent = $this->replaceTags($viewcontent);
        echo $viewcontent;
    }


    public function flash(string $message)
    {
        $_SESSION['view']['flash_shown'] = false;
        $_SESSION['view']['flash'] = $message;
    }


    public function replaceTags($content)
    {
        if (array_key_exists('errors', $_SESSION['view'])) {
            $content = str_replace("{{errors}}", $_SESSION['view']['errors'], $content);
        } else {
            $content = str_replace("{{errors}}", "", $content);
        }
        if (array_key_exists('flash', $_SESSION['view'])) {
            if (file_exists("views/flash.leap.php")) {
                $flash_template = $this->load_template("views/flash.leap.php");
            } else {
                $flash_template = "<div class=\"alert alert-success mt-4\"> <p>{{flash}}</p></div>";
            }
            $flash_message = str_replace("{{flash}}", $_SESSION['view']['flash'], $flash_template);
            $content = str_replace("{{flash}}", $flash_message, $content);
        } else {
            $content = str_replace("{{flash}}", "", $content);
        }
        $_SESSION['view']['flash_shown'] = true;
        return $content;
    }

    public function render($subview = null)
    {
        //load the base template first
        $base = $this->load_template("../views/index.leap.php");
        if ($subview) {
            if (file_exists("../views/" . $subview . ".leap.php")) {
                $subview = $this->load_template(
                    "../views/" . $subview . ".leap.php",
                );
            } else {
                $subview = "view not found:" . $subview . ".leap.php";
            }
        }
        $base = str_replace("{{content}}", $subview, $base);
        $base = $this->replaceTags($base);
        echo $base;
    }

    public function renderJson()
    {
        header("Content-Type: application/json");
        echo json_encode($this->data);
    }

    private function load_template($template)
    {
        ob_start();
        require $template;
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
}
