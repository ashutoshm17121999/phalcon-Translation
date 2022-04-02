<?php

use Phalcon\Mvc\Controller;
use Phalcon\Security\JWT\Builder;
use Phalcon\Security\JWT\Signer\Hmac;
use Phalcon\Security\JWT\Token\Parser;
use Phalcon\Security\JWT\Validator;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
class SignupController extends Controller
{
    public function indexAction()
    {

        $roles = Roles::find();
        $disp = '';
        foreach ($roles as $k => $v) {
            $disp .= '<option value="' . $v->role . '">' . $v->role . '</option>';
        }

        $this->view->options = $disp;
    }
    public function signupAction()
    {
        

        $postdata = $this->request->getPost();
        $key = "example_key";
        $payload = array(
            "iss" => "http://example.org",
            "aud" => "http://example.com",
            "iat" => 1356999524,
            "nbf" => 1357000000,
            "role" => $_POST['role'],
        );
        $jwt = JWT::encode($payload, $key, 'HS256');
        //$decoded = JWT::decode($jwt, new Key($key, 'HS256'));
        
        // $signer  = new Hmac();

        // // Builder object
        // $builder = new Builder($signer);

        // $now        = new DateTimeImmutable();
        // $issued     = $now->getTimestamp();
        // $notBefore  = $now->modify('-1 minute')->getTimestamp();
        // $expires    = $now->modify('+1 day')->getTimestamp();
        // $passphrase = 'QcMpZ&b&mo3TPsPk668J6QH8JA$&U&m2';

        // // Setup
        // $builder
        //     ->setAudience('https://target.phalcon.io')  // aud
        //     ->setContentType('application/json')        // cty - header
        //     ->setExpirationTime($expires)               // exp 
        //     ->setId('abcd123456789')                    // JTI id 
        //     ->setIssuedAt($issued)                      // iat 
        //     ->setIssuer('https://phalcon.io')           // iss 
        //     ->setNotBefore($notBefore)                  // nbf
        //     ->setSubject($_POST['role'])   // sub
        //     ->setPassphrase($passphrase)                // password 
        // ;

        // // Phalcon\Security\JWT\Token\Token object
        // $tokenObject = $builder->getToken();

        // // The token
        // $token =  $tokenObject->getToken();
        //$token  = $this->getToken($postdata['role']);
        $users = new Users();
        $users->assign(
            $postdata,
            [
                'name',
                'email',
                'password',
                'role'
            ]
        );
        $users->token = $jwt;
        $success = $users->save();

        $this->view->success = $success;

        if ($success) {
            $this->view->message = "Register succesfully";
        } else {
            $this->view->message = "Not Register succesfully due to following reason: <br>" . implode("<br>", $users->getMessages());
        }
        //$this->view->message = $message;
        // $postdata = $this->get->getPost();
        // echo "<pre>";
        // print_r($_POST);
        // echo "</pre>";
        // die();


        //$this->view->users = Users::find();
        // return '<h1>Hello World!</h1>';
    }
    public function getTokenAction()
    {
    }
}
