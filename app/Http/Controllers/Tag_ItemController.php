<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag_Item;
use App\Models\Tag;
use App\Models\Item;
use Symfony\Component\HttpFoundation\Response;

class Tag_ItemController extends Controller
{
    //add Tag_item
    public function storeTag_item(Request $request)
    {
        $request->validate([
            'item_id' => 'required|integer',
            'tag_id' => 'required|integer',
        ]);

        return $request['item_id'];
        // $item_name = Item::find($request->item_id);
        // $tag_name = Item::find($request->tag_id);

        // if (!$item_name && !$tag_name) {
        //     return response()->json([
        //         'message' => 'Success!',
        //         'item_name' => 'no item',
        //         'tag_name' => 'no item',
        //         'response' => 'no item',
        //         'status'=> Response::HTTP_OK]);
        //     }

        // $tag_item = Tag_Item::create([
        //     'item_id' => $request->item_id,
        //     'tag_id' => $request->tag_id,
        // ]);

        // return response()->json([
        //     'message' => 'Success!',
        //     'item_name' => $item_nam['name'],
        //     'tag_name' => $tag_name['name'],
        //     'response' => $tag_item,
        //     'status'=> Response::HTTP_OK]);
    }

    
    //getitemtag
     public function get_item_tag(Request $tag_item)
    {
        $tag_item = DB::table('item')
        ->join('tag_item', 'item.id', '=', 'tag_item.item_id')
        ->join('tag', 'tag_item.tag_id', '=', 'tag.id')
        ->select('item.*')
        ->get();
        return  $tag_item; 

    }  

}
