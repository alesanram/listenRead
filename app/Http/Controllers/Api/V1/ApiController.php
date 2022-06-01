<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller{
    //Login de usuario, respuesta en json

    public function login(Request $request){
        $input = $request->only(['name', 'password']);

        $validate_data = [
            'name' => 'required|string|min:4',
            'password' => 'required|min:8',
        ];

        $validator = Validator::make($input, $validate_data);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Please see errors parameter for all errors.'
            ],401);
        }

        // authentication attempt
        if (Auth::attempt($input)){
            if(Auth::user()->block > time()){
                Auth::logout();
                return response()->json([
                    'success' => false,
                    'message' => 'User is Blocked.'
                ], 200);
            }
            else{
                $token = Auth::user()->createToken('listen&readToken')->accessToken;
                return response()->json([
                    'success' => true,
                    'message' => 'User login succesfully, Use token to authenticate.',
                    'token' => $token
                ], 200);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User authentication failed.'
            ], 401);
        }
    }

    //Registro de usuario
    public function register(Request $request)
    {
        $input = $request->only(['name', 'email', 'password','password_re','politics']);
        $validate_data = [
            'name' => 'required|string|min:4|max:20',
            'email' => 'required|email',
            'password' => 'required|min:8|max:20',
            'password_re' => 'required|min:8|max:20'
        ];

        //Se comprueba si los datos son validos
        $validator = Validator::make($input, $validate_data);

        if ($validator->fails()||$input['password']!=$input['password_re']||$input['politics']==false) {
            return response()->json([
                'success' => false,
                'message' => 'Please see errors parameter for all errors.',
                'errors' => $validator->errors()
            ]);
        }
        $role=2;
        //Se crea usuario
        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'role_id'=>$role
        ]);
        //Se autoriza usuario
        $token = $user->createToken('listen&readToken')->accessToken;
            return response()->json([
                'success' => true,
                'message' => 'User Register succesfully, Use token to authenticate.',
                'token' => $token
            ], 200);
    }

    /**
     * Logout user.
     *
     * @return json
     */
    public function logout()
    {
        $user = Auth::user()->token();
        $user->revoke();
        if($user->revoke()){
            return response()->json([
                'success' => true,
                'message' => 'User logout successfully.'
            ], 200);
        }
        else{
            return response()->json([
                'success' => false,
                'message' => 'User can not logout .'
            ], 401);
        }

    }

    public function getUserDetail(){
        if(Auth::user()->id){
            return response()->json([
                'success' => true,
                'message' => 'Data fetched successfully.',
                'data' => ['id'=>Auth::user()->id,'name'=>Auth::user()->name, 'avatar'=>Auth::user()->avatar]
            ], 200);
        }
        else{
            return response()->json([
                'success' => false,
                'message' => 'User not Found',
            ], 401);
        }
    }
    public function sendMessage(Request $request){
        $input = $request->only(['email','asunto','text']);
        $validate_data = [
            'email' => 'required|email',
            'asunto'=>'required|min:8|max:20',
            'text' => 'required|min:8|max:100'
        ];

        //Se comprueba si los datos son validos
        $validator = Validator::make($input, $validate_data);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please see errors parameter for all errors.',
                'errors' => $validator->errors()
            ]);
        }
        require base_path("vendor/autoload.php");

        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPDebug = 0;
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPSecure = 'tls';
        $mail->SMTPAuth = true;
        $mail->Username = "ListenReadWeb@gmail.com";
        $mail->Password = "e&dxI5a4#cethFqoYqxMF4NnAR@#^P";
        $mail->SetFrom('ListenReadWeb@gmail.com', 'Listen&Read');
        $mail->AddAddress(''.$input['email'].'');
        $mail->Subject = ''.$input['asunto'];
        $mail->AltBody = ''.$input['text'];
        if(!$mail->Send()) {
            return response()->json([
                'success' => false,
                'message' => 'Email failled to send'
            ], 401);
        }
        else {
            return response()->json([
                'success' => true,
                'message' => 'Email send'
            ], 200);
        }
    }

    public function myPorfile(){
        $user=User::find(Auth::user()->id)->join('roles','roles.id','=','users.role_id')->get(['users.name','users.email','avatar','aboutMe','roles.name as role','fondo','users.created_at']);
        $novels=DB::select('select count(*) as total from novels where user_id= ?', [Auth::user()->id]);
        if($user){
            return response()->json([
                'success' => true,
                'message' => 'Porfile data fetched successfully',
                'data'=>[$user,$novels]
            ],200);
        }
        else{
            return response()->json([
                'success' => false,
                'message' => 'Data no found'
            ],404);
        }
    }
}
