<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

use App\Models\Order_HeadT;
use App\Models\Order_DetailT;

class OrderController extends Controller
{
    public function getAll(){
        $res =  Order_HeadT::select('id','id_supplier','po_number','po_time','po_assigne','ro_number','ro_date','ro_assigne','is_approved')
                ->with([
                   'supplier',
                   'order_detail'
                ])
                ->get();
                // ->map(function($key){
                //     return [
                //         'id'            => $key->id,
                //         'description'   => $key->description,
                //         'SKU'           => $key->SKU,
                //         'brand'         => $key->brand,
                //         'product_type'  => $key->product_type->name,
                //         'ncc'           => $key->ncc->name
                //     ];
                // });
        return response([
            'ok'      => true,
            'data'    => $res
        ],200);
    }

    public function postOrder(Request $request){
        
        $validator = Validator::make($request->all(), [
            'supplier'        => 'required',
            'po_number'       => 'required',
            'po_assigne'      => 'required',
        ]);
        
        if ($validator->fails()) {
            return response([
                'ok'        => false,
                'message'   => $validator->errors()->first()
            ]);
        }

        //check jika gak ada id nya maka dia post
        DB::beginTransaction();
        if($request->input('id') == ''){
            try {
                $order = Order_HeadT::create([
                            'id_supplier'       => $request->input('supplier'),
                            'po_number'         => $request->input('po_number'),
                            'po_time'           => Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s'),
                            'po_assigne'        => $request->input('po_assigne'),
                            'ro_number'         => $request->input('ro_number'),
                            'ro_date'           => $request->input('ro_date'),
                            'ro_assigne'        => $request->input('ro_assigne'),
                            'is_approved'       => false
                        ]);

                $result = DB::commit();

                if($result === false){
                    DB::rollback();
                    return response([
                        'ok'        => false,
                        'message'   => 'Internal Server Error'
                    ]);
                }else{

                    
                    return response([
                        'ok'        => true,
                        'message'   => 'Behasil Simpan Data !!'
                    ]);
                }
                
            } catch(Exception $e) {
                return response([
                    'ok'        => false,
                    'message'   => $e->getMessage()
                ]);
            }
        }
    }
}
