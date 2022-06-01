<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Novel;
use App\Models\Chapter;
use App\Models\Notification;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($indice)
    {
        $users=User::get(['name','avatar','aboutMe']);

        if(!$users){
            return response()->json([
                'success' => true,
                'message' => 'users Not Found'
            ], 404);
        }

        $result = new LengthAwarePaginator($users->slice($indice-1, 20), $users->count(),20, $indice);
        return response()->json([
            'success' => true,
            'message' => 'Users Data fetched successfully.',
            'pagination' => $result
        ], 200);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexName($indice,$usernme)
    {
        $users=User::where('name','LIKE','%'.$username.'%')->get(['name','avatar','aboutMe']);

        if(!$users){
            return response()->json([
                'success' => true,
                'message' => 'users Not Found'
            ], 404);
        }

        $result = new LengthAwarePaginator($users->slice($indice-1, 20), $users->count(),20, $indice);
        return response()->json([
            'success' => true,
            'message' => 'Users Data fetched successfully.',
            'pagination' => $result
        ], 200);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getNovels()
    {
        $novels=Novel::where('user_id','=',Auth::user()->id);

        if(!$novels){
            return response()->json([
                'success' => true,
                'message' => 'Novels Not Found'
            ], 404);
        }

        $result = new LengthAwarePaginator($novels->slice($indice-1, 20), $novels->count(),20, $indice);
        return response()->json([
            'success' => true,
            'message' => 'Novels Data fetched successfully.',
            'pagination' => $result
        ], 200);
    }

    public function getMessage()
    {
        $mesages=Notification::where('user_id','=',Auth::user()->id);

        if(!$messages){
            return response()->json([
                'success' => true,
                'message' => 'Notifications Not Found'
            ], 404);
        }

        $result = new LengthAwarePaginator($messages->slice($indice-1, 20), $mesages->count(),20, $indice);
        return response()->json([
            'success' => true,
            'message' => 'Notifications Data fetched successfully.',
            'pagination' => $result
        ], 200);
    }

    public function getNovelsCoo()
    {
        $novels=Novel::join('user_novel','user_novel.novel_id','=','novels.id')->where('user_novel.user_id','=',Auth::user()->id)->get('novels.*');

        if(!$novels){
            return response()->json([
                'success' => true,
                'message' => 'Novels Not Found'
            ], 404);
        }

        $result = new LengthAwarePaginator($novels->slice($indice-1, 20), $novels->count(),20, $indice);
        return response()->json([
            'success' => true,
            'message' => 'Novels Data fetched successfully.',
            'pagination' => $result
        ], 200);
    }

    public function getChapterCoo($id)
    {
        $chapters=Chapter::where('creator_id','=',Auth::user()->id)->where('novel_id','=',$id)->get(['id','name','number']);

        if(!$chapters){
            return response()->json([
                'success' => true,
                'message' => 'Chapters Not Found'
            ], 404);
        }

        $result = new LengthAwarePaginator($chapters->slice($indice-1, 20), $chapters->count(),20, $indice);
        return response()->json([
            'success' => true,
            'message' => 'chapters Data fetched successfully.',
            'pagination' => $result
        ], 200);
    }

    function checkBase64($base64) {
        if($base64){
            $img = imagecreatefromstring(base64_decode($base64));
        if (!$img) {
            return false;
        }

        imagepng($img, 'tmp.png');
        $info = getimagesize('tmp.png');

        unlink('tmp.png');

        if ($info[0] > 0 && $info[1] > 0 && $info['mime']) {
            return true;
        }

        return false;
        }
        else{
            return true;
        }
    }

    public function update(){
        $input = $request->only(['avatar','fondo','aboutMe','password']);

        $validate_data = [
            'aboutMe' => 'string|min:1|max:60',
            'password' => 'string|min:8|max:20'
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
        else if(User::checkBase64($input['fondo']) || User::checkBase64($input['avatar'])){
            return response()->json([
                'success' => false,
                'message' => 'Please see errors parameter for all errors.'
            ],201);
        }
        else{
            $user = User::find(Auth::user()->id);
            $user->aboutMe=$input['aboutMe'];
            $user->avatar=$input['avatar'];
            $user->fonfo=$input['fondo'];
            $user->password=$input['password'];
            $user->save();
            return response()->json([
                'success' => true,
                'message' => 'Porfile edit fetched successfully'
            ],200);
        }

    }

    public function AceptCoo($id){
        $notification=Notification::find($id);
        if(!$notification){
            return response()->json([
                'success' => false,
                'message' => 'User authentication failed.'
            ], 401);
        }
        else if($notification->user_id != Auth::user()->id){
            return response()->json([
                'success' => false,
                'message' => 'Notification Not Found'
            ], 404);
        }
        else{
            $action=explode(',',$notification->action);
            $peticion=explode(':',$action[0]);
            if($peticion[1]=='addcoop'){
                $coop=explode(':',$action[1]);
                $coop=$coop[1];
                $novel=explode(':',$action[2]);
                $novel=$novel[1];
                $coobolador=DB::insert('insert into user_novel (id_user, id_novel) values (?, ?)', [$coop, $novel]);
                //Crear Mensaje
                require base_path("vendor/autoload.php");
                $mail=New PHPMailer(true);
                $mail->SMTPDebug = 0;
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'ListenReadWeb@gmail.com';
                $mail->Password = 'e&dxI5a4#cethFqoYqxMF4NnAR@#^P';
                $mail->Port = 587;
                //Enviar Mensaje

            }
        }
    }

    public function porfile($id){
        $user=User::find($indice)->get(['name','avatar','fondo']);
        $novels=DB::select('select count(*) as total from novles where user_id= ?', [Auth::user()->id]);
        if($user){
            return response()->json([
                'success' => true,
                'message' => 'Porfile edit fetched successfully',
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
    public function CloseCount(){
        $user_id=0;
        $title='close of count';
        $description='El usuario '.Auth::user()->name.'quere cerrar su cuenta';
        $action='acion:close,user:'.$Auth::user()->id.',dia:'.date();
        $peticion=Notification::create(
            'title'->$title,
            'description'->$description,
            'action'->$action,
            'message'->$title,
            'user_id'->$user_id
        );
        return response()->json([
            'success' => true,
            'message' => 'Close Peticion successfully.'
        ], 200);
    }
}
