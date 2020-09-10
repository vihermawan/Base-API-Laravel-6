<?php

namespace App\Http\Controllers;
use App\BiodataPenandatangan;
use Illuminate\Http\Request;

class BiodataPenandatanganController extends Controller
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
        $biodata = BiodataPenandatangan::get();
        // return response()->json(BiodataPenandatangan::all());
        if(sizeof($biodata) > 0)
            return response()->json([
                'status' => 'Success',
                'size' => sizeof($biodata),
                'data' => [
                    'biodata' => $biodata->toArray()
                ],
            ],200);
        else{
            return response(['Belum ada biodata'],404);
        }
    }

    public function show($id)
    {   
        $biodata = BiodataPenandatangan::findOrFail($id);
        return response()->json($biodata);
    }

    public function create(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'nama_instansi' => 'required',
            'instansi' => 'required',
            'jabatan' => 'required',
            'nik' => 'required'
        ]);
        if ($validator->fails()) {    
            return response()->json($validator->messages(), 422);
        }
        
        $biodata = BiodataPenandatangan::create($request->all());

        return response()->json($biodata, 201);
    }

    public function update($id, Request $request)
    {
        $biodata = BiodataPenandatangan::findOrFail($id);
        $biodata->update($request->all());

        return response()->json($biodata, 200);
    }
    
    public function delete($id)
    {
        BiodataPenandatangan::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }    
}
