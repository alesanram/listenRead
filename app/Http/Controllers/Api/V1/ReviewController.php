<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Tag;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($novel_id,$indice)
    {
        $reviews=Review::join('users','rewiews.user_id','=','users.id')->where('reviews.novel_id','=',$novel_id)
        ->get(['reviews.title','reviews.text','reviews.starts','user.avatar','users.name']);

        if(!$reviews){
            return response()->json([
                'success' => true,
                'message' => 'Reviews Not Found'
            ], 404);
        }
        $array=[];
        for($i=0;$i < count($reviews);$i++){
            $tags=Tag::join('review_tag','reviews.id','=','review_tag.review_id')->where('review_tag','=',$reviews[$i]->id)->get(['reviews.id','reviews.name']);
            array_push($array,[$reviews[$i],$tags]);
        }

        $result = new LengthAwarePaginator($reviews->slice($indice-1, 20), $reviews->count(),20, $indice);
        return response()->json([
            'success' => true,
            'message' => 'Reviews Data fetched successfully.',
            'pagination' => $result
        ], 200);
    }

    public function tags(){
        $tags=Tag::all();

        return response()->json([
            'success' => true,
            'message' => 'Tags Data fetched successfully.',
            'data' => $tags
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
        $input = $request->only(['text']);

        $validate_data = [
            'title' => 'required|string|min:4',
            'text' => 'required|string|min:20',
            "tags"    => "required|array|min:0",
            "tags.*"  => "required|integer|distinct",
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

        $review=Review::create([
            'title'=>$input['title'],
            'text'=>$input['text'],
            'start'=>$input['start'],
            'novel_id'=>$input['novel_id'],
            'user_id'=>auth()->user()->id]
        );
        if($reviews){
            for($i=0;$i <count($input['tags']);$i++){
                $tag=DB::insert('insert into review_tag (id_review, id_tag) values (?, ?)', [$review->id, $input['tags'][$i]]);
                if(!$tag){
                    return response()->json([
                        'success' => true,
                        'message' => 'Error to add Tags',
                    ], 500);
                }
            }
            return response()->json([
                    'success' => true,
                    'message' => 'Reviews Create successfully.',
                ], 201);
        }
        else{
            return response()->json([
                'success' => true,
                'message' => 'Error to Create Reviews',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $review=Review::find($id);
        if($review->user_id == auth()->user()->id){
            $review->delete();
            return response()->json([
                'success' => true,
                'message' => 'Review Delete successfully.'
            ], 200);
        }
        else{
            return response()->json([
                'success' => false,
                'message' => 'User authentication failed.'
            ], 401);
        }
    }

    public function report($indice){
        $review=Review::find($indice);
        if(!$review){
            return response()->json([
                'success' => true,
                'message' => 'Data No found.'
            ], 404);
        }
        $user_id=0;
        $title='Report';
        $description='El usuario '.Auth::user()->name.' a reportado una reseña';
        $action='acion:report,review:'.$indice.',user:'.$Auth::user()->id;
        $peticion=Notification::create(
            'title'->$title,
            'description'->$description,
            'action'->$action,
            'message'->$input['message'],
            'user_id'->$user_id
        );
        return response()->json([
            'success' => true,
            'message' => 'Review Report successfully.'
        ], 200);
    }
}
