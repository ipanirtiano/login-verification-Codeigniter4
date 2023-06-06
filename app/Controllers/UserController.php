<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsersModel;

class UserController extends BaseController
{
    protected $userModel;
    public function __construct()
    {
        // contect to table user
        $this->userModel = new UsersModel();
    }
    public function index()
    {
        // get data user dari table user
        $data_user = $this->userModel->findAll();

        $data = [
            'tittle' => 'Data Users',
            'data_users' => $data_user
        ];

        return view('user/index', $data);
    }
}
