<?php

namespace App\Http\Controllers;
use App\User;
use App\UsersRole;
use App\Penandatangan;
use App\PenandatanganSertifikat;
use App\BiodataPenandatangan;
use App\ConfirmSigner;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Notifications\SendConfirmSigner;
use Illuminate\Support\Facades\Hash;

class PenandatanganController extends Controller
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
        $penandatangan = User::with(['penandatangan'])->whereHas('penandatangan', function ($query) {
            $query->where('id_role', '=', 4);
        })->get();;
        
        if(sizeof($penandatangan) > 0){
            // return response()->json(['Penandatangan', $penandatangan]);
            return response()->json([
                'status' => 'Success',
                'size' => sizeof($penandatangan),
                'data' => [
                    'sertifikat' => $penandatangan->toArray()
                ],
            ]);
        }   
        else{
            return response(['Belum ada penandatangan'],404);
        }
    }

    public function showProfile($id)
    {   
        $penandatangan = User::with('penandatangan')->whereHas('penandatangan', function ($query) use ($id){
            $query->where('id_penandatangan', '=', $id);
        })->get();
        if(sizeof($penandatangan) > 0){
            // return response()->json(['Penandatangan', $penandatangan]);
            return response()->json([
                'status' => 'Success',
                'size' => sizeof($penandatangan),
                'data' => [
                    'penandatangan' => $penandatangan->toArray()
                ],
            ]);
        }
        else{
            return abort(404);
        }
    }

    //Sertifikat yang belum ditandatangani 
    public function showWaitingSertifikat($id)
    {   
        $sertifikat = PenandatanganSertifikat::with('sertifikat')->where([
            ['id_penandatangan','=',$id], 
            ['id_status','=',3]
        ])->get();
        
        if(sizeof($sertifikat) > 0)
            return response()->json(['Sertifikat', $sertifikat]);
        else{
            return response(['Belum ada sertifikat yang perlu ditandatangani !'], 404);
        }
    }

    //Sertifikat yang udah ditandatangani 
    public function showSignedSertifikat($id)
    {   
        $sertifikat = PenandatanganSertifikat::with('sertifikat')->where([
            ['id_penandatangan','=',$id], 
            ['id_status','=',1]
        ])->get();
        if(sizeof($sertifikat) > 0)
            return response()->json(['Sertifikat', $sertifikat]);
        else{
            return response(['Belum ada sertifikat yang sudah ditandatangani !'], 404);
        }
    }

    //Sertifikat yang ditolak 
    public function showRejectedSertifikat($id)
    {   
        $sertifikat = PenandatanganSertifikat::with('sertifikat')->where([
            ['id_penandatangan','=',$id], 
            ['id_status','=',2]
        ])->get();
        if(sizeof($sertifikat) > 0)
            return response()->json(['Sertifikat', $sertifikat]);
        else{
            return response(['Belum ada sertifikat yang ditolak !'], 404);
        }
    }

    //Menampilkan total penandatangan dalam database
    public function countPenandatangan(){
        $penandatangan = Penandatangan::get();
        $penandatangans = $penandatangan->count();
        return response()->json(['Penandatangan', $penandatangans]);
    }

    public function create(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'email' => 'required',
        //     'password'=> 'required',
        //     'nama_penandatangan' => 'required',
        //     'instansi' => 'required',
        //     'jabatan' => 'required',
        //     'file_p12' => 'required',
        //     'nik' => 'required',
        // ]);
        // if ($validator->fails()) {    
        //     return response()->json($validator->messages(), 422);
        // }
        

        $id=$request->id_biodata_penandatangan;
        DB::beginTransaction();
        try{
            $u = DB::table('biodata_penandatangan')->where('id_biodata_penandatangan','=',$id)->get();
            foreach($u as $bp){
                $users = new User;
                $users->email = $bp->email;
            }
            $users->id_role=4;
            $users->password = Hash::make(str_random(8));
            $users->save();
            $user = $users->toArray();
        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['status' => 'Create Penandatangan Failed', 'message' => $e->getMessage()]);
        }

        try{
            $u = DB::table('biodata_penandatangan')->where('id_biodata_penandatangan','=',$id)->get();
        
            foreach($u as $bp){
                $penandatangan= new Penandatangan;
                $penandatangan->nama_penandatangan = $bp->nama;
                $penandatangan->instansi = $bp->instansi;
                $penandatangan->jabatan = $bp->jabatan;
                $penandatangan->nik = $bp->nik;
            }
    
            $penandatangan->id_users = $users->id_users;
            $penandatangan->file_p12 = $request->file_p12;
            $penandatangan->save();
    
    
            $signer = $penandatangan->toArray();
        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['status' => 'Create Penandatangan Failed', 'message' => $e->getMessage()]);
        }
        DB::commit();
   
        BiodataPenandatangan::findOrFail($id)->delete();
        /**
         * Create token set password
         *
         * @param  [string] email
         * @return [string] message
         */
        $details = [
            'greeting' =>'Hallo '.array_values($signer)[0],
            'body' => 'Anda terdaftar sebagai penandatangan di EventIn. Silahkan klik tombol dibawah untuk membuat password akun anda.',
            'actionText' => 'Set Password',
            'actionURL' => url('localhost:8000/api/login'),
        ];
        $confirmSigner = ConfirmSigner::updateOrCreate(
            ['email' => $users->email],
            [
                'email' => $users->email,
                'token' => str_random(60)
            ]
        );
        if ($users && $confirmSigner)
            $users->notify(
                new SendConfirmSigner($confirmSigner->token,$details)
            );
        return response()->json([
            'message' => 'We have e-mailed signer set password link!'
        ]);
      
       
     
    }

    public function update($id, Request $request)
    {
        $penandatangan = Penandatangan::findOrFail($id);
        $users = User::findOrFail($penandatangan->id_users);
        
        $penandatangan->update($request->all());
        if($request->hasfile('profile_picture')){
            //Membuat nama file random dengan extension
            $filename = null;
            $uploaded_profile_picture = $request->profile_picture;
            $extension = $uploaded_profile_picture->getClientOriginalExtension();
            //Membuat nama file random dengan extension
            $filename = time() . '.' . $extension;
            $uploaded_profile_picture->move('uploads/penandatangan/', $filename);

            if($penandatangan->profile_picture){
                $old_foto = $penandatangan->profile_picture;
                $filepath = public_path().DIRECTORY_SEPARATOR.'uploads/penandatangan/'.DIRECTORY_SEPARATOR.$old_foto;
                try{
                    File::delete($filepath);
                }
                catch(FileNotFoundException $e){

                }
            }
            $penandatangan->profile_picture = $filename;
            $penandatangan->save();
        }
        $users->update($request->all());
        return response()->json([$users,$penandatangan], 200);
    }
    
    public function delete($id)
    {
        $penandatangan = Penandatangan::findOrFail($id);
        $old_foto = $penandatangan->profile_picture;
            $filepath = public_path().DIRECTORY_SEPARATOR.'uploads/penandatangan/'.DIRECTORY_SEPARATOR.$old_foto;
            try{
                 File::delete($filepath);
            }
            catch(FileNotFoundException $e){

            }
        
        Penandatangan::findOrFail($id)->delete();
        User::findOrFail($penandatangan->id_users)->delete();
        return response('Deleted Successfully', 200);
    }    
}
