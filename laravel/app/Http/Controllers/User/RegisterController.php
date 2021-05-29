<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Response\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserRequests;
use App\Http\Resources\User as UserResource;
use Spatie\Permission\Models\Role;

class RegisterController extends BaseController
{

    function __construct()
    {
         $this->middleware('permission:user-create', ['only' => ['register']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $roles = Role::pluck('name','name')->all();
        $users = User::all();

        return $this->sendResponse(UserResource::collection($users, $roles), 'User retrieved successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $users = User::find($id);
  
        if (is_null($users)) {
            return $this->sendError('User not found.');
        }
   
        return $this->sendResponse(new UserResource($users), 'User retrieved successfully.');
    }
    
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(UserRequests $request)
    {
        $input = $request->all();

        $input['password'] = bcrypt($input['password']);
        $users = User::create($input);
        
        $users->assignRole($request->input('roles'));
        $success['token'] = $users->createToken('blog-app')->plainTextToken;
        $success['name'] =  $users->name;
   
        return $this->sendResponse($success, 'User register successfully.');
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('blog-app')->plainTextToken; 
            $success['name'] =  $user->name;
   
            return $this->sendResponse($success, 'User login successfully.');
        } 
        else{ 
            return $this->sendError('Unauthorized.', ['error'=>'Unauthorized']);
        } 
    }

    public function logout(Request $request)
    {
        if($request->user()){
            auth()->user()->currentAccessToken()->delete();

            return response()->json(['message' => 'User successfully signed out']);
        }
    }
}
