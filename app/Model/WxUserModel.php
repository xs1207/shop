<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;

class WxUserModel extends Model
{
    //
    public $table='p_wx_users';
    public $timestamps=false;

}