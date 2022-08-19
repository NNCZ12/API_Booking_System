<?php

namespace App\Http\Controllers;
use App\Models\Tag;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ItemController extends Controller
{
    //addItem
    public function store(Request $request){
        //if (Auth::user()->owner == 1 && Auth::user()->verify == 1 ){}
         $fields = $request->validate([
                'name' => 'required|string',
                'amount' => 'required|integer',
                'active' => 'required|boolean',
                'is_not_return' => 'required|boolean',
            ]);
        
            $response = Item::create([
                'name'=> $fields['name'],
                'amount'=> $fields['amount'],
                'active'=> $fields['active'],
                'is_not_return'=> $fields['is_not_return']
            ]);

            return response()->json(['message' => 'Success!' , 'response' => $response,'status' => Response::HTTP_CREATED]);
        
    }
    
    //getItem
    public function get(Request $request)
    {
       // if (Auth::user()->owner == 1 && Auth::user()->verify == 1 || Auth::user()->admin == 1 && Auth::user()->verify == 1) {}
            if ($request->id) {
                return response()->json([
                    'message' => 'Success!',
                    'response' => Item::find($request->id),
                    'status'=> Response::HTTP_OK]);
            } if ($request->search) {
                return response()->json([
                    'message' => 'Success!',
                    'response' => Item::where('name','like','%'.$request->search.'%')->get(),
                    'status'=> Response::HTTP_OK]);
            } 
            if ($request->amount) {
                return response()->json([
                    'message' => 'Success!',
                    'response' => Item::where('amount','like','%'.$request->amount.'%')->get(),
                    'status'=> Response::HTTP_OK]);
            } 
            if ($request->active) {
                return response()->json([
                    'message' => 'Success!',
                    'response' => Item::where('active','like','%'.$request->active.'%')->get(),
                    'status'=> Response::HTTP_OK]);
            }
            if ($request->is_not_return) {
                return response()->json([
                    'message' => 'Success!',
                    'response' => Item::where('is_not_return','like','%'.$request->is_not_return.'%')->get(),
                    'status'=> Response::HTTP_OK]);
            }  
            else {
                $response = DB::table('item')->get();
    
                return response()->json([
                    'message' => 'Success!',
                    'response' => $response,
                    'status'=>Response::HTTP_OK]);
            }   
            
        return response()->json(['message' => 'not found!','status'=> Response::HTTP_NOT_FOUND]);    
    }

    //Edit
    public function edit(Request $request)
    {
       // if (Auth::user()->owner == 1 && Auth::user()->verify == 1 ){}
            $fields = $request->validate([
                'id' => 'required|numeric',
                'name' => 'string',
                'amount'=> 'integer',
                'active' => 'boolean',
                'is_not_return' => 'boolean',
                
            ]);  
            $Item = Item::find($fields['id']);
            $Item->update($request->all());
            return response()->json(['message' => 'Edit Success','response' => $Item ,'status' => Response::HTTP_CREATED]);
        
    }
   
}

