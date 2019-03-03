<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\NccM;

class NccController extends Controller
{
    public function getAll(){
        $res =  NccM::select('id','name')->get();
        return response([
            'ok'      => true,
            'data'    => $res
        ],200);
    }

    public function postNcc(Request $request){
        
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string',
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
                $ncc = NccM::create([
                    'name'          => $request->input('name'),
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
                $ncc = NccM::where('uid', $request->input('id'))
                ->update([
                    'name'          => $request->input('name'),
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

    public function deleteNcc(Request $request){
        $id       = $request->input('id');
        try{
            DB::beginTransaction();

            $response = NccM::where('uid',$id)->delete();

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
            $res = NccM::where('uid' , $request->query('id'))->select('name')->get();
        }else{
            $res = NccM::where('name' , 'like', "%{$request->query('query')}%")->select('name')->get();
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
