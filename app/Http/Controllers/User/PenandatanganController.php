<?php

namespace App\Http\Controllers\User;
use App\User;
use App\UsersRole;
use App\Penandatangan;
use App\PenandatanganSertifikat;
use App\BiodataPenandatangan;
use App\Sertifikat;
use App\ConfirmSigner;
use App\PesertaEvent;
use App\Event;
use File;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Notifications\SendConfirmSigner;
use Illuminate\Support\Facades\Hash;
use App\Libraries\Pdf_sign;
use Storage;

class PenandatanganController extends Controller
{
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function showProfile()
    {   
        $auth = Auth::user()->penandatangan;
        $id = $auth->id_penandatangan;
        $penandatangan = User::with(['penandatangan'])
                    ->whereHas('penandatangan', function ($query) use($id){
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

    public function showEditProfile()
    {
         $auth = Auth::user();
         $id = $auth->id_users;
         $penandatangan = User::with(['penandatangan'])->findOrFail($id);
         return response()->json([
             'status' => 'Success',
             'data' => [
                'penandatangan' => $penandatangan->toArray()
             ],
        ]);
    }

    //total sertifikat perevent
    public function showTotalWaitingSertifikat(){
        $auth = Auth::user()->penandatangan;
        $sertifikat = Event::with(['panitia','sertifikat.penandatanganan_sertifkat'=>function($f){
                        $f->select('id_sertifikat')->selectRaw('count(*) as total')->where('id_status','=',3)->groupBy('id_sertifikat');
                     }])
                     ->whereHas('sertifikat.penandatanganan_sertifkat', function($q) use($auth){
                        $q->where('nama_sertifikat', '!=', null)->where('id_penandatangan', '=', $auth->id_penandatangan)->where('id_status',3)->select('id_sertifikat'); 
                     })
                     ->get();

        if(sizeof($sertifikat) > 0){
            return response()->json([
                'status' => 'Success',
                'size' => sizeof($sertifikat),
                'data' => [
                    'sertifikat' => $sertifikat->toArray()
                ],
            ]);
        }else if(sizeof($sertifikat) == 0){
            return response()->json([
                'status' => 'Success',
                'size' => sizeof( $sertifikat),
                'data' => [
                    'sertifikat' =>  $sertifikat->toArray()
                ],
            ]);
        }
    }

    //Sertifikat yang belum ditandatangani
    public function showWaitingSertifikat($id_event)
    {   
        $auth = Auth::user()->penandatangan;
        $sertifikat = PenandatanganSertifikat::with('sertifikat.event.panitia')->where([
            ['id_penandatangan','=', $auth->id_penandatangan], 
            ['id_status','=',3] 
            ])->whereHas('sertifikat', function($q) use($id_event) {
                $q->where('id_event', '=', $id_event); 
            })->get();
        
        if(sizeof($sertifikat) > 0){
            return response()->json([
                'status' => 'Success',
                'size' => sizeof($sertifikat),
                'data' => [
                    'sertifikat' => $sertifikat->toArray()
                ],
            ],200);
        }else if(sizeof($sertifikat) == 0){
            return response()->json([
                'status' => 'Success',
                'size' => sizeof($sertifikat),
                'data' => [
                    'sertifikat' => $sertifikat->toArray()
                ],
            ],200);
        }else{
            return response(['Belum ada sertifikat'],404);
        }
    }

    public function countWaitingDashboard(){
        $auth = Auth::user()->penandatangan;
        $sertifikat = PenandatanganSertifikat::with('sertifikat.event.panitia')->where([
            ['id_penandatangan','=', $auth->id_penandatangan], 
            ['id_status','=',3] 
            ])->get();
        
        if(sizeof($sertifikat) > 0){
            return response()->json([
                'status' => 'Success',
                'size' => sizeof($sertifikat),
                'data' => [
                    'sertifikat' => $sertifikat->toArray()
                ],
            ],200);
        }else if(sizeof($sertifikat) == 0){
            return response()->json([
                'status' => 'Success',
                'size' => sizeof($sertifikat),
                'data' => [
                    'sertifikat' => $sertifikat->toArray()
                ],
            ],200);
        }else{
            return response(['Belum ada sertifikat'],404);
        }
    }

    //Sertifikat yang udah ditandatangani 
    public function showSignedSertifikat()
    {   
        $auth = Auth::user()->penandatangan;
        $sertifikat = PenandatanganSertifikat::with('sertifikat.event.panitia','status')->where([
            ['id_penandatangan','=', $auth->id_penandatangan], 
            ['id_status','=',1]
        ])
        // ->whereHas('sertifikat', function($q) {
        //     $q->where('id_status', '=', 1); 
        // })
        ->get();
        
        if(sizeof($sertifikat) > 0){
            return response()->json([
                'status' => 'Success',
                'size' => sizeof($sertifikat),
                'data' => [
                    'sertifikat' => $sertifikat->toArray()
                ],
            ],200);
        }else if(sizeof($sertifikat) == 0){
            return response()->json([
                'status' => 'Success',
                'size' => sizeof($sertifikat),
                'data' => [
                    'sertifikat' => $sertifikat->toArray()
                ],
            ],200);
        }else{
            return response(['Belum ada sertifikat'],404);
        }
    }

    //Sertifikat yang ditolak 
    public function showRejectedSertifikat($id)
    {   
        $sertifikat = PenandatanganSertifikat::with('sertifikat')->where([
            ['id_penandatangan','=',$id], 
            ['id_status','=',4]
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

    //edit profile penandatangan.
    public function updateProfile($id_penandatangan,Request $request)
    {
        $penandatangan = Penandatangan::findOrFail($id_penandatangan);
        $users = User::findOrFail($penandatangan->id_users);
        $nama = $penandatangan->profile_picture;

        $penandatangan->update($request->all());
        $penandatangan->nip = $request->nip;
        $penandatangan->save();
        if($request->hasFile('profile_picture'))
        {
            $usersImage = public_path('uploads/penandatangan/'.$nama); // get previous image from folder
            if (File::exists($usersImage)) { // unlink or remove previous image from folder
                unlink($usersImage);
            }
            $file = $request->file('profile_picture');
            $name = time() . '.' . $file->getClientOriginalExtension();
            $file = $file->move('uploads/penandatangan/', $name);
            $penandatangan->profile_picture = $name;
            $penandatangan->save();
        }
        $users->update($request->all());
        return response()->json([$users,$penandatangan], 200);
    }

    //menampilkan detail sertifikat
    public function showSertifikat($id)
    {   
        $sertifikat = Sertifikat::findOrFail($id);

        return response()->json([
            'status' => 'Success',
            'data' => [
                'sertifikat' => $sertifikat->toArray()
            ],
        ]);
    }

    //melakukan tandatangan
    public function assignSertifikat($id_penandatangan_sertifikat, Request $request){
        $auth = Auth::user()->penandatangan;
        $id = $auth->id_penandatangan;
        $passphrase = $request->passphrase;

        $penandatangansertifikat = PenandatanganSertifikat::with('penandatangan')->where('id_penandatangan_sertifikat',$id_penandatangan_sertifikat)->first();
        $sertif = PenandatanganSertifikat::with('sertifikat.event')->where('id_penandatangan_sertifikat','=',$id_penandatangan_sertifikat)->get()->toArray();
        
        $penandatangan = $penandatangansertifikat->toArray();
            
        $penandatangansertifikat->update($request->all());
        $penandatangansertifikat->id_status = 1;
        $penandatangansertifikat->save();
        $nama_event = $sertif[0]['sertifikat']['event']['nama_event'];
        $nama_sertif = $sertif[0]['nama_sertifikat'];
       
        $Pdf_sign =  new Pdf_sign();
        $path_p12 =  storage_path('sign/'.$penandatangan['penandatangan']['id_penandatangan'].'/'.$penandatangan['penandatangan']['file_p12']);
        $output_path = public_path('uploads/sertifikat_sign');
        $path_pdf = public_path('/uploads/sertifikat/'.$nama_event.'/'.$nama_sertif);
        
        $Pdf_sign->setDocument($path_pdf);
    
        $Pdf_sign->setDirectory($output_path,false);
    
        $Pdf_sign->setSuffixFileName('');
    
        $Pdf_sign->setTimeStamp('http://tsa-bsre.bssn.go.id','coba','1234');
    
        $Pdf_sign->setOCSP('http://cvs-bsre.bssn.go.id/ocsp');
    
        $Pdf_sign->readCertificateFromFile(
            $path_p12,
            $passphrase            
        );
    
        $Pdf_sign->setCertificationLevel(1);
    
        $Pdf_sign->setLocation('Gamatechno');
    
        $result = true;
        if(!$Pdf_sign->sign()) {
             $result = $result && false;
            $msg = $Pdf_sign->getError();
                 response()->json([
                'status' => 'Failed',
                'message' => $msg
            ]);
        } else {
            return response()->json([
                'status' => 'Success',
                'data' => [
                'sertifikat' => $penandatangansertifikat->toArray()
                ],
            ]);
        }
    }
        
     //menampilkan detail view untuk sertifikat
    public function showFileSertifikat($id){
        $sertifikat = Sertifikat::findOrFail($id);
        $file_sertifikat = $sertifikat->sertifikat;
        $file= public_path(). "/uploads/sertifikat/".$file_sertifikat;
        $nama_sertifikat = $sertifikat->nama_sertifikat;
        return response()->download($file,$nama_sertifikat.'.rtf');
    }

    //ubah password
    public function changePassword(Request $request){
        $request_data = $request->all();
        $current_password = Auth::user()->password;
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password' => 'required|confirmed|min:8|different:old_password'
        ]);
        if ($validator->fails()) {    
            return response()->json($validator->messages(), 422);
        }
        $old_password = $request->old_password;
        
        if(Hash::check($old_password, $current_password))
        {           
            $user_id = Auth::user()->id_users;                       
            $obj_user = User::find($user_id);
            $obj_user->password = Hash::make($request->password);
            $obj_user->save(); 
            return response()->json([
                'status' => 'Success',
                'message' => 'Password Berhasil diubah'
           ]);
        }else {
            return response()->json([
                'status' => 'Error',
                'message' => 'Password lama anda tidak sesuai'
            ]);

        }
    }
}