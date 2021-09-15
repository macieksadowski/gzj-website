<?php

require_once 'defines.php';

require_once SITE_ROOT.'/page.php';

require_once SITE_ROOT.'/menu.php';

class restrictedPage extends page
{
    private $logged = false;
    private $errors;
    private $errorDescriptions;

    public function __construct($menu = __DIR__.'/data/menu.json')
    {
        parent::__construct($menu);
        if (PHP_SESSION_NONE == session_status()) {
            session_start();
        }
        if (isset($_SESSION['logged'])) {
            $this->logged = $_SESSION['logged'];
        }

        $this->errorDescriptions = json_decode(file_get_contents(SITE_ROOT.'/data/errors.json'), true);
    }

    public function checkCredentials()
    {
        if (!$this->logged) {
            header('location: ../index.php');

            exit();
        }

        // VARIABLES
        $_SESSION['logged'] = false;
    }

    public function show($content)
    {
        parent::header();
        parent::footer();
        parent::toLayout('MENU', $this->menu->print(false, false));
        parent::scripts();
        parent::toLayout('CONTENT', $content);
        $this->modal();
        echo $this->getPage();
    }

    private function modal()
    {
        $modal = file_get_contents(__DIR__.'/templates/modal.html');

        if (isset($_SESSION['success'])) {
            $this->fillWithData($modal, ['TYPE' => ' id="success" style="display:block;" ']);
            $communicate = $_SESSION['success'];
            unset($_SESSION['success']);
        } elseif (isset($_SESSION['errors'])) {
            $this->errors = $_SESSION['errors'];

            $communicate = '';
            $print = false;
            foreach ($this->errors as $num => $error) {
                if (true == $error) {
                    if (@!$print) {
                        $communicate = 'Wystąpił błąd: ';
                        $print = true;
                    }
                    $communicate .= ' '.$this->errorDescriptions[$num];
                    $this->fillWithData($modal, ['TYPE' => ' id="error" style="display:block;" ']);
                }
            }
            $this->resetAllErrorFlags();
        }
        $this->fillWithData($modal, ['COMMUNICATE' => $communicate]);
        $this->toLayout('MODAL', $modal);
    }

    private function resetAllErrorFlags()
    {
        if (isset($_SESSION['errors'])) {
            foreach ($_SESSION['errors'] as $key => $error) {
                $_SESSION['errors'][$key] = false;
            }
        }
    }
}