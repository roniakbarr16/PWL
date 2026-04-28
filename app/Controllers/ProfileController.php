<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class ProfileController extends BaseController
{
    public function index()
    {
        $data = [
            'username'   => session()->get('username'),
            'role'       => session()->get('role'),
            'email'      => session()->get('email'),
            'waktu_login' => session()->get('waktu_login'),
            'isLoggedIn' => session()->get('isLoggedIn'),
        ];

        return view('v_profile', $data);
    }
}