<?php
declare(strict_types=1);

use App\Models\User;
use App\Models\Address;

class UserController extends ControllerBase
{

    public function listUserAction()
    {
        $this->view->disable();
        foreach (User::find() as $user) {
            $user->typeUser();
            $user->convertDate();
            $users[] = $user;
        }
        return json_encode($users);
    }

    public function userCreateAction()
    {
        if ($post = $this->request->getPost()) {

            $user = new User();
            $user->firstname = $post['firstname'];
            $user->lastname = $post['lastname'];
            $user->email = $post['email'];
            $user->user_type = $post['user_type'];
            $user->password = $this->security->hash($post['password']);
            $user->created_at = time();

            if ($user->save() === false) {
                $messages = $user->getMessages();
                $errorMsg = '';
                foreach ($messages as $message) {
                    $errorMsg .= "{$message} ";
                }
                $this->flashSession->error("Error: $errorMsg");
           } else {
                $this->flashSession->success("OK");
           }
        }
    }

    public function userAddressAction()
    {
        if (($id = $this->request->get('id')) && $this->request->isAjax()) {
            $this->view->disable();
            return json_encode(User::findFirst($id)->getAddress());
        }
    }

    public function userAddressDeleteAction()
    {
        if (($id = $this->request->get('id')) && $this->request->isAjax()) {
                $this->view->disable();
                $address = Address::findFirst($id)->delete();
                $response = ['status' => true];
        }
        return json_encode($response ?? ['status' => false]);
    }

    public function createAddressAction()
    {
        if ($post = $this->request->getPost()) {

            $address = new Address();
            $address->user_id = $post['user_id'];
            $address->city = $post['city'];
            $address->postcode = $post['postcode'];
            $address->region = $post['region'];
            $address->street = $post['street'];

            if ($address->save() === false) {
                $messages = $address->getMessages();
                $errorMsg = '';
                foreach ($messages as $message) {
                    $errorMsg .= "{$message} ";
                }
                $this->flashSession->error("Error: $errorMsg");
           } else {
                $this->flashSession->success("OK");
           }
        }
        return $this->response->redirect('/user/userAddress?id=' . $post['user_id'] ?? '');
    }

    public function listAddressAction1()
    {
        if ($this->request->isAjax()) {
            $this->view->disable();
            return json_encode(Address::find());
        }
        return $this->view->users = User::find(['columns' => 'user_id, firstname']);
    }

    public function listAddressAction()
    {
        if ($this->request->isAjax()) {
            $this->view->disable();
            $id = $this->request->get('id');
            $address = $id ? User::findFirst($id)->getAddress()->toArray() :Address::find()->toArray();
            $paginator   = new Phalcon\Paginator\Adapter\NativeArray(
                [
                    "data"  => $address,
                    "limit" => 5,
                    "page"  => $this->request->get('currentPage') ?? 1,
                ]
            );
            return json_encode($paginator->paginate());
        }
        return $this->view->users = User::find(['columns' => 'user_id, firstname']);
    }

    public function userApiAction()
    {
        return $this->view->users = User::find(['columns' => 'user_id, firstname']);
    }

}
