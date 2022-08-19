<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use App\Models\OutOfService;

class OutOfServiceController extends Controller
{
    public function get()
    {
        try {
            $response = DB::table('out_of_service')->get();

            return response()->json([
                'message' => 'Success!',
                'response' => $response],Response::HTTP_OK);   
    
        } catch (\Throwable $th) {
            return response()->json(['message' => $th],Response::HTTP_NOT_FOUND);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|integer',
            'note' => 'required|string',
            'amount' => 'required|integer',
        ]);   

        $out_of_service = OutOfService::create([
            'item_id' => $request->item_id,
            'note' => $request->note,
            'amount' => $request->amount,
            'ready_to_use' => 0,
            
        ]);

        return response()->json([
            'message' => 'Success!',
            'response' => $out_of_service],Response::HTTP_CREATED);        
    
    }
}
