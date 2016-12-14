<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 18.11.2016
 * Time: 22:29
 */
class Controller
{
    private $model;
    private $view;
    private $numberPage;
    private $action;

    function __construct()
    {$this->numberPage = isset($_GET['number']) ? intval($_GET['number']) : 1;
        if (!empty($_POST)) $this->action = 'write'; else $this->action = 'read';
    }
    public function run() {
        try {
            $this->model = new Model($this->action);
            $this->model->setMess(MESSAGES_PER_PAGE);
            $this->model->run($this->numberPage);

            $this->view = new View($this->numberPage, $this->model->getPage(), PAGINATION_INDENT);
            $this->view->render($this->model->getMess(), $this->model->getDataForm(), $this->model->getError());
        }
        catch(Exception $e) {
            echo 'ОШИБКА<br/>' . $e->getMessage();
        }
    }
}