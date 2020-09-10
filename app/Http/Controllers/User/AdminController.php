<?php

namespace App\Http\Controllers\User;
use App\User;
use App\Panitia;
use App\Peserta;
use App\Penandatangan;
use App\Kategori;
use App\Status;
use App\Event;
use App\Sertifikat;
use App\PesertaEvent;
use App\DetailEvent;
use File;
use Auth;
use App\BiodataPenandatangan; 
use App\PenandatanganSertifikat; 
use Illuminate\Support\Carbon; 
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Notifications\Notifiable;
use App\Notifications\PesertaRegisteredEvent;
use App\Notifications\ConfirmRequestSigner;
use App\Notifications\SendNotification;
use Illuminate\Support\Facades\Hash;
use App\SetPassword;
use App\Notifications\SendConfirmSigner;
use WordTemplate;
use Storage;
use PhpOffice\PhpWord\TemplateProcessor;
use \ConvertApi\ConvertApi;
use NcJoes\OfficeConverter\OfficeConverter;
use Illuminate\Support\HtmlString;
use Str;

class AdminController extends Controller
{
    //menampilkan semua peserta dalam database
    public function showPeserta()
    {
        $peserta = User::with('peserta')->where([
            ['id_role', '=', 3],
            ['isBan', '=', 0],
        ])->orderBy('created_at', 'DESC')->get();
        if(sizeof($peserta) > 0){
               return response()->json([
                  'status' => 'Success',
                  'size' => sizeof($peserta),
                  'data' => [
                     'user' => $peserta->toArray()
                  ],
             ]);
        }else if(sizeof($peserta) == 0){
            return response()->json([
                'status' => 'Success',
                'size' => sizeof($peserta),
                'data' => [
                    'user' => $peserta->toArray()
                ],
            ]);
        }
    }

    //menampilkan data peserta yang di ban
    public function trashPeserta()
    {
        $peserta = User::with('peserta')->where([
            ['id_role', '=', 3],
            ['isBan', '=', 1],
        ])->orderBy('updated_at', 'DESC')->get();
        if(sizeof($peserta) > 0){
               return response()->json([
                  'status' => 'Success',
                  'size' => sizeof($peserta),
                  'data' => [
                     'user' => $peserta->toArray()
                  ],
             ]);
        }else if(sizeof($peserta) == 0){
            return response()->json([
                'status' => 'Success',
                'size' => sizeof($peserta),
                'data' => [
                    'user' => $peserta->toArray()
                ],
            ]);
        }
    }

    //menampilkan data panitia yang di ban
    public function trashPanitia()
    {
        $panitia = User::with('panitia')->where([
            ['id_role', '=', 2],
            ['isBan', '=', 1],
        ])->orderBy('updated_at', 'DESC')->get();
        if(sizeof($panitia) > 0){
               return response()->json([
                  'status' => 'Success',
                  'size' => sizeof($panitia),
                  'data' => [
                     'user' => $panitia->toArray()
                  ],
             ]);
        }else if(sizeof($panitia) == 0){
            return response()->json([
                'status' => 'Success',
                'size' => sizeof($panitia),
                'data' => [
                    'user' => $panitia->toArray()
                ],
            ]);
        }
    }

    //menampilkan data penandatangan yang di ban
    public function trashPenandatangan()
    {
        $penandatangan = User::with('penandatangan')->where([
            ['id_role', '=', 4],
            ['isBan', '=', 1],
        ])->orderBy('updated_at', 'DESC')->get();
        if(sizeof($penandatangan) > 0){
               return response()->json([
                  'status' => 'Success',
                  'size' => sizeof($penandatangan),
                  'data' => [
                     'user' => $penandatangan->toArray()
                  ],
             ]);
        }else if(sizeof($penandatangan) == 0){
            return response()->json([
                'status' => 'Success',
                'size' => sizeof($penandatangan),
                'data' => [
                    'user' => $penandatangan->toArray()
                ],
            ]);
        }
    }


    //menampilkan detail peserta
    public function detailPeserta($id){
        $peserta = User::with('peserta')->findOrFail($id);
        return response()->json([
            'status' => 'Success',
            'data' => [
                'peserta' => $peserta->toArray()
            ],
        ]);
    }

    //Menampilkan semua panitia dalam database
    public function showPanitia()
    {
        $panitia = User::with(['panitia'])->where([
            ['id_role', '=', 2],
            ['isBan', '=', 0],
        ])->orderBy('created_at', 'DESC')->get();
        if(sizeof($panitia) > 0){
            return response()->json([
                'status' => 'Success',
                'size' => sizeof($panitia),
                'data' => [
                    'panitia' => $panitia->toArray()
                ],
            ]);
        }else if(sizeof($panitia) == 0){
            return response()->json([
                'status' => 'Success',
                'size' => sizeof($panitia),
                'data' => [
                    'panitia' => $panitia->toArray()
                ],
            ]);
        }
    }

    //menampilkan event berdasarkan panitia
    public function showEventbyPanitia($id_panitia){
        $event = Event::with(['detail_event','kategori','status_biaya','status_event'])
                ->where( 'id_panitia', '=', $id_panitia)
                ->get();
            if(sizeof($event) > 0){
                return response()->json([
                    'status' => 'Success',
                    'size' => sizeof($event),
                    'data' => [
                        'event' => $event->toArray()
                    ],
                ]);
            }else if(sizeof($event) == 0){
                return response()->json([
                    'status' => 'Success',
                    'size' => sizeof($event),
                    'data' => [
                        'event' => $event->toArray()
                    ],
                ]);
            }
    }

    //menampilkan event berdasarkan peserta
    public function showEventbyPeserta($id_peserta){
        $peserta_event = PesertaEvent::with('event.detail_event','event.kategori','status')
                        ->where( 'id_peserta', '=', $id_peserta)->orderBy('created_at', 'DESC')
                        ->get();
        if(sizeof($peserta_event) > 0){
            return response()->json([
                'status' => 'Success',
                'size' => sizeof( $peserta_event),
                'data' => [
                    'peserta' =>  $peserta_event->toArray()
                ],
            ]);
        }else if(sizeof($peserta_event) == 0){
            return response()->json([
                'status' => 'Success',
                'size' => sizeof( $peserta_event),
                'data' => [
                    'peserta' =>  $peserta_event->toArray()
                ],
            ]);
        }
    }

    //menampilkan event yang sudah di approve
    public function listEvent(){
        $event = Event::with(['detail_event','kategori','status_biaya','panitia'])
                ->where( 'id_status_event', '=', 6)
                ->orderBy('created_at', 'DESC')
                ->get();
        if(sizeof($event) > 0){
            return response()->json([
                'status' => 'Success',
                'size' => sizeof($event),
                'data' => [
                    'event' => $event->toArray()
                ],
            ]);
        }else if(sizeof($event) == 0){
            return response()->json([
                'status' => 'Success',
                'size' => sizeof($event),
                'data' => [
                    'event' => $event->toArray()
                ],
            ]);
        }
    }

    //menampilkan event yang belum di approve
    public function listApproveEvent(){
        $event = Event::with(['detail_event','kategori','status_biaya','panitia'])
                ->where( 'id_status_event', '=', 5)
                ->orderBy('created_at', 'ASC')
                ->get();
        if(sizeof($event) > 0){
            return response()->json([
                'status' => 'Success',
                'size' => sizeof($event),
                'data' => [
                    'event' => $event->toArray()
                ],
            ]);
        }else if(sizeof($event) == 0){
            return response()->json([
                'status' => 'Success',
                'size' => sizeof($event),
                'data' => [
                      'event' => $event->toArray()
                ],
            ]);
        }
    }

    //menampilkan detail event
    public function detailEvent($id){
        $event = Event::with(['kategori','detail_event.provinsi','detail_event.kabupaten','status_biaya'])->findOrFail($id);         
        return response()->json([
            'status' => 'Success',
            'data' => [
                'event' => $event->toArray()
            ],
        ]);
    }

    //acc event.
    public function accEvent($id_event,Request $request){
        $event = Event::findOrFail($id_event);
        $panitia = Panitia::find($event->id_panitia);
        $users = User::find($panitia->id_users);
        $panitias=$panitia->toArray();
        $user=$users->toArray();
        $events=$event->toArray();
        $event->update($request->all());
        $event->id_status_event = 6;
        $event->save();
        $details = [
            'subject' => 'Event diterima',
            'greeting' =>'Hallo '.array_values($panitias)[2],
            'body' => 'Event '.array_values($events)[6].' telah diterima',
            'thank' => 'Sekarang anda sudah bisa mulai menerima peserta pada event tersebut.',
        ];
        $users->notify(
            new SendNotification($details)
        );
        return response()->json('Event berhasil diterima');
    }

    //reject event
    public function rejectEvent($id_event,Request $request){
        $event = Event::findOrFail($id_event);
        $panitia = Panitia::find($event->id_panitia);
        $users = User::find($panitia->id_users);
        $panitias=$panitia->toArray();
        $user=$users->toArray();
        $events=$event->toArray();
        $details = [
            'subject' => 'Event ditolak',
            'greeting' =>'Hallo '.array_values($panitias)[2],
            'body' => 'Event '.array_values($events)[6].' ditolak karena tidak sesuai dengan ketentuan yang berlaku',
            'thank' => 'Silahkan daftarkan kembali event dengan benar',
        ];
        $users->notify(
            new SendNotification($details)
        );
        Event::findOrFail($id_event)->delete();
        DetailEvent::where('id_detail_event',$id_event)->first()->delete();
        return response()->json('Event berhasil ditolak');
    }
    
    //menampilkan detail panitia
    public function detailPanitia($id){
        $panitia = User::with('panitia')->findOrFail($id);
        return response()->json([
            'status' => 'Success',
            'data' => [
                'panitia' => $panitia->toArray()
            ],
        ]);
    }

    //menampilkan semua penandatangan dalam database
    public function showPenandatangan()
    {
        $penandatangan = User::with('penandatangan','penandatangan.kabupaten','penandatangan.provinsi')->where([
            ['id_role', '=', 4],
            ['isBan', '=', 0],
        ])->orderBy('created_at', 'DESC')->get();   
        if(sizeof($penandatangan) > 0){
            return response()->json([
                'status' => 'Success',
                'size' => sizeof($penandatangan),
                'data' => [
                    'penandatangan' => $penandatangan->toArray()
                ],
            ]);
        }else if(sizeof($penandatangan) == 0){
            return response()->json([
                'status' => 'Success',
                'size' => sizeof($penandatangan),
                'data' => [
                    'penandatangan' => $penandatangan->toArray()
                ],
            ]);
        }
    }   

    //menampilkan detail penandatangan
    public function detailPenandatangan($id_users){
        $penandatangan = User::with('penandatangan.kabupaten','penandatangan.provinsi')->findOrFail($id_users);
        return response()->json([
            'status' => 'Success',
            'data' => [
                'penandatangan' => $penandatangan->toArray()
           ],
        ]);
    }

    //menampilakn sertifikat yang diajukan ke penandatangan
    public function showDetailSertifikatPenandatangan($id_penandatangan){
        $sertifikat = PenandatanganSertifikat::with('penandatangan','sertifikat.event.panitia','status')
                      ->where('id_status',3)
                      ->where('id_penandatangan','=',$id_penandatangan)
                      ->orderBy('created_at','DESC')->get();
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
                'size' => sizeof($sertifikat),
                'data' => [
                    'sertifikat' => $sertifikat->toArray()
                ],
            ]);
        }
    }

    //menampilkan data penandatangan untuk diedit
    public function showEditPenandatangan($id_users){
        $penandatangan = User::with('penandatangan.kabupaten','penandatangan.provinsi')->findOrFail($id_users);
        return response()->json([
            'status' => 'Success',
            'data' => [
                'penandatangan' => $penandatangan->toArray()
            ],
        ],200);
    }

    //menampilkan semua biodata penandatangan yang ada dalam database
    public function showBiodataPenandatangan(){
        $biodata = BiodataPenandatangan::get();
        if(sizeof($biodata) > 0){
            return response()->json([
                'status' => 'Success',
                'size' => sizeof($biodata),
                'data' => [
                    'biodata' => $biodata->toArray()
                ],
            ]);
        }
        else if(sizeof($biodata) == 0){
            return response()->json([
                'status' => 'Success',
                'size' => sizeof($biodata),
                'data' => [
                    'biodata' => $biodata->toArray()
                ],
            ]);
        }
    }
 
    //menampilkan sertifikat dalam database
    public function showSertifikat()
    {
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
    
    //menampilkan sertifikat yang masih menunggu
    public function showWaitingSertifikat()
    {   
        $sertifikat = PenandatanganSertifikat::with('penandatangan','sertifikat.event.panitia','sertifikat.event.detail_event')
            ->where('id_status','=',8)
            ->orderBy('created_at', 'ASC')->get();
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
                'size' => sizeof($sertifikat),
                'data' => [
                    'sertifikat' => $sertifikat->toArray()
                ],
            ]);
        }
    }

    //menampilkan sertifikat yang sudah diterima
    public function showSignedSertifikat()
    {   
        $sertifikat = PenandatanganSertifikat::with('sertifikat.event.panitia','status','penandatangan')->where([
            ['id_status','=',1]
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

    //mengedit data profil peserta
    public function editPeserta($id_peserta, Request $request)
    {
        $peserta = Peserta::findOrFail($id_peserta);
        $nama = $peserta->foto_peserta;

        $users = User::findOrFail($peserta->id_users);
        $validator = Validator::make($request->all(), [
            
            'nama_peserta' => 'string',
            // 'tanggal_lahir' => 'date',
            'jenis_kelamin' => 'string',
            'organisasi' => 'string',
            // 'umur' => 'integer',
        ]);
        if ($validator->fails()) {    
            return response()->json($validator->messages(), 422);
        }
        $peserta->update($request->all());
        if($request->hasFile('foto_peserta'))
        {
            $usersImage = public_path('uploads/peserta/'.$nama); // get previous image from folder
            if (File::exists($usersImage)) { // unlink or remove previous image from folder
                unlink($usersImage);
            }
            $file = $request->file('foto_peserta');
            $name = time() . '.' . $file->getClientOriginalExtension();
            $file = $file->move('uploads/peserta/', $name);
            $peserta->foto_peserta = $name;
            $peserta->save();
        }

        $users->update($request->all());
        return response()->json([$users,$peserta], 200);
    }

    //mengedit data panitia
    public function editPanitia($id_panitia, Request $request)
    {
        $panitia = Panitia::findOrFail($id_panitia);
        $nama = $panitia->foto_panitia;

        $users = User::findOrFail($panitia->id_users);
        $validator = Validator::make($request->all(), [
            'nama_panitia' => 'string',
            'organisasi' => 'string',
            'instagram' => 'string',
            'no_telepon' => 'string',
        ]);
        if ($validator->fails()) {    
            return response()->json($validator->messages(), 422);
        }
        $panitia->update($request->all());

        if($request->hasFile('foto_panitia'))
        {
            $usersImage = public_path('uploads/panitia/'.$nama); // get previous image from folder
            if (File::exists($usersImage)) { // unlink or remove previous image from folder
                unlink($usersImage);
            }
            $file = $request->file('foto_panitia');
            $name = time() . '.' . $file->getClientOriginalExtension();
            $file = $file->move('uploads/panitia/', $name);
            $panitia->foto_panitia = $name;
            $panitia->save();
        }

        $users->update($request->all());
        return response()->json([$users,$panitia], 200);
    }

    //mengedit data penandatangan
    public function editPenandatangan($id_penandatangan, Request $request)
    {
        $penandatangan = Penandatangan::findOrFail($id_penandatangan);
        $p12 = $penandatangan->file_p12;
        $penandatangan->update($request->all());
        if($request->file_p12 == 'null'){
            $penandatangan->file_p12 = $penandatangan->file_p12;
        }
        else if($request->hasFile('file_p12'))
        {
            $fileP12 = storage_path('sign/'.$id_penandatangan.'/'.$p12);
            if (File::exists($fileP12)) { 
                unlink($fileP12);
            }
            $file = $request->file('file_p12');
            $name = $penandatangan->nama_penandatangan .'-'. $penandatangan->nip . '.' . $file->getClientOriginalExtension();
            $file = $file->move(storage_path('sign/'.$id_penandatangan), $name);
            $penandatangan->file_p12 = $name;
            $penandatangan->save();
        }
        return response()->json([$penandatangan], 200);
    }

    public function accPenandatangan(Request $request)
    {
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
                $penandatangan->nip = $bp->nip;
                $penandatangan->profile_picture = $bp->profile_picture;
                $penandatangan->id_kabupaten = $bp->id_kabupaten;
                $penandatangan->id_provinsi = $bp->id_provinsi;
                $penandatangan->telepon = $bp->telepon;
            }
            $penandatangan->id_users = $users->id_users;
            $penandatangan->file_p12 = $request->file_p12;
            $penandatangan->save();
            $signer = $penandatangan->toArray();
        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['status' => 'Create Penandatangan Failed', 'message' => $e->getMessage()]);
        }try{
            $details = [
                'subject' => 'Konfirmasi Akun Penandatangan',
                'greeting' =>'Hallo '.array_values($signer)[0],
                'body' => 'Email anda telah terdaftar sebagai penandatangan di EventIn. Silahkan klik tombol "Atur Kata Sandi" agar akun anda bisa segera digunakan',
                'actionText' => 'Atur Kata Sandi',
                'closing' => new HtmlString('<pre style="color:blue;font-size:12px;font-weight:bold;">*Tombol diatas hanya berlaku selama 30 menit, setelah itu anda bisa klik <a href="http://localhost:3000/forgot-password" style="color:red;">Atur ulang Kata Sandi</a> untuk mengatur kata sandi anda </pre>'),
            ];
            $confirmSigner = SetPassword::updateOrCreate(
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
        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['status' => 'Pengiriman Email ke penandatangan gagal', 'message' => $e->getMessage()]);
        }try{
            $biodata = BiodataPenandatangan::findOrFail($id);
            $panitia = Panitia::find($biodata->id_panitia);
            $users = User::find($panitia->id_users);
            $biodatas=$biodata->toArray();
            $user=$users->toArray();
            $panitias=$panitia->toArray();
            $information = [
                'subject' => 'Akun Penandatangan Sukses Terdaftar',
                'greeting' =>'Hallo '.array_values($panitias)[2],
                'body' => 'Selamat data penandatangan telah diterima',
                'thank' => 'Sekarang anda sudah bisa upload sertifikat dengan memilih penandatangan yang anda daftarkan.',
            ];
            $users->notify(
                new ConfirmRequestSigner($information)
            );    
        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['status' => 'Pengiriman Email ke panitia gagal', 'message' => $e->getMessage()]);
        }
        DB::commit();
        BiodataPenandatangan::findOrFail($id)->delete();
        return response()->json([
            'status' => 'Success',
            'message' => 'Berhasil menerima penandatangan'
        ]); 
    }

    //coba get email panitia di penandatangan
    public function rejectPenandatangan(Request $request){
        $id=$request->id_biodata_penandatangan;
        $biodata = BiodataPenandatangan::findOrFail($id);
        $panitia = Panitia::find($biodata->id_panitia);
        $users = User::find($panitia->id_users);
        $biodatas=$biodata->toArray();
        $user= $users->toArray();
        $panitias=$panitia->toArray();
        $information = [
            'subject' => 'Permintaan Akun Penandatangan ditolak',
            'greeting' =>'Hallo '.array_values($panitias)[2] ,
            'body' => 'Mohon maaf data penandatangan ditolak karena data tidak valid',
            'thank' => 'Silahkan upload ulang data baru penandatangan.',
        ];
        $users->notify(
            new ConfirmRequestSigner($information)
        );    
        BiodataPenandatangan::findOrFail($id)->delete();
        return response()->json([
            'status' => 'Success',
            'message' => 'Berhasil menolak penandatangan'
        ]); 
    }

    //menghapus data peserta
    public function banPeserta($id_peserta, Request $request)
    {
        $peserta = Peserta::find($id_peserta);
        $peserta = User::find($peserta->id_users);
        $peserta->update($request->all());
        $peserta->isBan = 1;
        $peserta->save();
        return response()->json([
            'message' => 'Peserta berhasil diblokir'
        ], 200);
    }

    //unban peserta
    public function unbanPeserta($id_peserta, Request $request)
    {
        $peserta = Peserta::find($id_peserta);
        $peserta = User::find($peserta->id_users);
        $peserta->update($request->all());
        $peserta->isBan = 0;
        $peserta->save();
        return response()->json([
            'message' => 'Akun peserta sudah diaktifkan kembali'
        ], 200);
    }

    //unban panitia
    public function unbanPanitia($id_panitia, Request $request)
    {
        $panitia = Panitia::find($id_panitia);
        $panitia = User::find($panitia->id_users);
        $panitia->update($request->all());
        $panitia->isBan = 0;
        $panitia->save();
        return response()->json([
            'message' => 'Akun panitia sudah diaktifkan kembali'
        ]);
    }

    //unban penandatangan
    public function unbanPenandatangan($id_penandatangan, Request $request)
    {
        $penandatangan = Penandatangan::find($id_penandatangan);
        $penandatangan = User::find($penandatangan->id_users);
        $penandatangan->update($request->all());
        $penandatangan->isBan = 0;
        $penandatangan->save();    
        return response()->json([
            'message' => 'Akun penandatangan sudah diaktifkan kembali'
        ]);
    }

    //menghapus data penandatangan
    public function banPenandatangan($id_penandatangan, Request $request)
    {
        $penandatangan = Penandatangan::find($id_penandatangan);
        $penandatangan = User::find($penandatangan->id_users);
        $penandatangan->update($request->all());
        $penandatangan->isBan = 1;
        $penandatangan->save();
        return response()->json([
            'message' => 'Penandatangan berhasil diblokir'
        ]);
    }


    //menghapus data panitia
    public function banPanitia($id_panitia, Request $request)
    {
        $panitia = Panitia::find($id_panitia);
        $panitia = User::find($panitia->id_users);
        $panitia->update($request->all());
        $panitia->isBan = 1;
        $panitia->save();
        return response()->json([
            'message' => 'Panitia berhasil diblokir'
        ]);
    }

    //kategori
    public function indexKategori(){
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

    //detail kategori
    public function showKategori($id)
    {   
        $kategori = Kategori::findOrFail($id);
            return response()->json([
                'status' => 'Success',
                'data' => [
                    'kategori' => $kategori->toArray()
                ],
            ]);       
    }

    //create kategori
    public function createKategori(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'required'
        ]);
        if ($validator->fails()) {    
            return response()->json($validator->messages(), 422);
        }
        $kategori= new Kategori;
        $kategori->nama_kategori = $request->nama_kategori;
        $kategori->save();
        return response()->json([
            'status' => 'Success',
            'data' => [
                'kategori' => $kategori->toArray()
            ],
        ],201);
    }

    //update kategori
    public function updateKategori($id, Request $request)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->nama_kategori = $request->nama_kategori;
        $kategori->save();
        return response()->json([
            'status' => 'Success',
            'data' => [
                'kategori' => $kategori->toArray()
            ],
        ]);
    }
    
    //delete kategori
    public function deleteKategori($id)
    {
        $event = Event::where('id_kategori','=',$id)->count();
        if ($event == 0){
            Kategori::findOrFail($id)->delete();
            return response()->json([
                'status' => 'Success',
                'data' => 'Berhasil menghapus kategori'
            ]);
        }else{
            return response()->json([
                'status' => 'Failed',
                'data' => 'Gagal menghapus kategori'
            ]);
        }
    }    

    //status
    public function indexStatus(){
        $status = Status::get();
        // return response()->json(Status::all());
        if(sizeof($status) > 0){
            return response()->json([
                'status' => 'Success',
                'size' => sizeof($status),
                'data' => [
                    'status' => $status->toArray()
                ],
            ]);
        }else{
            return response(['Tidak ada status'],404);
        }
    }
    
    //detail status
    public function showStatus($id)
    {   
        $status = Status::findOrFail($id);
        // return response()->json($status);
        if(sizeof($status) > 0){
            return response()->json([
                'status' => 'Success',
                'size' => sizeof($status),
                'data' => [
                    'status' => $status->toArray()
                ],
            ]);
        }else{
            return response(['Tidak ada status'],404);
        }
    }

    //create status
    public function createStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_status' => 'required'
        ]);
        if ($validator->fails()) {    
            return response()->json($validator->messages(), 422);
        }
        $status = Status::create($request->all());

        return response()->json($status, 201);
    }

    //updatestatus
    public function updateStatus($id, Request $request)
    {
        $status = Status::findOrFail($id);
        $status->update($request->all());

        return response()->json($status, 200);
    }
    
    //delete status
    public function deleteStatus($id)
    {
        Status::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }    

    //mengirim sertifikat apabila registrasi sudah ditutup
    public function sendSertifikat($id_penandatangan_sertifikat,Request $request){
        $updateSertif = PenandatanganSertifikat::findOrFail($id_penandatangan_sertifikat);
        $sertif = PenandatanganSertifikat::with('penandatangan.kabupaten','sertifikat.event.detail_event.kabupaten','sertifikat.event.panitia','sertifikat.event.peserta_event.peserta','sertifikat.event.peserta_event.event')->where([
            ['id_penandatangan_sertifikat','=',$id_penandatangan_sertifikat], 
        ])->get();
        $sertifikat = $sertif->toArray();
        $nama_sertifikat = $sertifikat[0]['sertifikat']['sertifikat'];
        $nama_event = $sertifikat[0]['sertifikat']['event']['nama_event'];
        $no_sertifikat = $sertifikat[0]['sertifikat']['no_sertifikat'];
        $tgl_mulai = $sertif[0]['sertifikat']['event']['detail_event']['start_event'];
        $tgl_selesai = $sertifikat[0]['sertifikat']['event']['detail_event']['end_event'];
        $lokasi = $sertifikat[0]['sertifikat']['event']['detail_event']['lokasi'];
        $nip = $sertifikat[0]['penandatangan']['nip'];
        $jabatan = $sertifikat[0]['penandatangan']['jabatan'];
        $signer = $sertifikat[0]['penandatangan']['nama_penandatangan'];
        $kota = $sertifikat[0]['penandatangan']['kabupaten']['ibu_kota'];
        $path = public_path('uploads/sertifikat/'.$nama_event);
        if(!File::isDirectory($path)){
            File::makeDirectory($path);
        } 
        for($i=0; $i<sizeof(array_values($sertifikat)[0]['sertifikat']['event']['peserta_event']); $i++){
            $nama_peserta = array_values($sertifikat)[0]['sertifikat']['event']['peserta_event'][$i]['peserta']['nama_peserta'];
            $file = $nama_peserta.'-'.time();
            DB::beginTransaction();
            try{
                if($tgl_mulai != $tgl_selesai){
                    $templateProcessor = new TemplateProcessor(public_path('uploads/sertifikat/'.$nama_sertifikat));
                    $templateProcessor->setValues(array(
                        'NO_SERTIFIKAT' => $no_sertifikat,
                        'PESERTA' => $nama_peserta,
                        'EVENT' => $nama_event,
                        'TGL_MULAI' => \Carbon\Carbon::parse($tgl_mulai)->locale('id_ID')->isoFormat('LL'),
                        'TGL_SELESAI' => \Carbon\Carbon::parse($tgl_selesai)->locale('id_ID')->isoFormat('LL'),
                        'LOKASI' => $lokasi,
                        'KOTA' => $kota,
                        'TANGGAL' => \Carbon\Carbon::parse(\Carbon\Carbon::now())->locale('id_ID')->isoFormat('LL'),
                        'JABATAN' => $jabatan,
                        'PENANDATANGAN' => $signer,
                        'NIP' => $nip
                    ));
                    $templateProcessor->saveAs(public_path('uploads/sertifikat/'.$file.'.docx'));
                }
                else if($tgl_mulai == $tgl_selesai){
                    $templateProcessor = new TemplateProcessor(public_path('uploads/sertifikat/'.$nama_sertifikat));
                    $templateProcessor->setValues(array(
                        'NO_SERTIFIKAT' => $no_sertifikat,
                        'PESERTA' => $nama_peserta,
                        'EVENT' => $nama_event,
                        'TGL' => \Carbon\Carbon::parse($tgl_mulai)->locale('id_ID')->isoFormat('LL'),
                        'LOKASI' => $lokasi,
                        'KOTA' => $kota,
                        'TANGGAL' => \Carbon\Carbon::parse(\Carbon\Carbon::now())->locale('id_ID')->isoFormat('LL'),
                        'JABATAN' => $jabatan,
                        'PENANDATANGAN' => $signer,
                        'NIP' => $nip
                    ));
                    $templateProcessor->saveAs(public_path('uploads/sertifikat/'.$file.'.docx'));
                }
            }catch(\Exception $e){
                DB::rollback();
                return response()->json(['status' => 'Gagal menginject data ke dokumen word', 'message' => $e->getMessage()]);
            }
            try{
                ConvertApi::setApiSecret('TFaLpynmVYvFqyOI');
                $result = ConvertApi::convert('pdf', [
                        'File' => public_path('uploads/sertifikat/'.$file.'.docx'),
                    ], 'docx'
                );
                $result->saveFiles(public_path('uploads/sertifikat/'.$file.'.pdf'));      
                File::move(public_path('uploads/sertifikat/'.$file.'.pdf'), public_path('uploads/sertifikat/'.$nama_event.'/'.$file.'.pdf'));
                File::delete(public_path('uploads/sertifikat/'.$file.'.docx'));
            }catch(\Exception $e){
                return response()->json(['status' => 'Gagal mengconvert ke pdf', 'message' => $e->getMessage()]);
            }
            try{
                $id_peserta = $sertif[0]['sertifikat']['event']['peserta_event'][$i]['peserta']['id_peserta'];
                $id_event = $sertif[0]['sertifikat']['event']['peserta_event'][$i]['event']['id_event'];  
                $peserta_event = PesertaEvent::where([
                    ['id_peserta', '=', $id_peserta],
                    ['id_event', '=', $id_event]
                ])->first();   
                if($updateSertif->nama_sertifikat == null){
                    $updateSertif->update($request->all());
                    $updateSertif->id_status = 3;
                    $updateSertif->nama_sertifikat = $file.'.pdf';
                    $updateSertif->nama_event = $nama_event;
                    $updateSertif->save();
                    $peserta_event->update($request->all());
                    $peserta_event->nama_sertifikat = $file.'.pdf';
                    $peserta_event->nama_event = $nama_event;
                    $peserta_event->save();
                }
                else{
                    $penandatangan_sertifikat = new PenandatanganSertifikat;
                    $penandatangan_sertifikat->id_penandatangan = $updateSertif->id_penandatangan;
                    $penandatangan_sertifikat->id_sertifikat = $updateSertif->id_sertifikat;
                    $penandatangan_sertifikat->id_status = 3;
                    $penandatangan_sertifikat->nama_sertifikat = $file.'.pdf';
                    $penandatangan_sertifikat->nama_event = $nama_event;
                    $penandatangan_sertifikat->save();
                    $peserta_event->update($request->all());
                    $peserta_event->nama_sertifikat = $file.'.pdf';
                    $peserta_event->nama_event = $nama_event;
                    $peserta_event->save();
                }
            }catch(\Exception $e){
                DB::rollback();
                return response()->json(['status' => 'Gagal mengubah status sertifikat', 'message' => $e->getMessage()]);
            }
            DB::commit();       
        }
        return response()->json([
            'status' => 'Success',
            'message' => 'Sertifikat sudah dikirim ke penandatangan'
        ]);
    }

    //menolak sertifikat karena salah format
    public function rejectSertifikat($id_penandatangan_sertifikat){
        $penandatangan_sertifikat = PenandatanganSertifikat::findOrFail($id_penandatangan_sertifikat);
        $sertifikat = Sertifikat::findOrFail($penandatangan_sertifikat->id_sertifikat); 
        $event = Event::findOrFail($sertifikat->id_event);
        $panitia = Panitia::find($event->id_panitia);
        $users = User::find($panitia->id_users);
        $panitias=$panitia->toArray();
        $user=$users->toArray();
        $events=$event->toArray();
        try{
            $details = [
                'subject' => 'Sertifikat ditolak',
                'greeting' =>'Hallo '.array_values($panitias)[2],
                'body' => 'Sertifikat anda pada event '.array_values($events)[6].' ditolak karena tidak sesuai dengan ketentuan',
                'thank' => 'SIlahkan unggah kembali sertifikat yang sesuai dengan ketentuan',
            ];
            $users->notify(
                new SendNotification($details)
            );
        }catch(\Exception $e){
            return response()->json(['status' => 'Gagal mengirim email, cek koneksi internet', 'message' => $e->getMessage()]);
        }
        try{
            $penandatangan_sertifikat->delete();
        }catch(\Exception $e){
            return response()->json(['status' => 'Gagal menghapus data penandatangan', 'message' => $e->getMessage()]);
        }
        return response()->json([
            'status' => 'Success',
            'message' => 'Sertifikat berhasil ditolak'
        ]);
    }

    //menampilkan jumlah event yang diadakan tiap bulan
    public function countEventbyMonth(){ 
        $month = [
            'Januari' => '01',
            'Februari' => '02',
            'Maret' => '03',
            'April' => '04',
            'Mei' => '05',
            'Juni' => '06',
            'Juli' => '07',
            'Agustus' => '08',
            'September' => '09',
            'Oktober' => '10',
            'November' => '11',
            'Desember' => '12',

        ];
        for($i=0; $i<sizeof($month); $i++){
            $event = DetailEvent::with('event')->whereHas('event', function ($query) {
                $query->where('id_status_event', '=', 6);
           })->whereYear('start_event',Carbon::now()->year)->whereMonth('start_event',array_values($month)[$i])->count();

            $thisMonth[]=array_search(array_values($month)[$i], $month);
            $total[] = $event;
        }
        return response()->json([
            'status' => 'Success',
            'size' => sizeof($thisMonth),
            'data' => [
                'bulan' => $thisMonth,
                'data' => $total
            ],
        ],200);
    }

    //menampilkan jumlah user tiap role
    public function countRolebyUser(){ 
        $roles = [
            'panitia' => '2',
            'peserta' => '3',
            'penandatangan' => '4',
        ];
        
        for($i=0; $i<sizeof($roles); $i++){
            $user = User::where('id_role','=',array_values($roles)[$i])->count();
            
            $thisUser[] = array_search(array_values($roles)[$i], $roles);
            $total[] = $user;
        }
        return response()->json([
            'status' => 'Success',
            'size' => sizeof($thisUser),
            'data' => [
                'user' => $thisUser,
                'data' => $total
            ],
        ],200);
    }

    //input nama event dan nama panitia
    public function injectSertif($id_penandatangan_sertifikat){
        $sertif = PenandatanganSertifikat::with('penandatangan','sertifikat.event.detail_event','sertifikat.event.panitia','sertifikat.event.peserta_event.peserta')->where([
            ['id_penandatangan_sertifikat','=',$id_penandatangan_sertifikat], 
        ])->get();
        
        $sertifikat = $sertif->toArray();
        
        $array = array();
        for($i=0; $i<sizeof(array_values($sertifikat)[0]['sertifikat']['event']['peserta_event']); $i++){
            $filepath = public_path('template.rtf');    
            $array[$i] = array(
                '[EVENT]' => array_values($sertifikat)[0]['sertifikat']['event']['nama_event'],
                '[PESERTA]' => array_values($sertifikat)[0]['sertifikat']['event']['peserta_event'][$i]['peserta']['nama_peserta'],
                '[NIP]' => array_values($sertifikat)[0]['penandatangan']['nip'],
                '[TEMPAT]' => 'Yogyakarta',
                '[JABATAN]' => array_values($sertifikat)[0]['penandatangan']['jabatan'],
                '[SIGNER]' => array_values($sertifikat)[0]['penandatangan']['nama_penandatangan'],
                '[TANGGAL]' => date('d F Y'),
            );
            
            $nama_file = $sertif[0]['sertifikat']['event']['peserta_event'][$i]['peserta']['nama_peserta'].'.rtf';
            
            
            WordTemplate::export($filepath, $array[$i], $nama_file);

            ConvertApi::setApiSecret('j0E1yU6kPbwjDxNr');
            $result = ConvertApi::convert('pdf', [
                    'File' => public_path('download/'.$nama_file),
                ], 'rtf'
            );
            $result->saveFiles(public_path('uploads/sertifikat/'));
            
            // $pdfname = time().'.pdf';

            // $FilePath = public_path('download/'.$nama_file);
            // $FilePathPdf = public_path()."/uploads/sertifikat/".$pdfname;

 
            // \PhpOffice\PhpWord\Settings::setPdfRendererPath(base_path() .'/vendor/dompdf/dompdf');

            // \PhpOffice\PhpWord\Settings::setPdfRendererName('DomPDF');

            
            // $phpWord = \PhpOffice\PhpWord\IOFactory::load($FilePath);

            // $pdfWriter = \PhpOffice\PhpWord\IOFactory::createWriter( $phpWord, 'PDF' );

            // $pdfWriter->save(public_path('uploads/sertifikat/'));

       }
       return response('sertifikat berhasil dikirim');
    }

    //menampilkan detail view untuk sertifikat
    public function showFileSertifikat($id){
        $sertifikat = Sertifikat::findOrFail($id);
        $file_sertifikat = $sertifikat->sertifikat;
        $file= public_path(). "/uploads/sertifikat/".$file_sertifikat;
        $nama_sertifikat = $sertifikat->nama;
        return response()->download($file,$nama.'.docx');
    }


    public function allSertifikat(){
        $sertifikat = PenandatanganSertifikat::with('penandatangan','sertifikat.event.panitia','sertifikat.event.detail_event')->where('nama_sertifikat','!=',null)->count();
        return response()->json([
            'status' => 'Success',
            'data' => [
               'sertifikat' => $sertifikat
            ],
        ]);
    }

    public function allUser(){
        $event = User::where('id_role','!=',1)->count(); 
        return response()->json([
            'status' => 'Success',
            'data' => [
               'user' => $event
            ],
        ]);
    }

    public function allEvent(){
        $event = Event::where('id_status_event','=',6)->count(); 
        return response()->json([
            'status' => 'Success',
            'data' => [
               'event' => $event
            ],
        ]);
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
    public function test(){
        $converter = new OfficeConverter(public_path('uploads/sertifikat/BL.docx'));
        $converter->convertTo(public_path('uploads/sertifikat/BL.pdf'));
    }
}
