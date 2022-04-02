<?php

use Phalcon\Mvc\Controller;


class SettingsController extends Controller
{
    public function indexAction()
    {

        //$this->view->users = Users::find();
        // return '<h1>Hello World!</h1>';
    }
    public function settingAction()
    {

        print_r($this->request->getPost());
       // die();

        $time_optimization  = $this->request->getPost('time_optimization');
        $default_price =  $this->request->getPost('default_price');
        $default_stock =  $this->request->getPost('default_stock');
        $default_zipcode =  $this->request->getPost('default_zipcode');
      


        $particular_column = Settings::findFirst(
            [
                "id = 1",
            ]
        );


// print_r(json_encode($particular_column));
// die();
        $particular_column->time_optimization = $time_optimization;
        $particular_column->default_price = $default_price;
        $particular_column->default_stock = $default_stock;
        $particular_column->default_zipcode = $default_zipcode;
     


        $particular_column->save();
      //  $this->response->redirect('/settings/setting');

        // $settings = new Settings();
        // if ($this->request->getPost()) {
        //     $settings->assign(
        //         $this->request->getPost(),
        //         [
        //             'time_optimization',
        //             'default_price',
        //             'default_stock',
        //             'default_zipcode'
        //         ]
        //     );
            
        //     $success = $settings->save();
            
        //     $this->view->success = $success;
       // }
    }
}
