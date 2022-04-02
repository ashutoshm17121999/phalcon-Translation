<?php

use Phalcon\Mvc\Controller;


class OrderaddController extends Controller
{
    public function indexAction()
    {

        //$this->view->users = Users::find();
        // return '<h1>Hello World!</h1>';

    }
    public function ordersAction()
    {
        $this->view->data = Products::find();

        $orders = new Orders();
        
        if ($this->request->isPost()) {
            $values = $_POST;
            $eventmanager = $this->di->get('EventsManager');
            $settings = Settings::find();
            $array = $eventmanager->fire('notifications:beforeOrder', (object)$values, $settings);
            $value = array(
                'customer_name' => $array->customer_name,
                'customer_address' => $array->customer_address,
                'zipcode' => $array->zipcode,
                'product' => $array->product,
                'quantity' => $array->quantity,
            );
            $orders->assign(
                $value,
                [
                    'customer_name',
                    'customer_address',
                    'zipcode',
                    'product',
                    'quantity'
                ]
            );
            $success = $orders->save();
            $this->view->success = $success;
            // print_r($orders->getMessages());
            // die();
        }
    }
}
