<?php

use Phalcon\Mvc\Controller;
use Phalcon\Acl\Adapter\Memory;
use Phalcon\Acl\Component;
use Phalcon\Acl\Role;

use Phalcon\Security\JWT\Builder;
use Phalcon\Security\JWT\Signer\Hmac;
use Phalcon\Security\JWT\Token\Parser;
use Phalcon\Security\JWT\Validator;

class SecureController extends Controller

{
    public function tokenAction()
    {
        $signer  = new Hmac();

        // Builder object
        $builder = new Builder($signer);

        $now        = new DateTimeImmutable();
        $issued     = $now->getTimestamp();
        $notBefore  = $now->modify('-1 minute')->getTimestamp();
        $expires    = $now->modify('+1 day')->getTimestamp();
        $passphrase = 'QcMpZ&b&mo3TPsPk668J6QH8JA$&U&m2';

        // Setup
        $builder
            ->setAudience('https://target.phalcon.io')  // aud
            ->setContentType('application/json')        // cty - header
            ->setExpirationTime($expires)               // exp 
            ->setId('abcd123456789')                    // JTI id 
            ->setIssuedAt($issued)                      // iat 
            ->setIssuer('https://phalcon.io')           // iss 
            ->setNotBefore($notBefore)                  // nbf
            ->setSubject('account')   // sub
            ->setPassphrase($passphrase)                // password 
        ;

        // Phalcon\Security\JWT\Token\Token object
        $tokenObject = $builder->getToken();

        // The token
        $token= $tokenObject->getToken();
    }
    public function indexAction()
    {

        $roles = new Roles();
        if ($this->request->getPost()) {
            $roles->assign(
                $this->request->getPost(),
                'role'
            );
            // $roles->token=$token;
            $success = $roles->save();
            $this->view->success = $success;
            // $this->view->users = Users::find();
        }
    }

    public function BuildAction()
    {
        $data = Permission::find();
        // echo "<pre>";
        // print_r(json_encode($data));
        // echo "</pre>";Z
        // die;
        $aclFile = APP_PATH . '/security/acl.cache';
        if (true !== is_file($aclFile)) {
            // acl does not exist build it
            $acl = new Memory();
            foreach ($data as $k => $v) {
                $acl->addRole($v->role);
                $acl->addComponent($v->controller, json_decode($v->action));
                $acl->allow($v->role, $v->controller, json_decode($v->action));
            }
            // print_r(json_encode($acl));

            // $acl->addRole('admin');
            // $acl->addRole('customer');
            // $acl->addRole('guest');

            // $acl->addComponent(
            //     'test',
            //     [
            //         'eventtest'
            //     ]
            // );

            // $acl->allow('admin', 'test', 'eventtest');

            // $acl->allow('guest', 'test', 'eventtest');

            file_put_contents(
                $aclFile,
                serialize($acl)
            );
        } else {
            $acl = unserialize(
                file_get_contents($aclFile)
            );
        }

        // if (true == $acl->isAllowed('admin', 'test', 'eventtest')) {
        //     echo "Access Granted";
        // } else {
        //     echo "Access Denied";
        // }
    }
    public function componentsAction()
    {
        $controller = new Components();


        if ($this->request->getPost()) {
            $controller->assign(
                $this->request->getPost(),
                'controller'
            );

            $success = $controller->save();
            $this->view->success = $success;
            // $this->view->users = Users::find();
        }
    }
    public function actionsAction()
    {
        $this->view->dropdown = Components::find();
    }
    public function addactionAction()
    {
        $action = new Actions();
        $action->assign(
            $this->request->getPost(),
            [

                'action',
                'id'
            ]
        );
        // print_r((json_encode($action)));
        // die;
        $action->save();
        $this->response->redirect('/index');
    }
    public function permissionAction()
    {
        $this->view->role = Roles::find();
        $this->view->controller = Components::find();
        // $this->view->action = Action::find();
    }

    public function addpermissionAction()
    {
        $data = $this->request->getPost();


        $count = count($_POST);
        $action = array_slice($_POST, 2, $count - 3);

        $actions = array();
        foreach ($action as $k => $v) {
            array_push($actions, $v);
        }
        $fill = new Permission();
        $actions = json_encode($actions);
        $value = array(
            'role' => $data['roles'],
            'controller' => $data['controller'],
            'action' => $actions
        );
        $fill->assign(
            $value,
            [
                'role',
                'controller',
                'action'
            ]
        );
        $fill->save();
        $this->response->redirect('/secure/permission');
    }
}
