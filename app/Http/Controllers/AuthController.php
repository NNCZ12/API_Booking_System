<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Follow;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Symfony\Component\HttpFoundation\Response;
//use Illuminate\Http\Response;
//use Illuminate\Http\Client\Response;


function admimn_and_ownerRole() {
    if (Auth::user()->admin == 1 && Auth::user()->verify == 1|| Auth::user()->owner == 1 && Auth::user()->verify == 1 ) {
        return true;
    }else{
        return false;
    }
} 

class AuthController extends Controller
{

    //Register
    public function register(Request $request)
    {   
        // $this->authorize('create-delete-users');
        // $usercreate=User::create($request->validated());
        // return response($usercreate);
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed',
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            
        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'message' => 'Success, please wait for verify!',
            'user' => $user,
            'token' => $token,
            'status' => Response::HTTP_CREATED
        ];


        
        return response()->json($response);
    }

    //Login
    public function login(LoginRequest $request)
    {   
        if(!Auth::attempt($request->only('email','password'))){
            return response([
                'errors'=>'Invalid credentials'
            ],Response::HTTP_UNAUTHORIZED);
        }

         $user = Auth::user();

         $user->tokens()->delete();
         $token = $user->createToken('token')->plainTextToken;

        return response([
            'jwt'=>$token
        ]);
        /*$fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);
    
        $user = User::where('email', $request['email'])->firstOrFail();
            
            $user->tokens()->delete();
            $token = $user->createToken('myapptoken')->plainTextToken;
    
            $response = [
                'user' => $user,
                'token' => $token
            ];*/
                

            //return response()->json($response, 201);
    }

    //Logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return ['massage' => 'logged out'];
    }

    //verify
    public function verify(Request $request)   
    {
        if (Auth::user()->admin == 1 && Auth::user()->verify == 1) {
            
            $user = User::find($request->user_id);

            if ($user->owner == 0 && $user->verify == 0) {
                $user->update([
                    'owner' => 1,
                    'verify' => 1,
                ]);
            }
            return response()->json(['message' => 'you verify is owner','status'=> Response::HTTP_OK ]); //ในมุมแอดมิน
        }
        if (Auth::user()->owner == 1 && Auth::user()->verify == 1) {
            $user = User::find($request->user_id);

            if ($user->verify == 0) {
                $user->update([
                    'verify' => 1, 
                ]);
            }
            return response()->json(['message' => 'you verify is user','status'=> Response::HTTP_OK]); //ในมุมโอนเนอร์
        }
        return response()->json(['message' => 'not found!','status'=> Response::HTTP_NOT_FOUND]);
    }

    //Follow
    public function follow(Request $request){
        if (Auth::user()->verify == 1) {
            $follow = User::where('id', $request['owner_id'])->firstOrFail();
             
            if ($follow && $follow->owner == 1 && $follow->verify == 1) {

                $response = Follow::create([
                    'user_id' => Auth::user()->id,
                    'owner_id' => $request['owner_id'],
                ]);
                return response()->json([
                    'message' => 'Success! you followed',
                    'response' => $response,
                ], Response::HTTP_OK);
            }else{
                return response()->json(['message' => 'follow not owner!','status'=> Response::Internal_Server_Error]);
            }
        }
        return response()->json(['message' => 'not found!','status'=> Response::HTTP_NOT_FOUND]);
    }

    public function get(Request $request)
    {
        if (Auth::user()->admin == 1 && Auth::user()->verify == 1 || Auth::user()->owner == 1 && Auth::user()->verify == 1 ) {
            if ($request->id) {
                return response()->json([
                    'message' => 'Success!',
                    'response' => User::find($request->id),
                    'status'=> Response::HTTP_OK]);
            } if ($request->search) {
                return response()->json([
                    'message' => 'Success!',
                    'response' => User::where('email','like','%'.$request->search.'%')->get(),
                    'status'=> Response::HTTP_OK]);
            } 
            else {
                $response = DB::table('users')->get();
    
                return response()->json([
                    'message' => 'Success!',
                    'response' => $response,
                    'status'=> Response::HTTP_OK]);
            }
            return response()->json(['message' => 'not found!','status'=> Response::HTTP_NOT_FOUND]);
        }
        
    }

    public function owner_search(Request $request)
    {
        if (Auth::user()->verify == 1 ) {
            return response()->json([
                'message' => 'Success!',
                'response' => User::where('email','like','%'.$request->search.'%')
                ->where('owner','=','1')
                ->where('verify','=','1')
                ->get(),
                'status'=> Response::HTTP_NOT_FOUND]);
        }
        return response()->json(['message' => 'not found!','status'=> Response::HTTP_NOT_FOUND]);
    }


    public function test(Request $request)
    {
        if (admimn_and_ownerRole()) {
            return response()->json(['message' => 'admimn_owner','status'=> Response::HTTP_OK]);
        }
        return response()->json(['message' => 'not admimn_owner','status'=> Response::HTTP_OK]);
    }
}