<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Notifications\TemplateEmail;
use App\Notifications\ConfirmSignerSuccess;
use App\User;
use App\ConfirmSigner;
use App\Penandatangan;

class ConfirmSignerController extends Controller
{
    /**
     * Find token set password
     *
     * @param  [string] $token
     * @return [string] message
     * @return [json] setPassword object
     */
    public function find($token)
    {
        $confirmSigner = ConfirmSigner::where('token', $token)
            ->first();
        if (!$confirmSigner)
            return response()->json([
                'message' => 'This set password token is invalid.'
            ], 404);
        if (Carbon::parse($confirmSigner->updated_at)->addMinutes(720)->isPast()) {
            $confirmSigner->delete();
            return response()->json([
                'message' => 'This set password token is invalid.'
            ], 404);
        }
        return response()->json($confirmSigner);
    }
     /**
     * Reset password
     *
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     * @return [json] user object
     */
    public function setPassword(Request $request,$token)
    {
        $request->validate([
            'password' => 'required|string|confirmed',
        ]);
        $confirmSigner = ConfirmSigner::where([
            ['token', $token]
        ])->first();
        if (!$confirmSigner)
            return response()->json([
                'message' => 'This set password token is invalid.'
            ], 404);
        $users = User::where('email', $confirmSigner->email)->first();
        $user = $users->toArray();

        if (!$users)
            return response()->json([
                'message' => 'We cant find a user with that e-mail address.'
            ], 404);
        $users->password = bcrypt($request->password);
        $users->save();

        $penandatangan = Penandatangan::where('id_users', $users->id_users)->first();
     
        $signer = $penandatangan->toArray();

        $confirmSigner->delete();
        $details = [
            'greeting' => 'Selamat '.array_values($signer)[0],
            'body' => 'Anda sudah terdaftar sebagai Penandatangan di EventIn. Silahkan klik tombol login dibawah untuk masuk ke akun anda',
            'actionText' => 'Login',
            'actionURL' => url('api/login'),
        ];
        $users->notify(new ConfirmSignerSuccess($details));
        return response()->json($user);
    }
}