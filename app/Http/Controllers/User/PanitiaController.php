<?php

namespace App\Http\Controllers\User;
use App\User;
use App\UsersRole;
use App\Panitia;
use App\PesertaEvent;
use App\Event;
use App\DetailEvent;
use App\Sertifikat;
use App\Peserta;
use App\Kategori;
use App\BiodataPenandatangan;
use App\PenandatanganSertifikat;
use App\Provinsi;
use App\Kabupaten;
use File;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Notifications\Notifiable;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use App\Notifications\PesertaRegisteredEvent;
use Response;
use Illuminate\Support\Facades\Hash;
class PanitiaController extends Controller
{
    //menampilkan event sesuai id_panitia yang masih active
    public function indexEventinDate()
    {
        $auth = Auth::user()->panitia;
        $datenow = Carbon::now(); 
        $event = Event::with(['detail_event','kategori','status_biaya'])
                ->where( 'id_panitia', '=', $auth->id_panitia)
                ->where( 'id_status_event', '=', 6)
                ->whereHas('detail_event', function($q) use($datenow) {
                    $q->where('end_event', '>=', $datenow); 
                })
                ->get();
        if(sizeof($event) > 0){
            return response()->json([
                'status' => 'Success',
                'size' => sizeof($event),
                'data' => [
                    'event' => $event->toArray()
                ],
            ],200);
        }else if(sizeof($event) == 0){
            return response()->json([
                'status' => 'Success',
                'size' => sizeof($event),
                'data' => [
                    'event' => $event->toArray()
                ],
            ],200);
        }
    }

    //get event yang masih sudah lewat
    public function getEventinOutDate()
    {
        $auth = Auth::user()->panitia;
        $datenow = Carbon::now(); 
        $eventPast = Event::with(['detail_event','kategori','status_biaya',])
                    ->where( 'id_panitia', '=', $auth->id_panitia)
                    ->where( 'id_status_event', '=', 6)
                    ->whereHas('detail_event', function($q) use($datenow) {
                        $q->where('end_event', '<', $datenow); 
                    })->withCount([
                        'peserta_event' => function ($query) {
                            $query->where('id_status', 6)->orWhere('id_status',1);
                    }])
                    ->get();
        if(sizeof($eventPast) > 0){
            return response()->json([
                'status' => 'Success',
                'size' => sizeof($eventPast),
                'data' => [
                    'event' => $eventPast->toArray()
                ],
            ],200);
        }else if(sizeof($eventPast) == 0){
            return response()->json([
                'status' => 'Success',
                'size' => sizeof($eventPast),
                'data' => [
                    'event' => $eventPast->toArray()
                ],
            ],200);
        }
    }
    
    public function getPage(Request $request,$pagePath)
    {
        switch ($pagePath) {
            case 'event':
                $auth = Auth::user()->panitia;
                $id = $auth->id_panitia;

                $validator = Validator::make($request->all(), [
                    'deskripsi_event' => 'required|string',
                    'bank' => 'string',
                    'start_event'=> 'required|date',
                    'end_event'=> 'required|date',
                    'open_registration'=> 'required|date',
                    'end_registration'=> 'required|date',
                    'time_start' => 'required|date_format:H:i',
                    'time_end' => 'required|date_format:H:i',
                    'limit_participant' => 'required|string',
                    'lokasi' => 'required|string',
                    'venue' => 'required|string',
                    'picture' => 'required|image|mimes:jpeg,png,jpg|max:2000',
                    'email_event' => 'email',
                    'nama_event' => 'required|string',
                    'organisasi' => 'required|string',
                ]);
                if ($validator->fails()) {    
                    return response()->json($validator->messages(), 422);
                }
                DB::beginTransaction();
                try{
                    $detailevent = new DetailEvent;
                    $detailevent->id_provinsi =  $request->id_provinsi;
                    $detailevent->id_kabupaten = $request->id_kabupaten;
                    $detailevent->deskripsi_event = $request->deskripsi_event;
                    $detailevent->biaya = $request->biaya;
                    $detailevent->bank = $request->bank;
                    $detailevent->nomor_rekening = $request->nomor_rekening;
                    $detailevent->start_event = $request->start_event;
                    $detailevent->end_event = $request->end_event;
                    $detailevent->open_registration = $request->open_registration;
                    $detailevent->end_registration = $request->end_registration;
                    $detailevent->time_start = $request->time_start;
                    $detailevent->time_end = $request->time_end;
                    $detailevent->limit_participant = $request->limit_participant;
                    $detailevent->lokasi = $request->lokasi;
                    $detailevent->venue = $request->venue;
                    if($request->hasfile('picture')){
                        $file = $request->file('picture');
                        $extension = $file->getClientOriginalExtension();
                        $filename = time() . '.' . $extension;
                        $file->move('uploads/event/', $filename);
                        $detailevent->picture = $filename;
                    }
                    $detailevent->telepon = $request->telepon;
                    $detailevent->instagram = $request->instagram;
                    $detailevent->email_event = $request->email_event;
                    $detailevent->save();
                }catch(\Exception $e){
                    DB::rollback();
                    return response()->json(['status' => 'Create Event Failed', 'message' => $e->getMessage()]);
                }
                try{
                    Event::where('id_detail_event',$detailevent->id_detail_event);
                    $event = new Event;
                    $event->id_detail_event = $detailevent->id_detail_event;
                    $event->id_status_event = 5;
                    $event->id_status_biaya = $request->id_status_biaya;
                    $event->id_panitia = $id;
                    $event->id_kategori = $request->id_kategori;
                    $event->nama_event = $request->nama_event;
                    $event->organisasi = $request->organisasi;
                    $event->save();
                }catch(\Exception $e){
                    DB::rollback();
                    return response()->json(['status' => 'Create Event Failed', 'message' => $e->getMessage()]);
                }
                DB::commit();
                return response()->json([
                    'status' => 'Success',
                    'data' => [
                        'event' => $event->toArray(),
                        'detail_event'=> $detailevent->toArray()
                    ],
                ],201);

            case 'biodata-penandatangan':
                $auth = Auth::user()->panitia;
                $id = $auth->id_panitia;
                $validator = Validator::make($request->all(), [
                    'nama' => 'required',
                    'instansi' => 'required|string',
                    'jabatan' => 'required|string',
                    'email' => 'required|email',
                    'nip' => 'required|string'
                ]);
                if ($validator->fails()) {    
                    return response()->json($validator->messages(), 422);
                }
                
                $biodata = new BiodataPenandatangan;
                $biodata->nama = $request->nama;
                $biodata->id_panitia = $id;
                $biodata->instansi = $request->instansi;
                $biodata->jabatan = $request->jabatan;
                $biodata->email = $request->email;
                $biodata->nip = $request->nip;
                $biodata->telepon = $request->telepon;
                $biodata->id_provinsi = $request->id_provinsi;
                $biodata->id_kabupaten = $request->id_kabupaten;
                if($request->hasfile('profile_picture')){
                    $file = $request->file('profile_picture');
                    $extension = $file->getClientOriginalExtension();
                    $filename = time() . '.' . $extension;
                    $file->move('uploads/penandatangan/', $filename);
                    $biodata->profile_picture = $extension;
                }
                $biodata->save();
                return response()->json([
                    'status' => 'Success',
                    'data' => [
                        'biodata_penandatangan' => $biodata->toArray()
                    ],
                ],201);
            default:
                abort(404);
        }
    }

    //Mengedit suatu event
    public function updateEvent($id, Request $request)
    {
        $event = Event::findOrFail($id);
        $detailevent = DetailEvent::findOrFail($id);
        $nama = $detailevent->picture;
        $validator = Validator::make($request->all(), [
            'deskripsi_event' => 'string',
            'biaya' => 'integer',
            'bank' => 'string',
            'nomor_rekening' => 'string',
            'start_event'=> 'date',
            'end_event'=> 'date',
            'open_registration'=> 'date',
            'close_registration'=> 'date',
            'time_start' => 'string',
            'time_end' => 'string',
            'limit_participant' => 'string',
            'lokasi' => 'string',
            'venue' => 'string',
            'email_event' => 'email',
            'nama_event' => 'string',
            'organisasi' => 'string',
        ]);
        
        if ($validator->fails()) {    
            return response()->json($validator->messages(), 422);
        }
        $event->nama_event = $request->nama_event;
        $event->id_kategori = $request->id_kategori;
        $event->id_status_biaya = $request->id_status_biaya;
        $event->organisasi = $request->organisasi;
        $event->save();
        $detailevent->update($request->all());
        if($request->hasFile('picture'))
        {
            $usersImage = public_path('uploads/event/'.$nama); // get previous image from folder
            if (File::exists($usersImage)) { // unlink or remove previous image from folder
                unlink($usersImage);
            }
            $file = $request->file('picture');
            $name = time() . '.' . $file->getClientOriginalExtension();
            $file = $file->move('uploads/event/', $name);
            $detailevent->picture = $name;
            $detailevent->save();
        }
        
        return response()->json([$event,$detailevent], 200);
    }
    
    //Menghapus suatu event
    public function delete($id)
    {
        Event::findOrFail($id)->delete();
        DetailEvent::findorFail($id)->delete();

        return response()->json([
                'status' => 'Success',
                'message' => 'deleted success'
        ],200);
    }   
    
    //Menampilkan data event A
    public function showEvent($id)
    {   
        $event = Event::with(['kategori','detail_event.kabupaten','detail_event.provinsi','status_biaya'])->findOrFail($id);
        return response()->json([
            'status' => 'Success',
            'data' => [
                'event' => $event->toArray()
            ],
        ],200);
    }

    //Menampilkan daftar peserta dari event A
    public function showPesertainEvent($id_event)
    {
        $auth = Auth::user()->panitia;
        $id = $auth->id_panitia;
        $peserta_event = PesertaEvent::with('peserta.users','event','status')
                        ->where('id_event','=',$id_event)
                        ->where('id_status', 6)->orWhere('id_status',1) 
                        ->whereHas('event', function($q) use( $id) {
                            $q->where('id_panitia', '=',  $id); 
                        })
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

    //Menampilkan daftar peserta yang belum absensi
    public function showPesertabyEvent($id){
        $peserta = PesertaEvent::with(['peserta.users','status'])
                ->where( 'id_event', '=', $id)
                ->where('id_status', '=', 6)
                ->get();
        if(sizeof($peserta) > 0){
            return response()->json([
                'status' => 'Success',
                'size' => sizeof($peserta),
                'data' => [
                    'peserta' => $peserta->toArray()
                ],
            ],200);
        }else if(sizeof($peserta) == 0){
            return response()->json([
                'status' => 'Success',
                'size' => sizeof($peserta),
                'data' => [
                    'peserta' => $peserta->toArray()
                ],
            ],200);
        }
    }

    //Menampilkan daftar peserta yang sudah absensi
    public function showPesertabyEventAbsent($id){
        $peserta = PesertaEvent::with(['peserta.users','status'])
                ->where( 'id_event', '=', $id)
                ->Where('id_status', '=', 1)
                ->get();
        if(sizeof($peserta) > 0){
            return response()->json([
                'status' => 'Success',
                'size' => sizeof($peserta),
                'data' => [
                    'peserta' => $peserta->toArray()
                ],
            ],200);
        }else if(sizeof($peserta) == 0){
            return response()->json([
                'status' => 'Success',
                'size' => sizeof($peserta),
                'data' => [
                    'peserta' => $peserta->toArray()
                ],
            ],200);
        }
    }

    //menampilkan detail peserta
    public function detailPeserta($id){
        $peserta = User::with('peserta')
                ->where('id_users','=',$id)
                ->where( 'id_role', '=', 3)
                ->findOrFail($id);
        return response()->json([
            'status' => 'Success',
            'data' => [
                'peserta' => $peserta->toArray()
            ],
        ],200);
    }
    
    //mengupload sertifikat baru
    public function createSertifikat(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'sertifikat' => 'required'
        ]);
        if ($validator->fails()) {    
            return response()->json($validator->messages(), 422);
        }
        DB::beginTransaction();
        try{
            $sertifikat = new Sertifikat;
            $sertifikat->nama = $request->nama;
            $sertifikat->id_event = $request->id_event;
            $sertifikat->no_sertifikat = $request->no_sertifikat;
            if($request->hasfile('sertifikat')){
                $file = $request->file('sertifikat');
                $extension = $file->getClientOriginalExtension();
                $filename = $sertifikat->nama.'-'.time() . '.' . $extension;
                $file->move('uploads/sertifikat/', $filename);
                $sertifikat->sertifikat = $filename;
            }
            $sertifikat->save();  
        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['status' => 'Create Sertifikat Failed', 'message' => $e->getMessage()]);
        } 
        try{
            Sertifikat::where('id_sertifikat',$sertifikat->id_sertifikat);
            $penandatanganArray = $request->input('id_penandatangan');
            $count = sizeof($penandatanganArray);
            
            $items = array();
            for($i = 0; $i < $count; $i++){
                $item = array(
                    'id_penandatangan' => $penandatanganArray[$i],
                    'id_sertifikat' => $sertifikat->id_sertifikat,
                    'id_status' => 8
            );
                $items[] = $item;
            }
            PenandatanganSertifikat::insert($items);
        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['status' => 'Send Penandatangan Failed', 'message' => $e->getMessage()]);
        } 
        DB::commit();
        return response()->json([
            'status' => 'Success',
            'data' => [
                'sertifikat' => $sertifikat->toArray(),
                'penandatangan_sertifikat' => $items
            ],
        ],201);
    }

    //mengedit sertifikat
    public function updateSertifikat($id_sertifikat, Request $request){
        $sertifikat = Sertifikat::findOrFail($id_sertifikat);
        $nama = $sertifikat->sertifikat;
        $validator = Validator::make($request->all(), [
            'nama' => 'string',
            'sertifikat' => 'max:2000'
        ]);
        if ($validator->fails()) {    
            return response()->json($validator->messages(), 422);
        }
        $sertifikat->update($request->all());
        if($request->hasFile('sertifikat'))
        {
            $userSertifikat = public_path('uploads/sertifikat/'.$nama);
            if (File::exists($userSertifikat)) { 
                unlink($userSertifikat);
            }
            $file = $request->file('sertifikat');
            $name = $sertifikat->nama.'-'.time() . '.' . $file->getClientOriginalExtension();
            $file = $file->move('uploads/sertifikat/', $name);
            $sertifikat->sertifikat = $name;
            $sertifikat->save();
        }
        $penandatanganSertifikat = PenandatanganSertifikat::where('id_sertifikat',$id_sertifikat)->first();

        $penandatanganSertifikat->update($request->all());
        return response()->json([$sertifikat], 200);
    }

    //menampilkan profil di halaman edit panitia
    public function showEditProfile()
    {
         $auth = Auth::user();
         $id = $auth->id_users;
         $panitia = User::with(['panitia'])->findOrFail($id);
         return response()->json([
             'status' => 'Success',
             'data' => [
                'user' => $panitia->toArray()
             ],
        ]);
    }

    //update profile panitia.
    public function updateProfile($id_panitia,Request $request)
    {
        $panitia = Panitia::findOrFail($id_panitia);
        $users = User::findOrFail($panitia->id_users);
        $nama = $panitia->foto_panitia;
        $validator = Validator::make($request->all(), [
            'nama_panitia' => 'string',
            'organisasi' => 'string',
            'instagram' => 'string',
            'telepon' => 'string',
        ]);
        if ($validator->fails()) {    
            return response()->json($validator->messages(), 422);
        }
        $panitia->update($request->all());
        $panitia->telepon = $request->telepon;
        $panitia->save();
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

    //menampilkan data sertifikat yang sudah ditandatangani dari tiap panitia
    public function indexSertifikat ()
    {
        $auth = Auth::user()->panitia;
        $id = $auth->id_panitia;
        $sertifikat = PenandatanganSertifikat::with('penandatangan','sertifikat.event','status')
                    ->where('id_status', '=', 1)
                    ->whereHas('sertifikat.event', function($q) use($id) {
                        $q->where('id_panitia', '=', $id); 
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

    //menampilkan data sertifikat per event yang sedang menunggu
    public function showTotalWaitingSertifikat () {
        $auth = Auth::user()->panitia;
        $id = $auth->id_panitia;
        
        $sertifikat = Event::with(['sertifikat.penandatanganan_sertifkat'=>function($f){
                        $f->select('id_sertifikat')->selectRaw('count(*) as total')->where('id_status','=',3)->groupBy('id_sertifikat');
                      }])
                      ->where( 'id_panitia', '=', $auth->id_panitia)
                      ->whereHas('sertifikat.penandatanganan_sertifkat', function($q){
                            $q->where('nama_sertifikat', '!=', null)->select('id_sertifikat')->where('id_status','=',3); 
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
                'size' => sizeof($sertifikat),
                'data' => [
                    'sertifikat' =>  $sertifikat->toArray()
                ],
            ]);
        }
    }

    // $sertifikat = Event::with(['sertifikat.penandatanganan_sertifkat'=>function($f){
    //     $f->select('id_sertifikat')->selectRaw('count(*) as total')->where('id_status','=',3)->groupBy('id_sertifikat');
    // }])
    // ->where( 'id_panitia', '=', $auth->id_panitia)
    // ->whereHas('sertifikat.penandatanganan_sertifkat', function($q){
    //     $q->where('nama_sertifikat', '!=', null)->select('id_sertifikat'); 
    // })
    // ->get();

    //menampilkan data sertifikat yang belum ditandatangani dari tiap panitia
    public function indexWaitingSertifikat($id_event)
    {
        $auth = Auth::user()->panitia;
        $id = $auth->id_panitia;
        
        $sertifikat = PenandatanganSertifikat::with('penandatangan','sertifikat.event')
                    ->where('id_status', '=', 3)
                    ->whereHas('sertifikat.event', function($q) use($id, $id_event) {
                        $q->where('id_panitia', '=', $id)->where('id_event', '=', $id_event); 
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

    public function indexUploadSertifikat() 
    {
        $auth = Auth::user()->panitia;

        $id = $auth->id_panitia;
        
        $sertifikat = PenandatanganSertifikat::with('penandatangan','sertifikat.event')
                    ->where('id_status', '=', 8)
                    ->whereHas('sertifikat.event', function($q) use($id) {
                        $q->where('id_panitia', '=', $id); 
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

    //menampilkan detail edit sertifikat
    public function showSertifikat($id)
    {   
        $sertifikat = PenandatanganSertifikat::with('penandatangan','sertifikat.event')->findOrFail($id);

        return response()->json([
            'status' => 'Success',
            'data' => [
                'sertifikat' => $sertifikat->toArray()
            ],
        ]);
    }

    //menampilkan detail view untuk sertifikat
    public function showFileSertifikat($id){
        $sertifikat = Sertifikat::findOrFail($id);
        $file_sertifikat = $sertifikat->sertifikat;
        $file= public_path(). "/uploads/sertifikat/".$file_sertifikat;
        $nama_sertifikat = $sertifikat->nama_sertifikat;
        return response()->download($file,$nama_sertifikat.'.docx');
    }

    //menampilkan data peserta yang mendaftar
    public function indexRegisterPeserta($id_event)
    {
        $auth = Auth::user()->panitia;
        $id = $auth->id_panitia;
        $peserta_event = PesertaEvent::with('peserta.users','event','status')
                        ->where('id_status','=',5)
                        ->where('id_event','=',$id_event)
                        ->whereHas('event', function($q) use( $id) {
                            $q->where('id_panitia', '=',  $id); 
                        })
                        ->orderBy('created_at', 'ASC')
                        ->get();
        $total_daftar = Event::with(['detail_event'])
                        ->where('id_event','=',$id_event)
                        ->withCount([
                            'peserta_event as diterima' => function ($query) {
                                $query->where('id_status', 6);
                        },
                        'peserta_event as terdaftar' => function ($query) {
                            $query->where('id_status', 5);
                        }
                        ])
                        ->get();
        if(sizeof($peserta_event) > 0){
            return response()->json([
                'status' => 'Success',
                'size' => sizeof( $peserta_event),
                'data' => [
                    'peserta' =>  $peserta_event->toArray(),
                    'total_regis' => $total_daftar->toArray()
                ],
            ]);
        }else if(sizeof($peserta_event) == 0){
            return response()->json([
                'status' => 'Success',
                'size' => sizeof( $peserta_event),
                'data' => [
                    'peserta' =>  $peserta_event->toArray(),
                    'total_regis' => $total_daftar->toArray()
                ],
            ]);
        }
    }

    //ubah status peserta untuk absensi
    public function statusAbsensi($id, Request $request){
        $peserta_event = PesertaEvent::with('peserta','event','peserta.users')->findOrFail($id);
        $peserta_event->update($request->all());
        $peserta_event->id_status = 1;
        $peserta_event->save();
        return response()->json([
            'status' => 'Success',
            'data' => [
               'user' => $peserta_event->toArray()
            ],
       ]);
    }

    //approve peserta event
    public function approvePeserta($id, Request $request)
    { 
        $peserta_event = PesertaEvent::with('peserta','event','peserta.users')->findOrFail($id);
        $peserta = Peserta::find($peserta_event->id_peserta);
        $user = User::find($peserta->id_users);
        $event = Event::find($peserta_event->id_event);
        $detail_event = DetailEvent::find($event->id_detail_event);

        $kuota = PesertaEvent::where('id_event', '=', $event->id_event)->get();

        $pesertas=$peserta->toArray();
        $events=$event->toArray();
        $detailevent=$detail_event->toArray();

        $details = [
            'greeting' =>'Hallo '.array_values($pesertas)[2],
            'body' => 'Anda telah terdaftar pada Event '.array_values($events)[6],
            'thank' => 'Jangan lupa datang pada tanggal '.Carbon::parse(array_values($detailevent)[10])->locale('id_ID')->isoFormat('LL'),
        ];

        if(sizeof($kuota) > $detail_event->limit_participant){
            return response()->json([
                'status' => 'Full',
                'messages' => 'Kuota event '.array_values($events)[6].' sudah penuh. Silahkan reject peserta'
                ]);
        }else{
            $user->notify(
                new PesertaRegisteredEvent($details)
            );
            $peserta_event->id_status = 6;
            $peserta_event->save();
            return response()->json('Pemberitahuan email ke peserta sudah dikirim');
        }
    }

    //reject peserta event.
    public function rejectPeserta($id, Request $request)
    {
        $peserta_event = PesertaEvent::with('peserta','event','peserta.users')->findOrFail($id);
        $peserta = Peserta::find($peserta_event->id_peserta);
        $user = User::find($peserta->id_users);
        $event = Event::find($peserta_event->id_event);
        $detail_event = DetailEvent::find($peserta_event->id_event);


        $pesertas=$peserta->toArray();
        $events=$event->toArray();
        $detailevent=$detail_event->toArray();

        $details = [
            'greeting' =>'Hallo '.array_values($pesertas)[2],
            'body' => 'Mohon maaf anda tidak bisa mendaftar Event '.array_values($events)[6].' karena kuota sudah penuh.',
            'thank' => 'Silahkan mengikuti event yang lain.',
        ];
    

        $peserta_event->update($request->all());
        $user->notify(
            new PesertaRegisteredEvent($details)
        );
        PesertaEvent::with('peserta','event','peserta.users')->findOrFail($id)->delete();
        return response()->json('Pemberitahuan email ke peserta sudah dikirim');
    }

    //Menambah biodata penandatangan
    public function createBiodataPenandatangan(Request $request)
    {   
        $auth = Auth::user()->panitia;

        $id = $auth->id_panitia;
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'email' => 'required|email',
            'instansi' => 'required|string',
            'jabatan' => 'required|string',
            'nik' => 'required|integer'
        ]);
        if ($validator->fails()) {    
            return response()->json($validator->messages(), 422);
        }
        
        $biodata = BiodataPenandatangan::create($request->all());

        return response()->json($biodata, 201);
    }

    //menampilkan profil panitia
    public function showProfile()
    {
        $auth = Auth::user()->panitia;
        $id = $auth->id_panitia;
        $panitia = User::with(['panitia'])
                    ->whereHas('panitia', function ($query) use($id){
                         $query->where('id_panitia', '=', $id);
                    })->get();

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
            ],200);
        }
    }

    //kategori
    public function indexKategori(){
        $kategori = Kategori::get();
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

    //menampilkan data penandatangan
    public function showPenandatangan()
    {
        $penandatangan = User::with(['penandatangan'])->whereHas('penandatangan', function ($query) {
            $query->where('id_role', '=', 4);
        })->get();
            
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
            ],200);
        }else{
            return response(['Belum ada penandatangan'],404);
        }
    }   

    //menampilkan data count data pendaftar
    public function countRegister(){
        $auth = Auth::user()->panitia;
        $id = $auth->id_panitia;
        $datenow = Carbon::now(); 
        $total_daftar =    Event::with(['detail_event'])
                            ->where('id_panitia','=',$id)
                            ->where( 'id_status_event', '=', 6)
                            ->whereHas('detail_event', function($q) use($datenow) {
                                $q->where('end_event', '>=', $datenow); 
                            })->withCount([
                                'peserta_event as diterima' => function ($query) {
                                    $query->where('id_status', 6);
                            },
                            'peserta_event as terdaftar' => function ($query) {
                                $query->where('id_status', 5);
                            }
                            ])
                            ->get();
        if(sizeof($total_daftar) > 0){
            return response()->json([
                'status' => 'Success',
                'size' => sizeof($total_daftar),
                'data' => [
                    'event' => $total_daftar->toArray()
                ],
            ]);
    
        }else if(sizeof($total_daftar) == 0){
            return response()->json([
                'status' => 'Success',
                'size' => sizeof($total_daftar),
                'data' => [
                    'event' => $total_daftar->toArray()
                ],
            ],200);
        }else{
                return response(['Belum ada panitia'],404);
        }
    }

    //download template sertifikat
    public function getDownload()
    {
        $file= public_path(). "/download/template.docx";
  
        return Response::download($file, 'sertifikat.docx');
    }

    //menampilkan data provinsi
    public function getProvinsi(){
        $provinsi = Provinsi::get();
        return response()->json([
            'status' => 'Success',
            'size' => sizeof($provinsi),
            'data' => [
                'provinsi' => $provinsi->toArray()
            ],
        ]);
    }

    //menampilkan data kabupaten
    public function getKabupaten($id_provinsi){
        $kabupaten = Kabupaten::where('id_provinsi', $id_provinsi)->get();
        return response()->json([
            'status' => 'Success',
            'size' => sizeof($kabupaten),
            'data' => [
                'kabupaten' => $kabupaten->toArray()
            ],
        ]);
    }

    //menampilkan data kabupaten yangs udah ada value
    public function getDataKabupaten($id_kabupaten){
        $kabupaten = Kabupaten::where('id_kabupaten', $id_kabupaten)->get();
        return response()->json([
            'status' => 'Success',
            'size' => sizeof($kabupaten),
            'data' => [
                'kabupaten' => $kabupaten->toArray()
            ],
        ]);
    }

    //menampilkan count peserta yang mendaftar perhari
    public function getRegisterPesertabyToday(){
        $auth = Auth::user()->panitia;
        $id = $auth->id_panitia;
        $datenow = Carbon::now()->toDateString(); 
        $peserta = PesertaEvent::with('event.panitia')
                    ->whereHas('event.panitia', function($q) use($id) {
                        $q->where('id_panitia', '=', $id); 
                    })
                   ->where( 'id_status', '=', 5)
                   ->whereDate( 'created_at','=', $datenow)
                   ->count();
        return response()->json([
            'status' => 'Success',
            'data' => [
                'peserta' => $peserta
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
}
