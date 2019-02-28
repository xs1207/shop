<?php

namespace App\Http\Controllers\Weixin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WeixinLogin extends Controller
{
    public function WeixinLogin()
    {
        return view ('weixin.login');
    }
}
