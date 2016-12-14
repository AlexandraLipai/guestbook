<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 18.11.2016
 * Time: 22:29
 */
class View
{
    private $arrayMessPage;
    private $numberPage;
    private $countPage;
    private $paginationIndent;
    private $dataForm;
    private $error;


    public function __construct($numberPage, $countPage, $paginationIndent) {
        $this->arrayMessPage = array();
        $this->numberPage = $numberPage;
        $this->countPage = $countPage;
        $this->paginationIndent = $paginationIndent;
    }

    public function render($arrayMessPage, $dataForm, $error) {
        $this->arrayMessPage = $arrayMessPage;
        $this->dataForm = $dataForm;
        $this->error = $error;
        $this->renderHeader();
        $this->renderForm();
        $this->renderPagination();
        $this->renderMessages();
        $this->renderFooter();
    }

    private function renderHeader() {
        echo '<!DOCTYPE html><head><title>Страница ' . $this->numberPage .'</title>
       <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
        </head><body class="container"><h2>Гостевая книга</h2>';
    }

    private function renderMessages() {
        foreach ($this->arrayMessPage as $message) {

            $arr = explode("\t", $message);

            echo '<p> ' . $arr[3] . '<span style="color:red;">' . $arr[0] . '
            </span>  <a href' . $arr[1] .' >' . $arr[1] .'</a>
             написал: </p><b><p>' . $arr[2] . '</p></b>';

            echo '<hr/>';
        }
    }

    private function renderPagination() {
        if ($this->countPage<2) return;
        $start = $this->numberPage - $this->paginationIndent;

        if ($start<1) $start = 1;
        $finish = $this->numberPage + $this->paginationIndent;

        if ($finish>$this->countPage) $finish = $this->countPage;
        for ($i = $start; $i<=$finish; $i++) {

            if ($i==$this->numberPage)
                echo $i.'&nbsp;';
            else
                echo '<a href="' . basename($_SERVER['SCRIPT_FILENAME']) . '?number=' . $i . '">' . $i . '</a>&nbsp;';
        }echo '<br/><br/>';
    }

    private function renderFooter() {
        echo '</body></html>';
    }
    private function check(){
        if (isset($_POST['name'])) echo $_POST['name'];
    }
    private function renderForm() {
        if ($this->error)
            echo '<div>ПРИ ВВОДЕ ДОПУЩЕНЫ ОШИБКИ:<br/>' . $this->error . '</div>';
        echo
        '<form method="post" action="index.php">
            Ваше имя<br/><input type="text" size="20" maxlength="20" name="name"  placeholder="your name" value="'.$this->check().'"><br/><br/>
            E-mail<br/><input type="email" name="email" placeholder="your email"> <br><br>
            Ваше сообщение<br/><textarea name="message" cols="150" rows="5" placeholder="your message"></textarea><br/>
            <input type="submit" value="Отправить"><br/><br/>
        </form>';
    }
}