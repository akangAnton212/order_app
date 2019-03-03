<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use App\Models\ProductM;

class ProductController extends Controller
{
    public function getAll(){
        $res =  ProductM::select('id','description','SKU','brand','id_producttype','id_ncc')
                ->with([
                    'product_type'      => function($query){
                        return $query->select('uid','name');
                    },
                    'ncc'               => function($query){
                        return $query->select('uid','name');
                    }
                ])
                ->get()
                ->map(function($key){
                    return [
                        'id'            => $key->id,
                        'description'   => $key->description,
                        'SKU'           => $key->SKU,
                        'brand'         => $key->brand,
                        'product_type'  => $key->product_type->name,
                        'ncc'           => $key->ncc->name
                    ];
                });
        return response([
            'ok'      => true,
            'data'    => $res
        ],200);
    }

    public function postProduct(Request $request){
        
        $validator = Validator::make($request->all(), [
            'description'     => 'required|string',
            'SKU'             => 'required',
            'brand'           => 'required|string',
            'product_type'    => 'required',
            'ncc'             => 'required',
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
                $ncc = ProductM::create([
                    'id_producttype'    => $request->input('product_type'),
                    'id_ncc'            => $request->input('ncc'),
                    'SKU'               => $request->input('SKU'),
                    'description'       => $request->input('description'),
                    'brand'             => $request->input('brand')
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
        }else{
            try {
                $ncc = ProductM::where('uid', $request->input('id'))
                ->update([
                    'id_producttype'    => $request->input('product_type'),
                    'id_ncc'            => $request->input('ncc'),
                    'SKU'               => $request->input('SKU'),
                    'description'       => $request->input('description'),
                    'brand'             => $request->input('brand')
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
                        'message'   => 'Behasil Update Data !!'
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

    public function deleteProduct(Request $request){
        $id       = $request->input('id');
        try{
            DB::beginTransaction();

            $response = ProductM::where('uid',$id)->delete();

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
                    'message'   => 'Data Berhasil Di Hapus!!'
                ]);
            }
       }catch  (Exception $e) {
            return response([
                'ok'        => false,
                'message'   => $e->getMessage()
            ]);
       }
    }

    public function getDataBykey(Request $request){
        $res;
        if($request->query('id')){
            $res = ProductM::where('uid' , $request->query('id'))
                    ->select('id','description','SKU','brand','id_producttype','id_ncc')
                    ->with([
                        'product_type'      => function($query){
                            return $query->select('uid','name');
                        },
                        'ncc'               => function($query){
                            return $query->select('uid','name');
                        }
                    ])
                    ->get()
                    ->map(function($key){
                        return [
                            'id'            => $key->id,
                            'description'   => $key->description,
                            'SKU'           => $key->SKU,
                            'brand'         => $key->brand,
                            'product_type'  => $key->product_type->name,
                            'ncc'           => $key->ncc->name
                        ];
                    });
        }else{
            $q = $request->query('query');
            //where('description' , 'like', "%{$q}%")
            $res = ProductM::whereHas('product_type', function ($query) use ($q){
                        $query->where('name', 'like', '%'.$q.'%');
                    })
                    ->orWhereHas('ncc', function ($query) use ($q){
                        $query->where('name', 'like', '%'.$q.'%');
                    })
                    ->select('id','description','SKU','brand','id_producttype','id_ncc')
                    ->with([
                        'product_type'      => function($query) use($q){
                            return $query->orWhere('name', 'like', "%{$q}%")
                                    ->select('uid','name');
                        },
                        'ncc'               => function($query) use($q){
                            return $query->orWhere('name', 'like', "%{$q}%")
                                    ->select('uid','name');
                        }
                    ])
                    ->get()
                    ->map(function($key){
                        return [
                            'id'            => $key->id,
                            'description'   => $key->description,
                            'SKU'           => $key->SKU,
                            'brand'         => $key->brand,
                            'product_type'  => $key->product_type->name,
                            'ncc'           => $key->ncc->name
                        ];
                    });
        }
           
        if(count($res)>0){
            return response([
                'ok'      => true,
                'data'    => $res
            ],200);
        }else{
            return response([
                'ok'      => false,
                'data'    => 'Data Tidak Ada'
            ],200);
        } 
    }
}
