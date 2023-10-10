<?php
namespace App\Helpers;
use DB;
use GuzzleHttp\Client;
use App\Models\UserModel; 

class GeneralFunction {
    public static  function generateCode($prefix) {
        try
        {   
            $prefix = trim(strtoupper($prefix));
            $next_code =  $prefix.uniqid().rand(10000,99999);
            return $next_code;
        }    
        catch (\Exception $e)
        {        
            return $e->getMessage();
        }       
    }     

    public static function defaultResponJson($status,$code,$message,$data) {
        try
        {   
           $respon = ['status'  => trim(strtolower($status)),
                      'code'    => trim(strtolower($code)),
                      'message' => trim(strtolower($message)),
                      'data'    => $data];

            return $respon;
        }    
        catch (\Exception $e)
        {        
            return $e->getMessage();
        }       
    }      

    public static function checkToken($token) {
        try
        {  
           $user = new UserModel();  
           $data = $user::where([['token','=',$token],['is_active','=','yes'],['is_delete','=','no']])->first();
           
           if(!empty($data)) {
             $dt['email'] = $data['email'];
             $dt['user_id'] = $data['id'];
             $dt['fullname'] = $data['fullname'];
             return $dt;   
           } else {
             throw new \Exception("token not found");
           }
        }    
        catch (\Exception $e)
        {        
            return [];
        }       
    }     
  
}