<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comments = DB::select('select comments.id,text, users.name as name  from comments join users ON users.id=comments.user_id')->paginate(20);
        return view("comment.index",["comments"=>$comments]);
    }
    public function indexN()
    {
        $input = $request->only(['name']);

        $validate_data = [
            'novel' => 'required|string',
            'chapter'=>'required|integer'
        ];

        //Se comprueba si los datos son validos
        $validator = Validator::make($input, $validate_data);

        if (!$validator->fails()){
            $comments = DB::select("select comments.id,text, users.name as name from comments join users ON users.id=comments.user_id
            JOIN chapters ON chapters.id=comments.chapter_id JOIN novels ON novels.id=chapters.novel_id
            WHERE chapters.number=? AND novels.tittle LIKE '?'",[$input['chapter'],'%'.$input['novel'].'%'])->paginate(20);
            return view("comment.index",["comments"=>$comments]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $input = $request->only(['id']);
        $delete=Comment::find($input['id']);
        $delete->delete();
        return redirect()->route("comment.index");
    }
}
