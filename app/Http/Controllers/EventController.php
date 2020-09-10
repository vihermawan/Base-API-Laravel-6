<?php

namespace App\Http\Controllers;
use App\Event;
use App\User;
use App\DetailEvent;
use App\PesertaEvent;
use File;
use Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    //menampilkan semua data event sesuai orang yang login

    public function index(){
        $event=[
            'label' => "kategori", // Table column heading
            'type' => "select",
            'name' => 'id_kategori', // the column that contains the ID of that connected entity;
            'entity' => 'kategori', // the method that defines the relationship in your Model
            'attribute' => "nama_kategori", // foreign key attribute that is shown to user
            'model' => "App\Models\DetailEvent", // foreign key model
        ];
        $this->crud->setColumnDetails('kategori', $event);
        // $auth = Auth::user()->panitia;
        // $event = Event::with(['detail_event'])->where( 'id_panitia', '=', $auth->id_panitia)->get();
        //     if(sizeof($event) > 0){
        //         return response()->json([
        //             'status' => 'Success',
        //             'size' => sizeof($event),
        //             'data' => [
        //                 'kategori' => $event->toArray()
        //             ],
        //         ],200);
        //     }else{
        //         return response(['Belum ada event'],404);
        //     }
    }

    //Menampilkan data event A
    public function show($id)
    {   
        $event = Event::with(['detail_event'])->findOrFail($id);
        
            return response()->json([
                'status' => 'Success',
                'data' => [
                    'event' => $event->toArray()
                ],
            ],200);
    }

    //Menampilkan daftar peserta dari event A
    public function showPesertabyEvent($id_event){
        $peserta = PesertaEvent::with(['peserta.users'])->where('id_event','=',$id_event)->get();
        if(sizeof($peserta) > 0)
            return response()->json(['Peserta', $peserta]);
        else{
            return response(['Event belum memiliki peserta!'], 404);
        }
    }
    
    //Menampilkan jumlah peserta dalam suatu event
    public function countPesertabyEvent($id_event){
        $peserta = PesertaEvent::with('peserta')->where('id_event','=',$id_event)->get();
        $pesertas = $peserta->count();
        return response()->json(['Jumlah Peserta : ', $pesertas]);
    }
  
    //membuat event baru
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'deskripsi_event' => 'required',
            'audien'=> 'required',
            'open_registration' => 'required',
            'end_registration' => 'required',
            'start_event' => 'required',
            'end_event' => 'required',
            'time_start' => 'required',
            'time_end' => 'required',
            'limit_participant' => 'required',
            'lokasi' => 'required',
            'venue' => 'required',
            'picture' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        if ($validator->fails()) {    
            return response()->json($validator->messages(), 422);
        }
        $detailevent = new DetailEvent;
        $detailevent->id_kategori = $request->id_kategori;
        $detailevent->deskripsi_event = $request->deskripsi_event;
        $detailevent->audien=$request->audien;
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
        $detailevent->save();

        $e = DB::table('detail_event')->get();
        $auth = Auth::user()->panitia;
        foreach($e as $data){
            $event = new Event;
            $event->id_detail_event = $data->id_detail_event;
        }
        $event->id_status = $request->id_status;
        $event->id_panitia = $auth->id_panitia;
        $event->nama_event = $request->nama_event;
        $event->organisasi = $request->organisasi;

        $event->save();
        
        return response()->json([$detailevent,$event], 201);
    }

    //Mengedit suatu event
    public function update($id, Request $request)
    {
        $event = Event::findOrFail($id);
        $detailevent = DetailEvent::findOrFail($id);
        
        $event->update($request->all());
        $detailevent->update($request->all());
        if($request->hasfile('picture')){
            //Membuat nama file random dengan extension
            $filename = null;
            $uploaded_picture = $request->picture;
            $extension = $uploaded_picture->getClientOriginalExtension();
            //Membuat nama file random dengan extension
            $filename = time() . '.' . $extension;
            $uploaded_picture->move('uploads/event/', $filename);

            if($detailevent->picture){
                $old_foto = $detailevent->picture;
                $filepath = public_path().DIRECTORY_SEPARATOR.'uploads/event/'.DIRECTORY_SEPARATOR.$old_foto;
                try{
                    File::delete($filepath);
                }
                catch(FileNotFoundException $e){

                }
            }
            $detailevent->picture = $filename;
            $detailevent->save();
        }
        return response()->json([$event,$detailevent], 200);
    }
    
    //Menghapus suatu event
    public function delete($id)
    {
        Event::findOrFail($id)->delete();
        DetailEvent::findorFail($id)->delete();
        return response('Deleted Successfully', 200);
    }    


    //Get Event sesuai kategori
    public function getEvent($id_kategori){
        $event = Event::with(['detail_event'])
        ->whereHas('detail_event', function($q) use($id_kategori) {
            $q->where('id_kategori', '=', $id_kategori); 
        })
        ->get();
        if(sizeof($event) > 0)
            return response()->json(['Event', $event]);
        else{
            return response(['Tidak ada event!'], 404);
        }
    }

    //Ubah status otomatis
    public function changeStatus(){
        $datenow = Carbon::now();   
        $event = DB::table('detail_event')
            ->select('detail_event.*')
            ->where('end_event','>=',$datenow)
            ->update(['id_status' => 2]);
        
        return response()->json($event);
    }

    //get event yang masih sudah lewat
    public function getEventinDate(){
        $datenow = Carbon::now();  
        $event = Event::with(['detail_event'])
                ->whereHas('detail_event', function($q) use($datenow) {
                    $q->where('end_event', '<=', $datenow); 
                })
                ->get();
        if(sizeof($event) > 0)
            return response()->json(['Event', $event]);
        else{
            return response(['Tidak ada event!'], 404);
        }
    }
    
    

}
