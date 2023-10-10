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
use App\Models\BlogModel;
use App\Models\BlogCommentModel;
use App\Models\BlogLikeModel;
use App\Models\BlogCommentLikeModel;
use Illuminate\Support\Facades\Hash;


class BlogCommentLikeController extends BaseController
{
  public function create(Request $request) { 
     DB::beginTransaction();
     try {
           $token = GeneralFunction::checkToken($request->header('token'));
           if(count($token) > 0) {
               $dt['user_id'] = trim($token['user_id']);
               $dt['blog_comment_id'] = trim($request->blog_comment_id);
               $dt['is_like'] = trim($request->is_like);

               $blog = new BlogCommentLikeModel();
               $data = $blog::create($dt);
               DB::commit(); 
           } else {
               throw new \Exception("token not found");
           }    
           
           return response()->json(GeneralFunction::defaultResponJson('success',200,'create success',$data));
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
    