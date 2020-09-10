<?php


namespace App\Http\Controllers;
use App\User;
use App\UsersRole;
use App\Peserta;
use App\PesertaEvent;
use File;
use App\Notifications\TemplateEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PesertaController extends Controller
{    
    public function __construct()
    {
       
    }

 
    //show data peserta besera emailnya
    public function index(){
        $peserta = User::with(['peserta'])->whereHas('peserta', function ($query) {
            $query->where('id_role', '=', 3);
        })->get();

        if(sizeof($peserta) > 0){
            // return response()->json(['Peserta', $peserta]);
            return response()->json([
                'status' => 'Success',
                'size' => sizeof($peserta),
                'data' => [
                    'User' => $peserta->toArray()
                ],
            ]);

        }else{
            return response(['Belum ada peserta'],404);
        }
        
    }

    //Menampilkan semua event yang diikuti peserta A
    public function showEvent($id){
        $event = PesertaEvent::with('event','event.detail_event')->where('id_peserta','=',$id)->get();
        
        if(sizeof($event) > 0){
            // return response()->json(['Event', $event]);
            return response()->json([
                'status' => 'Success',
                'size' => sizeof($event),
                'data' => [
                    'Event' => $event->toArray()
                ],
            ]);
        }else{
            return response(['Peserta belum mendaftar event apapun!'], 404);
        }
    }

    

    //Menampilkan data profil peserta A
    public function showProfil($id)
    {
        // $peserta = UsersRole::with(['peserta'=>function($f){
        //     $f->where('id_peserta','=', $id);
        // }
        // ])->where('id_role','=',3)->get();
        // $peserta->load('users');
        // return response()->json(['Peserta', $peserta]); 

        $peserta = User::with('peserta')->whereHas('peserta', function ($query) use ($id){
            $query->where('id_peserta', '=', $id);
        })->get();

        if(sizeof($peserta) > 0){
            // return response()->json(['Peserta', $peserta]);
            return response()->json([
                'status' => 'Success',
                'size' => sizeof($peserta),
                'data' => [
                    'User' => $peserta->toArray()
                ],
            ]);
        }else{
            return abort(404);
        }
     
    }

    //Menampilkan jumlah peserta dalam database
    public function countPeserta(){
        $peserta = Peserta::get();
        $pesertas = $peserta->count();
        return response()->json(['Jumlah Peserta dalam sistem : ', $pesertas]);
    }
    
    //Menampilkan jumlah event yang diikuti peserta A
    public function countEventbyPeserta($id_peserta){
        $event = PesertaEvent::with('event','event.detail_event')->where('id_peserta','=',$id_peserta)->get();
        $totalevent = $event->count();
        if(sizeof($event) > 0){
            return response()->json(['total event', $totalevent]);
        }else{
            return abort(404);
        }
    }

     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    //Membuat peserta Baru
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password'=> 'required',
            'nama_peserta' => 'required',
            'tanggal_lahir' => 'required',
            'umur' => 'required',
            'organisasi' => 'required',
            'foto_peserta' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);
        if ($validator->fails()) {    
            return response()->json($validator->messages(), 422);
        }
        
        $users = new User;
		$users->email = $request->email;
        $users->password = $request->password;
        $users->id_role=3;
        $users->save();


        $peserta = new Peserta;
        $peserta->id_users = $users->id_users;
        $peserta->nama_peserta = $request->nama_peserta;
        $peserta->tanggal_lahir = $request->tanggal_lahir;
        $peserta->umur = $request->umur;
        $peserta->organisasi = $request->organisasi;
        if($request->hasfile('foto_peserta')){
            $file = $request->file('foto_peserta');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('uploads/peserta/', $filename);
            $peserta->foto_peserta = $filename;
        }
        $peserta->save();

        return response()->json([$users,$peserta], 201);
    }

       /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    //mengedit data profil peserta
    public function updateProfil($id, Request $request)
    {
        $peserta = Peserta::findOrFail($id);
        $users = User::findOrFail($peserta->id_users);
        
        $peserta->update($request->all());
        if ($request->hasFile('foto_peserta')) {
            $dir = public_path().DIRECTORY_SEPARATOR.'uploads/peserta/'.DIRECTORY_SEPARATOR;
            if ($peserta->foto_peserta != '' && File::exists($dir . $peserta->foto_peserta))
            File::delete($dir . $peserta->foto_peserta);
            $extension = strtolower($request->file('foto_peserta')->getClientOriginalExtension());
            $fileName = str_random() . '.' . $extension;
            $request->file('foto_peserta')->move($dir, $fileName);
            $peserta->foto_peserta = $fileName;
            
        } elseif ($request->remove == 1 && File::exists('uploads/peserta/' . $peserta->foto_peserta)) {
            File::delete('uploads/peserta/' . $peserta->post_image);
            $peserta->foto_peserta = null;
        }
        $peserta->save();
        $users->update($request->all());
        return response()->json([$users,$peserta], 200);
    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //menghapus data peserta
    public function delete($id)
    {
        $peserta = Peserta::findOrFail($id);
        
        $old_foto = $peserta->foto_peserta;
            $filepath = public_path().DIRECTORY_SEPARATOR.'uploads/peserta/'.DIRECTORY_SEPARATOR.$old_foto;
            try{
                 File::delete($filepath);
            }
            catch(FileNotFoundException $e){

            }
        Peserta::findOrFail($id)->delete();
        User::findOrFail($peserta->id_users)->delete();
        return response('Deleted Successfully', 200);
    }
    
}
