<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Novel;
use App\Models\Genre;
use App\Models\Chapter;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class NovelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($indice)
    {
        $novels=Novel::join('users','novels.user_id','=','users.id')
        ->get(['novels.id','novels.title','novels.description','novels.starts','novels.votes','novels.portada','users.name as creator']);

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

    public function indexName($name,$indice)
    {
        $name=str_replace('%20','',$name);
        $novels=Novel::join('users','novels.user_id','=','users.id')->where('novels.title','LIKE','%'.$name.'%')
        ->get(['novels.id','novels.title','novels.description','novels.starts','novels.votes','novels.portada','users.name as creator']);

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

    public function Genres(){
        $genres=Genre::all();

        return response()->json([
            'success' => true,
            'message' => 'Tags Data fetched successfully.',
            'data' => $genres
        ], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexGenre($genre,$indice)
    {
        $novels=Novel::join('novel_genre','novel_genre.novel_id','=','novels.id')
            ->join('genres','genres.id','=','novel_genre.genre_id')
            ->join('users','novels.user_id','=','users.id')
            ->where('genre.id','=',$genre)
            ->get(['novels.id','novels.title','novels.description','novels.starts','novels.votes','novels.portada','users.name']);

        if(!$novels){
            return response()->json([
                'success' => true,
                'message' => 'Novels Not Found'
            ], 404);
        }

        $result = new LengthAwarePaginator($novels->slice($indice, 20), $novels->count(),20, $indice);

        return response()->json([
            'success' => true,
            'message' => 'Novels Data fetched successfully.',
            'data' => $result
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->only(['title', 'description', 'portada','genres']);

        $validate_data = [
            'title' => 'required|string|min:4',
            'description' => 'required|string|min:20'
        ];

        //Se comprueba si los datos son validos
        $validator = Validator::make($input, $validate_data);

        if ($validator->fails() || !NovelController::checkBase64($input['portada'])) {
            return response()->json([
                'success' => false,
                'message' => 'Please see errors parameter for all errors.',
                'errors' => $validator->errors()
            ]);
        }

        $novel=Novel::create([
            'title'=>$input['title'],
            'description'=>$input['description'],
            'portada'=>$input['portada'],
            'user_id'=>auth()->user()->id]
        );
        if($novel){
            return response()->json([
                'success' => true,
                'message' => 'Novels Create successfully.',
            ], 200);
        }
        else{
            return response()->json([
                'success' => true,
                'message' => 'Novels Create successfully.',
            ], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  $id -> id of novel
     * @return json response
     */
    public function show($id)
    {
        $novel=Novel::join('users','novels.user_id','=','users.id')
        ->where('novels.id','=',$id)
        ->get(['novels.id','novels.title','novels.description','novels.starts','novels.votes','novels.portada','users.name as creator',]);

        $genres=Genre::join('novel_genre','novel_genre.genre_id','=','genres.id')
        ->where('novel_genre.novel_id','=',$id)
        ->get(['genres.name']);

        return response()->json([
            'success' => true,
            'message' => 'Novel Data fetched successfully.',
            'data' => [$novel,$genres]
        ], 200);
    }

    public function getChapters($id,$indice){
        $chapters=Chapter::where('chapters.novel_id','=',$id,'and','is_publish','=','true')
            ->get(['number','name']);

        if(!$chapters){
            return response()->json([
                'success' => true,
                'message' => 'Chapters Not Found'
            ], 404);
        }

        $result = new LengthAwarePaginator($chapters->slice($indice-1, 100), $chapters->count(),100, $indice);

        return response()->json([
            'success' => true,
            'message' => 'Novel Chapters Data fetched successfully.',
            'data' => $result
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Novel  $novel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $input = $request->only(['id','name', 'description', 'portada']);

        $validate_data = [
            'title' => 'required|string|min:4',
            'description' => 'required|string|min:20'
        ];

        //Se comprueba si los datos son validos
        $validator = Validator::make($input, $validate_data);

        if ($validator->fails() || !checkBase64($input['portada'])) {
            return response()->json([
                'success' => false,
                'message' => 'Please see errors parameter for all errors.',
                'errors' => $validator->errors()
            ]);
        }

        $novel=Novel::find($input['id']);
        if($novel->user_id == Auth::user()->id){
            $novel->title=$input['title'];
            $novel->description=$input['description'];
            $novel->portada=$input['portada'];
            $novel->save();
            return response()->json([
                'success' => true,
                'message' => 'Novel Update successfully.'
            ], 200);
        }
        else{
            return response()->json([
                'success' => false,
                'message' => 'User authentication failed.'
            ], 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Novel  $novel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $novel=Novel::find($id);
        if($novel->user_id == auth()->user()->id){
            $novel->delete();
            return response()->json([
                'success' => true,
                'message' => 'Novel Delete successfully.'
            ], 200);
        }
        else{
            return response()->json([
                'success' => false,
                'message' => 'User authentication failed.'
            ], 401);
        }
    }

    public function addGenre($id,$genre){
        $novel=Novel::find($id);
        $genres=Genre::find($genre);
        if(!$novel || !$genres){
            return response()->json([
                'success' => false,
                'message' => 'Data Not Found'
            ], 404);
        }
        if($novel->user_id == auth()->user()->id){
            $add=DB::table('novel_genre')->insert(['novel_id' => $id,'genre_id' => $genre]);
                return response()->json([
                    'success' => true,
                    'message' => 'Add Genre successfully.'
                ], 200);
        }
        else{
            return response()->json([
                'success' => false,
                'message' => 'User authentication failed.'
            ], 401);
        }
    }

    public function vote($id,$starts){
        $novel=Novel::find($id);
        if(!$novel){
            return response()->json([
                'success' => false,
                'message' => 'Data Not Found'
            ], 404);
        }
        else if($starts>5){
            return response()->json([
                'success' => false,
                'message' => 'BAD REQUEST'
            ], 400);
        }
        else{
            $novel->votes=($novel->votes ) + 1;
            $novel->starts=($novel->starts ) + $starts;
            $novel->save();
            return response()->json([
                'success' => true,
                'message' => 'Add Vote successfully.'
            ], 200);
        }
    }
    public function coop($id, Request $request){
        $novel=Novel::find($id);
        if(!$novel){
            return response()->json([
                'success' => false,
                'message' => 'Data Not Found'
            ], 404);
        }
        $input = $request->only(['message']);

        $validate_data = [
            'message' => 'required|string|min:20'
        ];

        //Se comprueba si los datos son validos
        $validator = Validator::make($input, $validate_data);

        if ($validator->fails() || !checkBase64($input['portada'])) {
            return response()->json([
                'success' => false,
                'message' => 'Please see errors parameter for all errors.',
                'errors' => $validator->errors()
            ]);
        }
        $title='Peticion de coolaboracion';
        $description='El usuario '.Auth::user()->name.' quiere colaborar con tu novela';
        $action='acion:addcoop,person:'.Auth::user()->id.',novel:'.$novel->id;
        $peticion=Notification::create(
            'title'->$title,
            'description'->$description,
            'action'->$action,
            'message'->$input['message'],
            'user_id'->$novel->user_id
        );
        return response()->json([
            'success' => true,
            'message' => 'Add Coop Peticion successfully.'
        ], 200);
    }
    public function report($indice){
        $novel=Novel::find($indice);
        if(!$novel){
            return response()->json([
                'success' => true,
                'message' => 'Data No found.'
            ], 404);
        }
        $user_id=0;
        $title='Report';
        $description='El usuario '.Auth::user()->name.' a reportado una novela';
        $action='acion:report,novel:'.$indice.',user:'.$Auth::user()->id;
        $peticion=Notification::create(
            'title'->$title,
            'description'->$description,
            'action'->$action,
            'message'->$input['message'],
            'user_id'->$user_id
        );
        return response()->json([
            'success' => true,
            'message' => 'Novel Report successfully.'
        ], 200);
    }
}
