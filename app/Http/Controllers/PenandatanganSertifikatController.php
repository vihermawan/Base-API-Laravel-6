<?php

namespace App\Http\Controllers;
use App\PenandatanganSertifikat;
use Illuminate\Http\Request;

class PenandatanganSertifikatController extends Controller
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
        $penandatangan_sertifikat = PenandatanganSertifikat::with('penandatangan','sertifikat')->get();
        return response()->json([$penandatangan_sertifikat]);
        
    }
    public function show($id)
    {   
        $penandatangan_sertifikat = PenandatanganSertifikat::find($id);
        return response()->json($penandatangan_sertifikat);
    }

    public function create(Request $request)
    {
        $penandatangan_sertifikat = PenandatanganSertifikat::create($request->all());

        return response()->json($penandatangan_sertifikat, 201);
    }

    public function update($id, Request $request)
    {
        
    }
    
    public function delete($id)
    {
        PenandatanganSertifikat::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }    
}
