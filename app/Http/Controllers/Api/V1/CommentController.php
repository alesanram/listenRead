<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id_chapter,$indice)
    {
        $comments=Comment::join('users','chapter.user_id','=','users.id')->where('comments.chapter_id','=',$id_chapter)
        ->get(['comments.text','user.avatar','users.name']);

        if(!$comments){
            return response()->json([
                'success' => true,
                'message' => 'comments Not Found'
            ], 404);
        }

        $result = new LengthAwarePaginator($comments->slice($indice-1, 20), $comments->count(),20, $indice);
        return response()->json([
            'success' => true,
            'message' => 'comments Data fetched successfully.',
            'pagination' => $result
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->only(['text','chapter_id']);

        $validate_data = [
            'text' => 'required|string|min:1',
            'chapter_id' =>'required|integer'
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

        $comment=Comment::create([
            'text'=>$input['text'],
            'chapter_id'=>$input['chapter_id'],
            'user_id'=>auth()->user()->id]
        );
        if($comment){
            return response()->json([
                'success' => true,
                'message' => 'Comment Create successfully.',
            ], 201);
        }
        else{
            return response()->json([
                'success' => true,
                'message' => 'Error to Create Comment',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $comment=Comment::find($id);
        if($comment->user_id == auth()->user()->id){
            $comment->delete();
            return response()->json([
                'success' => true,
                'message' => 'Comment Delete successfully.'
            ], 200);
        }
        else{
            return response()->json([
                'success' => false,
                'message' => 'User authentication failed.'
            ], 401);
        }
    }
    public function report(Request $request){
        $comment=Comment::find($indice);
        if(!$comment){
            return response()->json([
                'success' => true,
                'message' => 'Data No found.'
            ], 404);
        }
        $user_id=0;
        $title='Report';
        $description='El usuario '.Auth::user()->name.' a reportado un commentario';
        $action='acion:report,comment:'.$indice.',user:'.$Auth::user()->id;
        $peticion=Notification::create(
            'title'->$title,
            'description'->$description,
            'action'->$action,
            'message'->$title,
            'user_id'->$user_id
        );
        return response()->json([
            'success' => true,
            'message' => 'Comment Report successfully.'
        ], 200);
    }
}
