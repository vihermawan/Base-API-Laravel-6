<?php


namespace App\Http\Controllers\User;
use App\User;
use App\UsersRole;
use App\Peserta;
use App\PesertaEvent;
use App\Sertifikat;
use App\Event;
use App\Kategori;
use File;
use Auth;
use Illuminate\Support\Carbon;
use App\Notifications\TemplateEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Response;
use Illuminate\Support\Facades\Hash;

class PesertaController extends Controller
{  
    //menampilkan semua event yg masih berjalan di halaman All Event
    public function AllEvent(){
        $datenow = Carbon::now(); 
        $event = Event::with(['detail_event','kategori','status_biaya','panitia.users'])
                ->where( 'id_status_event', '=', 6)
                ->whereHas('panitia.users', function($q) {
                    $q->where('isBan', '=', false ); 
                })
                ->whereHas('detail_event', function($q) use($datenow) {
                    $q->where('end_event', '>', $datenow)->orderBy('start_event', 'DSC'); 
                })->withCount([
                    'peserta_event' => function ($query) {
                        $query->where('id_status', 6);
                }])
                
                ->paginate(16);
            if(sizeof($event) > 0){
                return response()->json([
                    'status' => 'Success',
                    'size' => sizeof($event),
                    'data' => [
                        'event' => $event->toArray()
                    ],
                ],200);
            }else{
                return response()->json([
                    'status' => 'Success',
                    'size' => sizeof($event),
                    'data' => [
                        'event' => $event->toArray()
                    ],
                ],200);
            }
    }

    //menampilkan semua event per kategori
    public function showAllKategori($id_kategori){
        $datenow = Carbon::now();
        $event = Event::with(['detail_event','kategori','status_biaya','panitia.users'])
                ->where( 'id_status_event', '=', 6)
                ->whereHas('panitia.users', function($q) {
                    $q->where('isBan', '=', false ); 
                })
                ->whereHas('detail_event', function($q) use($datenow) {
                    $q->where('end_event', '>', $datenow); 
                })
                ->whereHas('kategori', function($q) use($id_kategori) {
                    $q->where('id_kategori', '=', $id_kategori); 
                })->withCount([
                    'peserta_event' => function ($query) {
                        $query->where('id_status', 6);
                }])
                ->paginate(16);
            if(sizeof($event) > 0){
                return response()->json([
                    'status' => 'Success',
                    'size' => sizeof($event),
                    'data' => [
                        'event' => $event->toArray()
                    ],
                ],200);
            }else{
                return response()->json([
                    'status' => 'Success',
                    'size' => sizeof($event),
                    'data' => [
                        'event' => $event->toArray()
                    ],
                ],200);
            }
    }

    //menampilkan semua event yg masih berjalan
    public function showAllEvent(){
        $datenow = Carbon::now(); 
        $event = Event::with(['detail_event','kategori','status_biaya','panitia.users'])
                ->where( 'id_status_event', '=', 6)
                ->whereHas('panitia.users', function($q) {
                    $q->where('isBan', '=', false ); 
                })
                ->whereHas('detail_event', function($q) use($datenow) {
                    $q->where('end_event', '>=', $datenow); 
                })->withCount([
                    'peserta_event' => function ($query) {
                        $query->where('id_status', 6);
                }])
                ->paginate(16);
            if(sizeof($event) > 0){
                return response()->json([
                    'status' => 'Success',
                    'size' => sizeof($event),
                    'data' => [
                        'event' => $event->toArray()
                    ],
                ],200);
            }else{
                return response()->json([
                    'status' => 'Success',
                    'size' => sizeof($event),
                    'data' => [
                        'event' => $event->toArray()
                    ],
                ],200);
            }
    }

    //membatalkan pendaftaran event
    public function cancelDaftar($id_event){
        $auth = Auth::user()->peserta;
        $id = $auth->id_peserta;
        
        $event = PesertaEvent::where('id_peserta','=', $id)->where('id_event', '=', $id_event)->get();                  
        if(sizeof($event) > 0){
            PesertaEvent::where('id_peserta','=', $id)->where('id_event', '=', $id_event)->delete();
            return response()->json([
                'status' => 'Success',
                'messages' => 'Delete Succesfully',
            ],200);
        }else{
            return response()->json([
                'status' => 'Failed',
                'messages' => 'Anda belum mendaftar event tersebut',
            ],404);
        }
    }

    //menampilkan event sesuai kategori
    public function showEventbyKategori($id_kategori){
        $datenow = Carbon::now(); 
        $event = Event::with(['detail_event','kategori','status_biaya','panitia.users'])
                ->where( 'id_status_event', '=', 6)
                ->where('id_kategori','=', $id_kategori)
                ->whereHas('detail_event', function($q) use($datenow) {
                    $q->where('end_event', '>', $datenow); 
                })->withCount([
                    'peserta_event' => function ($query) {
                        $query->where('id_status', 6);
                }])
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
            }else{
                return response(['Belum ada event'],404);
            }
    }

    //menampilkan event yang terdekat dari segi waktu dalam seminggu
    public function showEventbyWeek(){
        $datenow = Carbon::now(); 
        $week = Carbon::now()->subWeek(-1)->format('Y-m-d');
        $event = Event::with(['detail_event','kategori','status_biaya','panitia.users'])
                ->where( 'id_status_event', '=', 6)
                ->whereHas('panitia.users', function($q) {
                    $q->where('isBan', '=', false ); 
                })
                ->whereHas('detail_event', function($q) use($week,$datenow) {
                    $q->where('end_event', '>', $datenow)->where('start_event', '<', $week)->where('start_event', '>', $datenow); 
                })
                ->withCount([
                    'peserta_event' => function ($query) {
                        $query->where('id_status', 6);
                }])
                ->paginate(12);
        // return $week;
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
            }else{
                return response(['Belum ada event'],404);
            }
    }

    //membuat query untuk pencarian data event
    public function searchEvent(Request $request)
	{
		// menangkap data pencarian
		$nama = $request->nama;
 
    	// mengambil data dari table event sesuai pencarian data
        $event = Event::with(['detail_event','kategori','status_biaya','panitia.users'])->where('nama_event','ILIKE',"%".$nama."%")
		->paginate();
 
    	if(sizeof($event) > 0){
            return response()->json([
                'status' => 'Success',
                'data' => [
                    'event' => $event->toArray()
                ],
            ],200);
   
        }else{
            return response(['event tidak ditemukan'],404);
        }
	}

    //mengedit data profil peserta
    public function updateProfile($id_peserta,Request $request)
    {
        $peserta = Peserta::findOrFail($id_peserta);
        $nama = $peserta->foto_peserta;;

        $users = User::findOrFail($peserta->id_users);
        $validator = Validator::make($request->all(), [
            // 'email' => 'email|unique:users',
            'password'=> 'min:8|confirmed',
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
            
        }
        $peserta->save();
        $users->update($request->all());
        return response()->json([$users,$peserta], 200);
    }

    //mendaftar event
    public function registerEvent(Request $request)
    {
        $auth = Auth::user()->peserta;
        $id = $auth->id_peserta;

        $peserta_event = new PesertaEvent;
        $peserta_event->id_peserta = $id;
        $peserta_event->id_status = 5;
        $peserta_event->id_event = $request->id_event;
        $daftarpeserta = PesertaEvent::where([
            ['id_event','=', $peserta_event->id_event],
            ['id_peserta','=', $peserta_event->id_peserta]
        ])->get();
    
        if(sizeof($daftarpeserta) > 0){
            return response()->json([
                'status' => 'Register',
                'messages' => 'Anda sudah mendaftar event ini. Silahkan menunggu konfirmasi 1x24jam di email anda'
            ]);
        }else{
            $peserta_event->save();
            return response()->json($peserta_event, 201);
        }

    }

    //Menampilkan data event A
    public function showDetailEvent($id)
    {   
        $event = Event::with(['kategori','detail_event','status_biaya'])
                ->withCount([
                    'peserta_event' => function ($query) {
                        $query->where('id_status', 6);
                }])->findOrFail($id);
            return response()->json([
                'status' => 'Success',
                'data' => [
                    'event' => $event->toArray()
                ],
            ],200);
    }

    //Menampilkan semua event yang diikuti peserta A
    public function showEvent(){
        $auth = Auth::user()->peserta;
        $id=$auth->id_peserta;
        $event = PesertaEvent::with('event','event.detail_event','event.kategori','event.status')->where('id_peserta','=',$id)->get();
        
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

    //menampilkan event yang diikuti oleh peserta
    public function indexPesertaInEvent()
    {
        $peserta_event = PesertaEvent::with('peserta','event')->get();
        if(sizeof($peserta_event) > 0)
            return response()->json([
                'status' => 'Success',
                'size' => sizeof( $peserta_event),
                'data' => [
                    'sertifikat' =>  $peserta_event->toArray()
                ],
            ]);
        else{
            return response(['Belum ada yang mendaftar'],404);
        }
    }

    //menampilkan semua sertifikat dari event yang diikuti peserta
    public function indexSertifikatInPeserta($ids){
        $auth = Auth::user()->peserta;
        $id = $auth->id_peserta;
        
        $peserta = Sertifikat::with(['event.sertifikat.penandatangan_sertifikat'])
                    //->where( 'id_status', '=', 1)
                  
                    ->whereHas('event.sertifikat', function($q)  {
                        $q->where('id_event', '!=', null); 
                    })
                    ->whereHas('event.peserta_event', function($j) use($id) {
                        $j->where('id_peserta', '=', $id); 
                    })
                    ->get()->toArray();
                    
        //return response()->json($sertifikat);
        // $details = [
        //     'greeting' => 'test'.array_values($sertifikats)[0],
        // // ];
        //dd(array_values($sertifikat)[0]['sertifikat']);
        $file = public_path(). "/download/".array_values($peserta)[$ids-1]['sertifikat'];
          
        return Response::download($file, 'filename.pdf');
        
    }

    //menampilkan event yang baru didaftar
    public function showEventRegister(){
        $auth = Auth::user()->peserta;
        $id = $auth->id_peserta;

        $peserta = PesertaEvent::with(['event.panitia','event.detail_event'])
                   ->where( 'id_status', '=', 5)
                   ->where( 'id_peserta','=', $id)
                ->get();
                if(sizeof($peserta) > 0){
                    return response()->json([
                        'status' => 'Success',
                        'size' => sizeof($peserta),
                        'data' => [
                            'event' => $peserta->toArray()
                        ],
                    ]);
                }else if(sizeof($peserta) == 0){
                    return response()->json([
                        'status' => 'Success',
                        'size' => sizeof($peserta),
                        'data' => [
                            'event' => $peserta->toArray()
                        ],
                    ],200);
                }else{
                    return response(['Belum ada peserta'],404);
                }
    }

    //menampilkan event yang telah terdaftar
    public function showEventRegistered(){
        $auth = Auth::user()->peserta;
        $id = $auth->id_peserta;

        $peserta = PesertaEvent::with(['event.panitia','event.detail_event'])
                   ->where( 'id_status', '=', 6)
                   ->where( 'id_peserta','=', $id)
                ->get();
                if(sizeof($peserta) > 0){
                    return response()->json([
                        'status' => 'Success',
                        'size' => sizeof($peserta),
                        'data' => [
                            'event' => $peserta->toArray()
                        ],
                    ]);
                }else if(sizeof($peserta) == 0){
                    return response()->json([
                        'status' => 'Success',
                        'size' => sizeof($peserta),
                        'data' => [
                            'event' => $peserta->toArray()
                        ],
                    ],200);
                }else{
                    return response(['Belum ada peserta'],404);
                }
    }

    //menampilkan event yg telah diikuti serta menampilkan sertifikatnya yang sudah ditandatangani
    public function showEventDone(){
        $auth = Auth::user()->peserta;
        $id = $auth->id_peserta;
        
        $peserta = PesertaEvent::with(['event.panitia','event.sertifikat','event.detail_event'])
                    // ->where('nama_sertifikat', '!=', null)
                    // ->where('nama_event', '!=', null)
                    ->where( 'id_status', '=', 1)
                    ->where( 'id_peserta','=', $id)
                    ->whereHas('event.sertifikat', function($q)  {
                        $q->where('id_event', '!=', null); 
                    })
                    ->get();
        if(sizeof($peserta) > 0){
            return response()->json([
                'status' => 'Success',
                'size' => sizeof($peserta),
                'data' => [
                    'event' => $peserta->toArray()
                ],
            ]);
        }else if(sizeof($peserta) == 0){
            return response()->json([
                'status' => 'Success',
                'size' => sizeof($peserta),
                'data' => [
                    'event' => $peserta->toArray()
                ],
            ],200);
        }else{
                return response(['Belum ada peserta'],404);
        }
    }

    //menampilkan profil peserta
    public function showProfile()
    {
        $auth = Auth::user()->peserta;
        $id = $auth->id_peserta;
        $peserta = User::with('peserta')
                    ->whereHas('peserta', function ($query) use($id){
                         $query->where('id_peserta', '=', $id);
                    })->get();

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
            ],200);
        }else{
                return response(['Belum ada peserta'],404);
        }
    }

    public function showSertifikat()
    {   
        $auth = Auth::user()->peserta;
        $sertifikat = PenandatanganSertifikat::with('sertifikat.event.panitia','status')->where([
            
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

    //menampilkan profil untuk halaman edit
    public function showEditProfile()
    {
         $auth = Auth::user();
         $id = $auth->id_users;
         $peserta = User::with(['peserta'])->findOrFail($id);
         return response()->json([
             'status' => 'Success',
             'data' => [
                'user' => $peserta->toArray()
             ],
        ]);
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
