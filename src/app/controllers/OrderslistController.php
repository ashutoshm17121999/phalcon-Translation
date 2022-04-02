<?php

use Phalcon\Mvc\Controller;


class OrderslistController extends Controller
{
    public function indexAction()
    {

        //$this->view->users = Users::find();
        // return '<h1>Hello World!</h1>';
    }
    public function orderlistAction()
    {
        $this->view->data = Orders::find();
    }
}
