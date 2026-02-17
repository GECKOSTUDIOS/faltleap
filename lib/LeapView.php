<?php

declare(strict_types=1);

namespace FaltLeap;

class LeapView
{
    public $db;
    public $request;
    public $session;
    public $data;
    private $viewsPath;

    public function __construct(LeapDB $db, LeapRequest $request, LeapSession $session)
    {
        // Set absolute path to views directory
        // Go up from lib/ to project root, then to views/
        $this->viewsPath = dirname(__DIR__) . '/views';

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

    /**
     * Set data for the view
     * @param mixed $data Data to pass to the view
     * @return void
     */
    public function set($data): void
    {
        $this->data = $data;
    }

    public function single(string $view)
    {
        $viewPath = $this->viewsPath . '/' . $view . '.leap.php';
        if (!file_exists($viewPath)) {
            throw new LeapNotFoundException("View not found: {$view}.leap.php (looked in: {$viewPath})");
        }
        $viewcontent = $this->load_template($viewPath);
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
            $flashPath = $this->viewsPath . '/flash.leap.php';
            if (file_exists($flashPath)) {
                $flash_template = $this->load_template($flashPath);
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
        $basePath = $this->viewsPath . '/index.leap.php';
        $base = $this->load_template($basePath);
        if ($subview) {
            $subviewPath = $this->viewsPath . '/' . $subview . '.leap.php';
            if (!file_exists($subviewPath)) {
                throw new LeapNotFoundException("View not found: {$subview}.leap.php (looked in: {$subviewPath})");
            }
            $subview = $this->load_template($subviewPath);
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

    public function redirect(string $url)
    {
        header("Location: " . $url);
        exit;
    }

    private function load_template($template)
    {
        // Lint check in debug mode
        $errorHandler = LeapErrorHandler::getInstance();
        if ($errorHandler->isDebug()) {
            $errorHandler->lintCheck($template);
        }

        ob_start();
        try {
            require $template;
        } catch (\Throwable $e) {
            ob_end_clean();
            throw $e;
        }
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
}
