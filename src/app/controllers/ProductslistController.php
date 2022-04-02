<?php

use Phalcon\Mvc\Controller;


class ProductslistController extends Controller
{
    public function indexAction()
    {

        //$this->view->users = Users::find();
        // return '<h1>Hello World!</h1>';
    }
    public function productlistAction()
    {
        $this->view->data = Products::find();
    }
}
