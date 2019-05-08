<?php

namespace App\Http\Controllers\Api\Test;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;

class TestController extends Controller
{
    public function test(Request $request){
//        session()->put('tree', 1);
//        session()->save();
        $data = session('tree');
        return $this->response->array(['data' => $data]);
    }
}
