<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
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

    public function update(Request $request){
        $input = $request->only(['avatar','email','fondo','aboutMe','password']);

        $validate_data = [
            'aboutMe' => 'string|min:1|max:60',
            'password' => 'nullable|string|min:8|max:20'
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
        else{
            $user = User::find(Auth::user()->id);
            $user->aboutMe=$input['aboutMe'];
            if(str_contains($input['avatar'],'data:image/png;base64,')||str_contains($input['avatar'],'data:image/jpeg;base64,')){
                $user->avatar=$input['avatar'];
            }
            if(str_contains($input['fondo'],'data:image/png;base64,')||str_contains($input['fondo'],'data:image/jpeg;base64,')){
                $user->fondo=$input['fondo'];
            }
            if(strlen($input['password'])!==0){
                $user->password=$input['password'];
            }
            $user->save();
            return response()->json([
                'success' => true,
                'message' => 'Porfile edit fetched successfully'
            ],200);
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
