<?php

use Phalcon\Mvc\Controller;


class ProductaddController extends Controller
{
    public function indexAction()
    {
    }
    public function productsAction()
    {
        $products = new Products();

        if ($this->request->getPost()) {

            $values = $_POST;
            $eventmanager = $this->di->get('EventsManager');
            $settings = Settings::find();
            $array = $eventmanager->fire('notifications:beforeSend', (object)$values, $settings);
            print_r($values);
            // die();
            $value = array(
                'name' => $array->name,
                'description' => $array->description,
                'tags' => $array->tags,
                'price' => $array->price,
                'stocks' => $array->stocks,
            );
            $products->assign(
                $value,
                [
                    'name',
                    'description',
                    'tags',
                    'price',
                    'stocks'
                ]
            );
            $success = $products->save();
            $this->view->success = $success;
        }
    }
}
