<?php

namespace App\Http\Controllers\Account;

use App\Models\Listing;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Store;
use App\Models\Bank;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class StoresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        return 'love u';
     $stores = Store::where('user_id', auth()->user()->id)->where('bank_id', '!=', '')->where('account_number', '!=', '')->orderBy('created_at', 'DESC')->paginate(10);
     if (count($stores) == 0){
        $banks  = Bank::all();
        return view('account.store_get_started', compact('banks'));
    }else {

        return view('account.stores', compact('stores'));
    }
}

public function viewstore($id){
    $stores = Store::where('user_id', auth()->user()->id)->where('store_category_id', $id)->orderBy('created_at', 'DESC')->paginate(10);

    $orders = Order::where('seller_id', $id)->get();
        $daysdiff =  Order::calculateDateOrderDiff($orders); //orders

        $declined_orders = Order::where('seller_id', $id)->where('declined_at', '!=',  NULL)->get();//decline orders
        $total_declined_orders = count($declined_orders);

        $total_orders = count($orders);

        $shipping_orders = Order::where('seller_id', $id)->where('accepted_at', '!=',  NULL)->get();
        $shipping_orders48 = Order::calculateShippingOrder48($shipping_orders);

        $bestSellingProducts=Order::select('listing_id')->selectRaw('COUNT(*) AS count')->groupBy('listing_id')
        ->orderByDesc('count')->limit(1)->get();

        $no_approve_products = Listing::where('user_id', auth()->user()->id)->where('is_admin_verified', '!=',  NULL)->where('store_id', $id)->get();//decline orders
        $no_approve_products = count($no_approve_products);

        $no_decline_products = Listing::where('user_id', auth()->user()->id)->where('is_admin_verified', '=',  NULL)->where('store_id', $id)->get();//decline orders
        $no_decline_products = count($no_decline_products);


        $rejected_products_photo = Listing::where('user_id', auth()->user()->id)->where('is_admin_verified', '=',  NULL)->where('photo', '=',  NULL)->where('store_id', $id)->get();//rejected products
        $rejected_products_photo = count($rejected_products_photo);

        $rejected_products_quality = Listing::where('user_id', auth()->user()->id)->where('is_admin_verified', '=',  NULL)->where('quantity', '<',  1)->where('store_id', $id)->get();//rejected products
        $rejected_products_quality = count($rejected_products_quality);


        $outOfStock = count(Listing::where('user_id', auth()->user()->id)->where('quantity', '=',  0)->get());

        $products =  Listing::where('user_id', auth()->user()->id)->where('store_id', $id)->get();
        $newlyCreatedProducts = Order::calculateNewProducts14($products);

        \Session::put('store_id', $id);

        return view('account.store_overview', compact('stores', 'daysdiff', 'total_declined_orders',
            'shipping_orders48', 'bestSellingProducts','outOfStock','newlyCreatedProducts', 'no_approve_products',
            'no_decline_products', 'rejected_products_photo', 'rejected_products_quality' ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    public function uploadFile(Request $request)
    {
       $validator = Validator::make($request->all(),
        [
            'file' => 'image',
        ],
        [
            'file.image' => 'The file must be an image (jpeg, png, bmp, gif, or svg)'
        ]);
       if ($validator->fails())
        return array(
            'fail' => true,
            'errors' => $validator->errors()
        );
    $extension = $request->file('file')->getClientOriginalExtension();
    $dir = 'uploads/';
    $filename = uniqid() . '_' . time() . '.' . $extension;
    $request->file('file')->move($dir, $filename);


   //  $store = Store::where('user_id', auth()->user()->id)->where('bank_id', '=', NULL)->first();
   // $store->bank_id = $request->bank_id;
   // $store->account_name = $request->account_name;
   // $store->account_number = $request->account_number;
   // $store->save(); 

   
    return $filename;

}

public function updateProfile(Request $request)
{ 

   $store = Store::where('user_id', auth()->user()->id)->where('bank_id', '=', NULL)->first();
   $store->bank_id = $request->bank_id;
   $store->account_name = $request->account_name;
   $store->account_number = $request->account_number;
   $store->save(); 

   $stores = Store::where('user_id', auth()->user()->id)->where('bank_id', '!=', '')->where('account_number', '!=', '')->orderBy('created_at', 'DESC')->paginate(10);
   if (count($stores) == 0){
    $banks  = Bank::all();
    return view('account.store_get_started', compact('banks'));
}else {

    return view('account.stores', compact('stores'));
}

}
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
