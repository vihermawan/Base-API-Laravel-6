<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Notifications\PasswordResetRequest;
use App\Notifications\ConfirmSignerSuccess;
use App\Notifications\PasswordResetSuccess;
use App\User;
use App\SetPassword;

class PasswordResetController extends Controller
{
    /**
     * Create token password reset
     *
     * @param  [string] email
     * @return [string] message.
     */
    public function create(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
        ]);
        $user = User::where('email', $request->email)->first();

        if (!$user)
            return response()->json([
                'message' => 'We cant find a user with that e-mail address.'
            ], 404);
        $passwordReset = SetPassword::updateOrCreate(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => str_random(60)
             ]
        );
        $details = [
            'greeting' =>'Hallo',
            'body' => 'Anda mendapatkan email ini karena anda lupa password, silahkan klik tombol dibawah untuk mereset password anda',
            'actionText' => 'Reset Password',
        ];
        if ($user && $passwordReset)
            $user->notify(
                new PasswordResetRequest($passwordReset->token,$details)
            );
        return response()->json([
            'message' => 'We have e-mailed your password reset link!'
        ]);
    }
    /**
     * Find token password reset
     *
     * @param  [string] $token
     * @return [string] message
     * @return [json] passwordReset object
     */
    public function find($token)
    {
        $passwordReset = SetPassword::where('token', $token)
        ->first();
            if (!$passwordReset)
                return response()->json([
                'message' => 'This forgot password token is invalid.'
            ], 404);
            if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
                $passwordReset->delete();
                return response()->json([
                'message' => 'This forgot password token is invalid.'
            ], 404);
    }
         return response()->json($passwordReset);
    }
     /**
     * Reset password.
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @param  [string] token
     * @return [string] message
     * @return [json] user object
     */
    public function setPenandatangan(Request $request,$token)
    {
        $confirmSigner = SetPassword::where([
            ['token', $token]
        ])->first();
        if (!$confirmSigner)
            return response()->json([
                'message' => 'This set password token is invalid.'
            ], 404);
        $users = User::where('email', $confirmSigner->email)->first();
        
        if (!$users)
            return response()->json([
                'message' => 'We cant find a user with that e-mail address.'
            ], 404);
        $users->password = bcrypt($request->password);
        $users->save();
        $users->notify(new ConfirmSignerSuccess($confirmSigner));
        $confirmSigner->delete();
        return response()->json('Password anda berhasil dibuat');
    }

    public function reset(Request $request,$token)
    {
        $passwordReset = SetPassword::where([
            ['token', $token]
        ])->first();
        if (!$passwordReset)
            return response()->json([
                'message' => 'This set password token is invalid.'
            ], 404);
        $users = User::where('email', $passwordReset->email)->first();
        if (!$users)
            return response()->json([
                'message' => 'We cant find a user with that e-mail address.'
            ], 404);
        $users->password = bcrypt($request->password);
        $users->save();
        $users->notify(new PasswordResetSuccess($passwordReset));
        $passwordReset->delete();
        return response()->json('Password anda berhasil diubah');
    }
}