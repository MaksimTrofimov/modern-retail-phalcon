<?php
declare(strict_types=1);

use App\Models\User;

class ApiController extends ControllerBase
{
    const REQUIRE_PARAM = ['email', 'password'];
    /**
     * @var object
     */
    private $user;
    /**
     * Request email
     *
     * @var string
     */
    private $email;
    /**
     * Request password
     *
     * @var string
     */
    private $password;
    /**
     * @var object
     */
    private $dataResponse;
    /**
     * @var string
     */
    private $status = 'fail';

    private $errorMessage = [
        'method'        => 'Wrong request method.',
        'param'         => 'Invalid input parameters.',
        'email'         => 'This email does not exist',
        'password'      => 'Wrong password',
        'permissions'   => 'You do not have permissions',
    ];
    /**
     * @return void
     */
    public function usersListAction()
    {
        if ($param = $this->request->getPost()) {
            $this->checkParam($param);
            $this->getUserByEmail();
            $this->authentication();
            $this->authorization();
            $this->listOrOneUser($this->request->get('_url'));
        } else {
            $this->response($this->errorMessage['method']);
        }
    }

    /**
     * @param mixed $param
     *
     * @return void
     */
    private function checkParam($param)
    {
        $countParam = count(array_intersect_key($param, array_flip(self::REQUIRE_PARAM)));
        if (count(self::REQUIRE_PARAM) == $countParam) {
            $this->email = $param['email'];
            $this->password = $param['password'];
        } else {
            $this->response($this->errorMessage['param']);
        }
    }

    /**
     * @return void
     */
    private function getUserByEmail()
    {
        $this->user = User::findFirst(
            ['conditions' => 'email = ?1',
            'bind' => [1 => $this->email]
        ]);

        if (!$this->user) {
            $this->response($this->errorMessage['email']);
        }
    }

    /**
     * @return void
     */
    private function authentication()
    {
        $auth = $this->security->checkHash($this->password, $this->user->password);
        if (!$auth) {
            $this->response($this->errorMessage['password']);
        }
    }

    /**
     * @return void
     */
    private function authorization()
    {
        if ($this->user->user_type != User::TYPE_USER_ADMIN) {
            $this->response($this->errorMessage['permissions']);
        }
    }

    /**
     * @param mixed $url
     *
     * @return void
     */
    private function listOrOneUser($url)
    {
        $user_id = preg_replace('/[^0-9]/', '', $url);
        if ($user_id) {
            $users = User::findFirst(
                ['conditions' => 'user_id = ?1',
                    'bind' => [
                        1 => $user_id
                    ],
                    'columns' => 'user_id, firstname, lastname, email, user_type, created_at',
            ]);
        } else {
            $users = User::find();
        }
        $this->dataResponse = $users;
        $this->status = 'succsess';
        $this->response('');
    }

    /**
     * @param mixed $errorMessage
     *
     * @return void
     */
    private function response($errorMessage)
    {
        echo json_encode(['status' => $this->status, 'error' => $errorMessage, 'data' => $this->dataResponse]);
        die();
    }

}