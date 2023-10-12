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
use Illuminate\Support\Facades\Hash;


class BlogController extends BaseController
{
  public function create(Request $request) {
     DB::beginTransaction();
     try {
           $token = GeneralFunction::checkToken($request->header('token'));
           if(count($token) > 0) {
               $dt['user_id'] = trim($token['user_id']);
               $dt['title'] = trim($request->title);
               $dt['content'] = trim($request->content);
               $dt['publish_date'] = trim($request->publish_date);
               $dt['is_publish'] = trim($request->is_publish);

               $blog = new BlogModel();
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

  public function update(Request $request, $id) {
     DB::beginTransaction();
     try {
           $token = GeneralFunction::checkToken($request->header('token'));
           if(count($token) > 0) {

               $dt['title'] = trim($request->title);
               $dt['content'] = trim($request->content);
               $dt['publish_date'] = trim($request->publish_date);
               $dt['is_publish'] = trim($request->is_publish);

               $blog = new BlogModel();
               $data = $blog::where( [['id','=',$id],['is_delete','=','no'],['user_id','=',$token['user_id']]] )->update($dt);

               DB::commit();
           } else {
               throw new \Exception("token not found");
           }

           return response()->json(GeneralFunction::defaultResponJson('success',200,'update success',$data));
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

  public function delete(Request $request, $id) {
     DB::beginTransaction();
     try {
           $token = GeneralFunction::checkToken($request->header('token'));
           if(count($token) > 0) {

               $dt['is_delete'] = 'yes';
               $blog = new BlogModel();
               $data = $blog::where( [['id','=',$id],['user_id','=',$token['user_id']]] )->update($dt);

               DB::commit();
           } else {
               throw new \Exception("token not found");
           }

           return response()->json(GeneralFunction::defaultResponJson('success',200,'delete success',$data));
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

  public function read(Request $request, $id) {
     DB::beginTransaction();
     try {
           /*
           $token = GeneralFunction::checkToken($request->header('token'));
           if(count($token) > 0) {
               $blog = new BlogModel();
               $data = $blog::where( [['id','=',$id],['is_delete','=','no'],['user_id','=',$token['user_id']]] )->first();

               DB::commit();
           } else {
               throw new \Exception("data not found");
           }
           */
          $blog = new BlogModel();
          $data = $blog::where( [['id','=',$id],['is_delete','=','no']] )->first();

          DB::commit();
          return response()->json(GeneralFunction::defaultResponJson('success',200,'get data success',$data));
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

  public function list(Request $request) {
        DB::beginTransaction();
        try {
           $blog = new BlogModel();
           $keyword = trim($request->keyword);
           $filter_field = trim($request->filter_field);

           $blog = DB::table('blog')
            ->join('users', 'users.id', '=', 'blog.user_id')
            ->select('blog.*','users.email','users.fullname')
            ->where([['blog.is_delete','=','no'],['is_publish','=','yes']] )
            ->orderBy('publish_date', 'desc');

           if(!empty($keyword) && !empty($filter_field)) {
            $blog = $blog->where([[$filter_field,'like','%'.$keyword.'%']]);
           }

           $data = $blog->get();
           DB::commit();

           return response()->json(GeneralFunction::defaultResponJson('success',200,'get data success',$data));
        }
            catch (\Exception $e) {
            DB::rollback();
            $data = [];
            if($e->getCode='42S22') {
               $data['validation_mesage'] = 'filter field not found';
            }
            return response()->json(GeneralFunction::defaultResponJson('failed',500,$e->getMessage(),$data));
        }
  }
}
