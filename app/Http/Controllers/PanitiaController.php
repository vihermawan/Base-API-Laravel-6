<?php


namespace App\Http\Controllers;
use App\User;
use App\Panitia;
use App\PesertaEvent;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
class PanitiaController extends Controller
{    
    //Menampilkan semua data panitia
    public function index(){
        $panitia = User::with(['panitia'])->whereHas('panitia', function ($query) {
            $query->where('id_role', '=', 2);
        })->get();
        
        if(sizeof($panitia) > 0)
            // return response()->json(['Panitia', $panitia]);
            return response()->json([
                'status' => 'Success',
                'size' => sizeof($panitia),
                'data' => [
                    'kategori' => $panitia->toArray()
                ],
            ],200);
        else{
            return response(['Belum ada panitia'],404);
        }
    }
    
    //Menampilkan data panitia A
    public function showProfil($id)
    {
        $panitia = User::with('panitia')->whereHas('panitia', function ($query) use ($id){
            $query->where('id_panitia', '=', $id);
        })->get();
            return response()->json([
                'status' => 'Success',
                'data' => [
                    'kategori' => $panitia->toArray()
                ],
            ],200);
    }

    //Menampilkan event dari panitia A
    public function showEvent($id_panitia){
        $event = Panitia::with('event','event.detail_event')->where('id_panitia','=',$id_panitia)->get();
        if(sizeof($event) > 0)
            // return response()->json(['Event', $event]);
            return response()->json([
                'status' => 'Success',
                'size' => sizeof($event),
                'data' => [
                    'kategori' => $event->toArray()
                ],
            ],200);
        else{
            return abort(404);
        }
    }

    
    //Menampilkan jumlah panitia dalam database
    public function countPanitia(){
        $panitia = Panitia::get();
        $panitias = $panitia->count();
        if(sizeof($event) > 0)
            return response()->json(['Jumlah Panitia', $panitias]);
        else{
            return abort(404);
        }
    }

    //menampilkan jumlah event dari panitia A
    public function countEventbyPanitia($id_panitia){
        $event = Panitia::with('event','event.detail_event')->where('id_panitia',$id_panitia)->get();
        $totalevent = $event->count();
        if(sizeof($event) > 0)
            return response()->json(['total event', $totalevent]);
        else{
            return abort(404);
        }
    }

    //menampilkan jumlah peserta dari semua event yang dibuat panitia A
    public function countPesertainAllEvent($id_panitia){
        $peserta = PesertaEvent::with(['event','peserta'])->whereHas('event',function($f) use($id_panitia) {
            $f->where('id_panitia','=',$id_panitia);
        })->get();
        $pesertas = $peserta->count();
        if(sizeof($peserta) > 0)
            return response()->json(['total peserta di semua event anda ', $pesertas]);
        else{
            return abort(404);
        }
    }
    

     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'password'=> 'required|min:8|confirmed',
            'nama_panitia' => 'required|string',
            'organisasi' => 'required|string',
            'foto_panitia' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);
        if ($validator->fails()) {    
            return response()->json($validator->messages(), 422);
        }
        $users = new User;
		$users->email = $request->email;
        $users->password = Hash::make($request->password);
        $users->id_role=2;
        $users->save();

        $panitia= new Panitia;
        $panitia->id_users = $users->id_users;
        $panitia->nama_panitia = $request->nama_panitia;
        $panitia->organisasi = $request->organisasi;
        if($request->hasfile('foto_panitia')){
            $file = $request->file('foto_panitia');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('uploads/panitia/', $filename);
            $panitia->foto_panitia = $filename;
        }
        $panitia->save();

        return response()->json([$users,$panitia], 201);
    }

       /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $panitia = Panitia::findOrFail($id);
        $users = User::findOrFail($panitia->id_users);
        
        $panitia->update($request->all());
        if($request->hasfile('foto_panitia')){
            //Membuat nama file random dengan extension
            $filename = null;
            $uploaded_foto_panitia = $request->foto_panitia;
            $extension = $uploaded_foto_panitia->getClientOriginalExtension();
            //Membuat nama file random dengan extension
            $filename = time() . '.' . $extension;
            $uploaded_foto_panitia->move('uploads/panitia/', $filename);

            if($panitia->foto_panitia){
                $old_foto = $panitia->foto_panitia;
                $filepath = public_path().DIRECTORY_SEPARATOR.'uploads/panitia/'.DIRECTORY_SEPARATOR.$old_foto;
                try{
                    File::delete($filepath);
                }
                catch(FileNotFoundException $e){

                }
            }
            $panitia->foto_panitia = $filename;
            $panitia->save();
        }

        $users->update($request->all());
        return response()->json([$users,$panitia], 200);
    }

}
