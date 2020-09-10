<?php

namespace App\Http\Controllers;
use App\Sertifikat;
use Illuminate\Http\Request;
use File;
use Illuminate\Support\Facades\Validator;
class SertifikatController extends Controller
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
    //menampilkan seluruh sertifikat dalam database
    public function index(){
        $sertifikat = Sertifikat::get();
        if(sizeof($sertifikat) > 0){
            return response()->json([
                'status' => 'Success',
                'size' => sizeof($sertifikat),
                'data' => [
                    'sertifikat' => $sertifikat->toArray()
                ],
            ]);
        }else{
            return response(['Belum ada event'],404);
        }
    }

    //menampilkan sertifikat berdasarkan id
    public function show($id)
    {   
        $sertifikat = Sertifikat::findOrFail($id);

        return response()->json([
            'status' => 'Success',
            'data' => [
                'sertifikat' => $sertifikat->toArray()
            ],
        ]);
        // return response()->json($sertifikat);
    }

    //menghitung jumlah sertifikat dalam database
    public function countSertifikat()
    {   
        $sertifikat = Sertifikat::get();
        $sertifikats = $sertifikat ->count();
        // return response()->json($sertifikats);
        return response()->json([
            'status' => 'Success',
            'size' => sizeof($sertifikats),
            'data' => [
                'sertifikat' => $sertifikat->toArray()
            ],
        ]);
    }

    //mengupload sertifikat baru
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_sertifikat' => 'required',
            'sertifikat' => 'required'
        ]);
        if ($validator->fails()) {    
            return response()->json($validator->messages(), 422);
        }

        $sertifikat = Sertifikat::create($request->all());
        if($request->hasfile('sertifikat')){
            $file = $request->file('sertifikat');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('uploads/sertifikat/', $filename);
            $sertifikat->sertifikat = $filename;
        }
        $sertifikat->save();
        // return response()->json($sertifikat, 201);
        return response()->json([
            'status' => 'Success',
            'size' => sizeof($sertifikat),
            'data' => [
                'sertifikat' => $sertifikat->toArray()
            ],
        ],201);
    }

    //mengedit sertifikat
    public function update($id, Request $request)
    {
        $sertifikat = Sertifikat::findOrFail($id);
        $sertifikat->update($request->all());
        if($request->hasfile('sertifikat')){
            //Membuat nama file random dengan extension
            $filename = null;
            $uploaded_sertifikat = $request->sertifikat;
            $extension = $uploaded_sertifikat->getClientOriginalExtension();
            //Membuat nama file random dengan extension
            $filename = time() . '.' . $extension;
            $uploaded_sertifikat->move('uploads/sertifikat/', $filename);

            if($sertifikat->sertifikat){
                $old_file = $sertifikat->sertifikat;
                $filepath = public_path().DIRECTORY_SEPARATOR.'uploads/sertifikat/'.DIRECTORY_SEPARATOR.$old_file;
                try{
                    File::delete($filepath);
                }
                catch(FileNotFoundException $e){

                }
            }
            $sertifikat->sertifikat = $filename;
            $sertifikat->save();
        }

        // return response()->json($sertifikat, 200);
        return response()->json([
            'status' => 'Success',
            'size' => sizeof($sertifikat),
            'data' => [
                'sertifikat' => $sertifikat->toArray()
            ],
        ]);
    }
    
    //menghapus sertifikat
    public function delete($id)
    {
        $sertifikat = Sertifikat::findOrFail($id); 
            $old_foto = $sertifikat->sertifikat;
            $filepath = public_path().DIRECTORY_SEPARATOR.'uploads/sertifikat/'.DIRECTORY_SEPARATOR.$old_foto;
            try{
                 File::delete($filepath);
            }
            catch(FileNotFoundException $e){

            }
        
        Sertifikat::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }    

    //menghitung jumlah sertifikat dari suatu event
    public function countSertifikatbyEvent($id_event){
        $sertif = Sertifikat::with('event','event.sertifikat')->where('id_event','=',$id_event)->get();
        $totalsertif = $sertif->count();
        return response()->json(['Total sertif', $totalsertif]);
    }
}
