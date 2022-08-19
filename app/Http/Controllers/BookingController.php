<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\OutOfService;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class BookingController extends Controller
{
    public function store(Request $request)
    {

        $fields = $request->validate([
            'note_user' => 'required|string|max:255',
            'date_start' => 'required|date_format:"Y-m-d"',
            'date_end' => 'required|date_format:"Y-m-d"',
            'item_id' => 'required|integer',
            'amount' => 'required|integer',
            // 'booking_item' => 'required|array',
        ]);

        $booking = Booking::create([
            'user_id' => Auth::user()->id,
            'note_user' => $fields['note_user'],
            'date_start' => $fields['date_start'],
            'date_end' => $fields['date_start'],
            'status' => 0,
            'date_verify' => $fields['date_start'],
            
        ]);

        $booking_item = array();
        for ($x = 1; $x <= $fields['amount']; $x++) {
                $return_status = Item::find($fields['item_id']);
                $BookingItem = BookingItem::create([
                'booking_id' => $booking['id'],
                'item_id' => $fields['item_id'],
                // 'amount' => $data['amount'],
                'return_status' => $return_status['is_not_return'],
            ]);
            array_push($booking_item, $BookingItem);
          };

        // $booking_item = array();
        // foreach ($fields['booking_item'] as $data){ 
        //     $return_status = Item::find($data['item_id']);
        //     $BookingItem = BookingItem::create([
        //         'booking_id' => $booking['id'],
        //         'item_id' => $data['item_id'],
        //         'amount' => $data['amount'],
        //         'return_status' => $return_status['is_not_return'],
                
        //     ]);
        //      array_push($booking_item, $BookingItem);
        // };

        return response()->json(['message' => 'Success!' , 'response' => ['booking' => $booking,'booking_item' => $booking_item],'status' => Response::HTTP_CREATED]);


        // return response()->json(['message' => 'Success!' , 'response' => $response,'status' => 201]);

        // booking_status = 0-pending 1-approve 2-reject 
    }

    public function get(Request $request)
    {
        $response = DB::table('booking')->get();
    
        return response()->json([
            'message' => 'Success!',
            'response' => $response,
            'status'=> Response::HTTP_OK]);
    }

    public function getBookingItem(Request $request)
    {
        $response = DB::table('booking_item')->get();
    
        return response()->json([
            'message' => 'Success!',
            'response' => $response,
            'status'=> Response::HTTP_OK]);
    }

    
    // 0=pending 1=approve 2=reject
    public function approve(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|integer',
            'status' => 'required|integer',
            'note_owner' => '',
        ]);

        $booking = Booking::find($request['booking_id']);
        $booking->update([
            'status' => $request['status'],
            'note_owner' => $request['note_owner']
        ]);

        return response()->json([
            'message' => 'Success!',
            'response' => $booking,
            'status'=> Response::HTTP_OK]);
    }
    public function booking_item_amount(Request $request)
    {
        $request->validate(['item_id' => 'required|integer']);

        $itemAmount = Item::find($request['item_id']);
        if (!$itemAmount) {
            return response()->json([
                'message' => 'Success!',
                'name' => 'no item',
                'response' => 0,
                'status'=> Response::HTTP_OK]);
        }

        $bookingItemAmount = BookingItem::where('item_id','=',$request['item_id'])
            ->where('return_status','=','0')
            ->count();
        $outOfServiceAmount = OutOfService::where('item_id','=',$request['item_id'])->where('ready_to_use','=',0)->get();
        $outOfServiceAmountALL = 0;
        foreach ($outOfServiceAmount as $key => $value) {
            $outOfServiceAmountALL += $value['amount'];
        }

        $Booking_item_amount = $itemAmount['amount'] - ($bookingItemAmount + $outOfServiceAmountALL);
        return response()->json([
            'message' => 'Success!',
            'name' => $itemAmount['name'],
            'response' => $Booking_item_amount,
            'status'=> Response::HTTP_OK]);
    }

    public function delete()
    {
        DB::table('booking_item')->delete();
        DB::table('booking')->delete();
        return response()->json(['message' => 'Success!'],Response::HTTP_ACCEPTED);
    }
}
