<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Models\UserDetail;

class RegisterController extends Controller
{
    function signup(Request $request){
        return view('salon'); 
    }
    
        function register(Request $request){
            $data = $request->all();
            $success = false ;
            $result = [];
            $message = 'Something went wrong....';
            $rules=[
                'email'=>'required|unique:users',
                'password'=>'required|min:6',
                'name'=>'required',
                'phone'=>'required|max:10|min:10
            ];
          
            $validator = Validator::make($data,$rules);
            if($validator->fails()){
                $success = false;
                $message = $validator->errors();
            }else{
                $user = new User();
                $user->name=$data['name'];
                $user->email=$data['email'];
                $user->password=$data['password'];
                $user->save();
                if($user){
                    $userdetail = new UserDetail();
                    $userdetail->address=$data['address'];
                    $userdetail->phone=$data['phone'];
                    $userdetail->age=$data['age'];
                    $userdetail->user_id=$user->id;
                    $userdetail->save();
                    $result = User::find($user->id);
                    // User::where('user_id',$user->id)
                    $success=true;
                    $message="User successsfully created.";
                }
            }
            $response = [
                'error' => array('code'=>200, 'message'=>$message),
                'success' => $success,
                'result'=>$result
            ];
            return response()->json($response, 200);
        }
}
