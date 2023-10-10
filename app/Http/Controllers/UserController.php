<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Session;
use DB;
use GuzzleHttp\Client;
use App\Helpers\GeneralFunction;  
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redis;
use App\Models\UserModel;
use Illuminate\Support\Facades\Hash;



class UserController extends BaseController
{


  public function create(Request $request) { 
     DB::beginTransaction();
     try {

           $dt['role'] = trim($request->role);
           $dt['email'] = trim($request->email);
           $dt['password'] = Hash::make(trim($request->password));
           $dt['fullname'] = trim($request->fullname);

           $user = new UserModel();
           $data = $user::create($dt);
 
           DB::commit(); 
       
           return response()->json(GeneralFunction::defaultResponJson('success',200,'create success',$data));
      }
      catch (\Exception $e) {
        DB::rollback();
        $data = [];
        if($e->getCode='2300') {
           $data['validation_mesage'] = 'email '.$request->email. 'not available';
        }
        return response()->json(GeneralFunction::defaultResponJson('failed',500,$e->getMessage(),$data));
      }
  }    

 public function login(Request $request) { 
     DB::beginTransaction();
     try {
           $email = trim($request->email);
           $password = trim($request->password);

           $user = new UserModel();
           $data = $user::where([['email','=',$email],['is_active','=','yes'],['is_delete','=','no']])->first();
           
           if(!empty($data)) {
             if (Hash::check($password, $data['password'])) {
                $dt['token'] = md5(uniqid());
                $data['token'] = $dt['token'];
                $user = new UserModel();
                $user::where([['email','=',$email]])->update($dt);
                DB::commit();
               } else {
                throw new \Exception("password not match");
              }
           } else {
             throw new \Exception("user email not found");
           }
       
           return response()->json(GeneralFunction::defaultResponJson('success',200,'login success',$data));
      }
      catch (\Exception $e) {
        DB::rollback();
        $data = [];
        if($e->getCode='2300') {
           $data['validation_mesage'] = $e->getMessage();
        }
        return response()->json(GeneralFunction::defaultResponJson('failed',500,$e->getMessage(),$data));
      }
  }     
}
    