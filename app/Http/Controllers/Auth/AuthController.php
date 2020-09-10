<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\User;
use DB;
use App\Panitia;
use App\Peserta;
use App\Penandatangan;
use Auth;

class AuthController extends Controller
{
    public function registerPeserta(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed',
            'nama_peserta' => 'required|string'
        ]);
        DB::beginTransaction();
        try{
            $users = new User;
            $users->email = $request->email;
            $users->password = bcrypt($request->password);
            $users->id_role=3;
            $users->save();
            
        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['status' => 'Register Failed', 'message' => $e->getMessage()]);
        }
        try{
            $peserta = new Peserta;
            $peserta->id_users = $users->id_users;
            $peserta->nama_peserta = $request->nama_peserta;
            $peserta->tanggal_lahir = $request->tanggal_lahir;
            $peserta->umur = $request->umur;
            $peserta->organisasi = $request->organisasi;
            $peserta->telepon = $request->telepon;
            $peserta->pekerjaan = $request->pekerjaan;
            $peserta->jenis_kelamin = $request->jenis_kelamin;
            $peserta->foto_peserta = 'avatar.png';
            // if($request->hasfile('foto_peserta')){
            //     $file = $request->file('foto_peserta');
            //     $extension = $file->getClientOriginalExtension();
            //     $filename = time() . '.' . $extension;
            //     $file->move('uploads/peserta/', $filename);
            //     $peserta->foto_peserta = $filename;
            // }
            $peserta->save();
        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['status' => 'Register Failed', 'message' => $e->getMessage()]);
        }
        DB::commit();
        
        return response()->json([
            'message' => 'Successfully created peserta!'
        ], 201);
    }
  
    public function registerPanitia(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed',
            'nama_panitia' => 'required|string'
        ]);
        DB::beginTransaction();
        try{
            $users = new User;
            $users->email = $request->email;
            $users->password = bcrypt($request->password);
            $users->id_role=2;
            $users->save();
        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['status' => 'Register Failed', 'message' => $e->getMessage()],422);
        }
        try{
            $panitia= new Panitia;
            $panitia->id_users = $users->id_users;
            $panitia->nama_panitia = $request->nama_panitia;
            $panitia->organisasi = $request->organisasi;
            $panitia->telepon = $request->telepon;
            $panitia->instagram = $request->instagram;
            $panitia->foto_panitia = 'avatar.png';
            // if($request->hasfile('foto_panitia')){
            //     $file = $request->file('foto_panitia');
            //     $extension = $file->getClientOriginalExtension();
            //     $filename = time() . '.' . $extension;
            //     $file->move('uploads/panitia/', $filename);
            //     $panitia->foto_panitia = $filename;
            // }
            $panitia->save();
        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['status' => 'Register Failed', 'message' => $e->getMessage()]);
        }
        DB::commit();
        
        return response()->json([
            'message' => 'Successfully created panitia!'
        ], 201);
    }
    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = request(['email', 'password']);
        if(Auth::attempt($credentials)){
           if(Auth::user()->isBan == 0){
                $user = $request->user();
                $tokenResult = $user->createToken('Personal Access Token');
                $token = $tokenResult->token;
                $token->save();
                
                if($user->id_role == 2){
                    $panitia = Panitia::where('id_users', '=', $user->id_users )->get();
                    $user = User::where('id_users', '=', $user->id_users )->get();
                    $users =$user->toArray();
                    $panitias=$panitia->toArray();
                    return response()->json([
                        'token' => $tokenResult->accessToken,
                        'id_role' =>array_values($users)[0]['id_role'],
                        'nama' => array_values($panitias)[0]['nama_panitia'],
                        'profile_picture'=>array_values($panitias)[0]['image_URL'],
                    ]);
                
                }else if($user->id_role == 3){
                    $peserta = Peserta::where('id_users', '=', $user->id_users )->get();
                    $user = User::where('id_users', '=', $user->id_users )->get();
                    $users =$user->toArray();
                    $pesertas=$peserta->toArray();
                    return response()->json([
                        'token' => $tokenResult->accessToken,
                        'id_role' =>array_values($users)[0]['id_role'],
                        'nama' =>array_values($pesertas)[0]['nama_peserta'],
                        'profile_picture'=>array_values($pesertas)[0]['image_URL'],
                    ]);
                }
                else if($user->id_role == 4){
                    $penandatangan = Penandatangan::where('id_users', '=', $user->id_users )->get();
                    $user = User::where('id_users', '=', $user->id_users )->get();
                    $users =$user->toArray();
                    $penandatangans=$penandatangan->toArray();
                    return response()->json([
                        'token' => $tokenResult->accessToken,
                        'id_role' =>array_values($users)[0]['id_role'],
                        'nama' =>array_values($penandatangans)[0]['nama_penandatangan'],
                        'profile_picture'=>array_values($penandatangans)[0]['image_URL'],
                    ]);
                }
                else if($user->id_role == 1){
                    return response()->json([
                        'id_role' => 1,
                        'token' => $tokenResult->accessToken,
                        'nama' => 'Admin'
                    ]);
                }
           }else if(Auth::user()->isBan == 1){
                return response()->json([
                    'status' => 'Banned',
                    'message' => 'Akun anda telah diblokir. Silahkan kirim email ke service.eventin@gmail.com jika menurut anda tidak selayaknya diblokir'
                ], 401);
           }
        }else{
            return response()->json([
                'message' => 'Email anda belum terdaftar, silahkan register terlebih dahulu'
            ], 401);
        }
    }
  
    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        // $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
  
    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}