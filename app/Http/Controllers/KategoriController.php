<?php

namespace App\Http\Controllers;
use App\Kategori;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
class KategoriController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    public function index(){
        $kategori = Kategori::get();
        // return response()->json(Kategori::all());
        if(sizeof($kategori) > 0)
            return response()->json([
                'status' => 'Success',
                'size' => sizeof($kategori),
                'data' => [
                    'kategori' => $kategori->toArray()
                ],
            ],200);
        else{
            return response(['Belum ada kategori'],404);
        }
    }

    public function show($id)
    {   
        $kategori = Kategori::findOrFail($id);
        // return response()->json('Kategori',$kategori);
            return response()->json([
                'status' => 'Success',
                'data' => [
                    'kategori' => $kategori->toArray()
                ],
            ],200);       
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'required'
        ]);
        if ($validator->fails()) {    
            return response()->json($validator->messages(), 422);
        }
        $kategori= Kategori::create($request->all());

        return response()->json($kategori, 201);
    }

    public function update($id, Request $request)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->update($request->all());

        return response()->json($kategori, 200);
    }
    
    public function delete($id)
    {
        Kategori::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }    
}
