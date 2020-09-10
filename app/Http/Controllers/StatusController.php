<?php

namespace App\Http\Controllers;
use App\Status;
use Illuminate\Http\Request;

class StatusController extends Controller
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

    public function show($id)
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

    public function create(Request $request)
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

    public function update($id, Request $request)
    {
        $status = Status::findOrFail($id);
        $status->update($request->all());

        return response()->json($status, 200);
    }
    
    public function delete($id)
    {
        Status::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }    
}
