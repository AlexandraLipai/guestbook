<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 18.11.2016
 * Time: 22:28
 */
class model
{
    private $arrayMess;//массив сообщений
    private $numberMess;//кол-во сообщений
    private $messPage;//кол-во сообщ на одной стр
    private $countPage;//кол-во стр
    private $arrayMessPage;//сообщ для конкр стр
    private $numberPage;
    private $action;
    private $dataForm;
    private $error;

    public function __construct($action) {
        $this->arrayMess=array();
        $this->numberMess=0;
        $this->messPage=1;
        $this->countPage=1;
        $this->arrayMessPage=array();
        $this->numberPage=1;
        $this->action=$action;
        $this->dataForm['name']='';
        $this->dataForm['message']='';
        $this->dataForm['email']='';
        $this->error='';

    }

    private function write(){
        $this->dataCheck('name', array(3,20));
        $this->dataCheck('email', array(5,30));
        $this->dataCheck('message', array(3,220));

        if ($this->error) return;

        $str = $this->dataForm['name'] . "\t" . $this->dataForm['email']. "\t" . $this->dataForm['message'] . "\t" . date("j.m.Y в H:i") . "\n";

        if (file_put_contents('guestbook.txt', $str, FILE_APPEND | LOCK_EX) === false) {
            $this->error .= 'Не удалось сохранить сообщение<br/>';
            return;}

        header('Location: http://' . $_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_NAME']);
        exit();
    }

    private function dataCheck($key, array $length) {//проверка данных
        if(!empty($_POST[$key]))
            $this->dataForm[$key]=$_POST[$key];
        $this->dataForm[$key]=trim($this->dataForm[$key]);
        $countSymbols=mb_strlen( $this->dataForm[$key]);

        if($countSymbols<$length[0] | $countSymbols>$length[1])

            $this->error .='Количество символов в поле '.$key.' должно находиться в пределах от  '.$length[0].' до ' .$length[1].'<br>';

        $this->dataForm[$key] = str_replace("\t", ' ', $this->dataForm[$key]);
        $this->dataForm[$key] = str_replace(array("\r\n", "\n\r", "\r", "\n"), '<br/>', $this->dataForm[$key]);
        $this->dataForm[$key] = htmlspecialchars($this->dataForm[$key], ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    private function read(){
        if (($this->arrayMess = file('guestbook.txt'))===false)
        {
            throw new Exception('Невозможно прочитать файл');
        }
        $this->arrayMess=array_reverse($this->arrayMess);
        $this->numberMess=count($this->arrayMess);//кол-во сообщ всего
        $this->countPage=ceil($this->numberMess/$this->messPage);//кол-во страниц
        if ($this->numberPage<1||$this->numberPage>$this->countPage){throw new Exception('Ошибка - нет такой страницы');}
        $firstMess=($this->numberPage-1)*$this->messPage;
        $this->arrayMessPage=array_slice($this->arrayMess,$firstMess, $this->messPage);
    }


    public function setMess($messPage){
        $this->messPage=$messPage;
    }
    public function run ($numberPage = 1){
        $this->numberPage = $numberPage;
        $this->{$this->action}();}

    public function getMess () {
        return $this->arrayMessPage;
    }
    public function getPage() {
        return $this->countPage;
    }

    public function getDataForm() {
        return $this->dataForm;
    }
    public function getError() {
        return $this->error;
    }
}