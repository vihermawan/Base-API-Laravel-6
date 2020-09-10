<?php

namespace App\Http\Controllers;
use App\PesertaEvent;
use App\Peserta;
use App\User;
use App\Event;
use App\DetailEvent;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use App\Notifications\PesertaRegisteredEvent;
class PesertaEventController extends Controller
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
        $peserta_event = PesertaEvent::with('peserta','event')->get();
        // return response()->json(['Peserta', $peserta_event]);
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


    public function show($id_peserta_event)
    {   
        $peserta_event = User::with('peserta','peserta.peserta_event','peserta.peserta_event.event')->whereHas('peserta.peserta_event',function($f) use($id_peserta_event) {
            $f->where('id_peserta_event','=',$id_peserta_event);
        })->get();
            return response()->json([
                'status' => 'Success',
                'data' => [
                'peserta_event' =>  $peserta_event->toArray()
                ]
            ]);
    }

    // public function showEvent ($id){
    //     $event = DB::table('peserta_event')
    //     ->join('event', 'peserta.id_event', '=', 'event.id_peserta_event')
    //     ->
    //     ->join('orders', 'users.id', '=', 'orders.user_id')
    //     ->select('users.*', 'contacts.phone', 'orders.price')
    //     ->get();
    // }

    public function create(Request $request)
    {
        $peserta_event = PesertaEvent::create($request->all());

        return response()->json($peserta_event, 201);
    }

    public function update($id, Request $request)
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
            'body' => 'Anda telah terdaftar pada Event '.array_values($events)[4],
            'thank' => 'Jangan lupa datang pada tanggal '.array_values($detailevent)[6],
        ];
    

        $peserta_event->update($request->all());
        if($peserta_event->id_status == 4)
        $user->notify(
            new PesertaRegisteredEvent($details)
        );
        return response()->json('Pemberitahuan email ke peserta sudah dikirim');
    }
    
    public function delete($id)
    {
        PesertaEvent::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }    
}
