<?php
// declare(strict_types=1);
namespace App\Console;

require_once BASE_PATH.'/vendor/autoload.php';
use Phalcon\Cli\Task;
use Firebase\JWT\JWT;
use Orders;
use Products;
// use MyApp;
use Settings;

class TasksTask extends Task
{
    public function mainAction()
    {
        echo 'Hello' . PHP_EOL;
    }

    /**
     * Function to generate a toke for the string 'admin'
     *
     * @param string $role
     * @return void
     */
    public function adminTokenAction($role = "admin")
    {
        $now        = new \DateTimeImmutable();
        $issued     = $now->getTimestamp();
        $notBefore  = $now->modify('-1 minute')->getTimestamp();
        $expires    = $now->modify('+1 day')->getTimestamp();
        $key = "example_key";
        $payload = array(
            "iss" => 'iss',
            "aud" => 'aud',
            "iat" => $issued,
            "nbf" => $notBefore,
            "exp" => $expires,
            "role" => $role
        );

        $jwt = JWT::encode($payload, $key, 'HS256');
        echo $jwt. PHP_EOL;
    }

    /**
     * function to remove log file named by user.log
     *
     * @return void
     */
    public function removelogAction()
    {
        $file = (APP_PATH.'/logs/user.log');
        $delete = unlink($file);
        if ($delete) {
            echo 'File Deleted Successfully'.PHP_EOL;
        } else {
            echo 'There is some problem'.PHP_EOL;
        }
    }

    /**
     * function to delete acl file named with acl.log
     *
     * @return void
     */
    public function removeaclAction()
    {
        $file = (APP_PATH.'/security/acl.cache');
        $delete = unlink($file);
        if ($delete) {
            echo 'File Deleted Successfully'.PHP_EOL;
        } else {
            echo 'There is some problem'.PHP_EOL;
        }
    }

    /**
     * function to return product count having stock < 10 form products table
     *
     * @return void
     */
    public function productcountAction()
    {
        $products = \Products::find("stock < 10");
        echo count($products). PHP_EOL;
    }

    /**
     * function to print the most recent order
     *
     * @return void
     */
    public function neworderAction()
    {
        
        $orders = Orders::findFirst(
            [
                'order' => 'Date DESC',
            ]
        );
        echo 'Customer Name : '.($orders->customer_name).PHP_EOL;
        echo 'Customer Name : '.($orders->customer_address).PHP_EOL;
        echo 'Customer Name : '.($orders->zipcode).PHP_EOL;
        echo 'Customer Name : '.($orders->product).PHP_EOL;
        echo 'Customer Name : '.($orders->quantity).PHP_EOL;
        //echo 'Customer Name : '.($orders->Date).PHP_EOL;
        echo PHP_EOL;
    }

    /**
     * function to set default value of price and stock in settings table
     *
     * @param [String] $key
     * @param [integer] $val
     * @return void
     */
    public function setdefaultAction($key = null, $val = null)
    {
        $settings = Settings::findFirst();
        if ($key == 'price') {
            $settings->default_price = $val;
            $settings->save();
            echo 'Price updated successfully'.PHP_EOL;
        } elseif ($key == 'stock') {
            $settings->default_stock = $val;
            $settings->save();
            echo 'Stock updated successfully'.PHP_EOL;
        } else {
            echo 'Choose key as price/stock only'.PHP_EOL;
        }
    }
}
