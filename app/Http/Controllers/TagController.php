<?php

namespace App\Http\Controllers;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

class TagController extends Controller
{

    public function store(Request $request){
        //if (Auth::user()->owner == 1 && Auth::user()->verify == 1 || Auth::user()->admin == 1 && Auth::user()->verify == 1) {}
            $request->validate([
                'name' => 'required|string',
            ]);
        
            $response = Tag::create($request->all());

            return response()->json(['message' => 'Success!' , 'response' => $response,Response::HTTP_CREATED]);
        
        //return response()->json(['message' => 'not found!'], Response::HTTP_NOT_FOUND);
    }

    public function get(Request $request)
    {
        if ($request->id) {
            return response()->json([
                'message' => 'Success!',
                'response' => Tag::find($request->id),
                'status'=> Response::HTTP_OK]);
        } if ($request->search) {
            return response()->json([
                'message' => 'Success!',
                'response' => Tag::where('name','like','%'.$request->search.'%')->get(),
                'status'=> Response::HTTP_OK]);
        } 
        else {
            $response = DB::table('tag')->get();

            return response()->json([
                'response' => $response,
                'message' => 'Success!',
                'status'=> Response::HTTP_OK]);
        }
        return response()->json(['message' => 'not found!','status'=> Response::HTTP_NOT_FOUND]);    
    }

    public function edit(Request $request)
    {
        // if (Auth::user()->owner == 1 && Auth::user()->verify == 1 || Auth::user()->admin == 1 && Auth::user()->verify == 1) {
        //     $response = Tag::find($request->id);
        //     $response->update($request->all());
        //     return response()->json(['message' => 'Edit Success','response' => $response ,Response::HTTP_Created]);
        // }
        // return response()->json(['message' => 'not found!'], 404);  
        $fields = $request->validate([
            'id' => 'required|numeric',
            'name' => 'required|string',
        ]);  
        return response()->json(['message' => $fields,'status'=> Response::HTTP_NOT_FOUND]);  
    }

}
