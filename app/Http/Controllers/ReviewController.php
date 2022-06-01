<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reviews =DB::table('reviews')->join('users','users.id','=','reviews.user_id')->select('select reviews.id,text, users.name as name ')->paginate(20);
        return view("review.index",["reviews"=>$reviews]);
    }
    public function indexN()
    {
        $input = $request->only(['name']);

        $validate_data = [
            'novel' => 'required|string'
        ];

        //Se comprueba si los datos son validos
        $validator = Validator::make($input, $validate_data);

        if (!$validator->fails()){
            $reviews =DB::table('reviews')->join('users','users.id','=','reviews.user_id')
            ->join('novels','novels.id','=','reviews.novel_id')
            ->where('novels.title','LIKE','%'.$input['novel'].'%')->select('select reviews.id,text, users.name as name ')->paginate(20);
            return view("review.index",["reviews"=>$reviews]);
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\review  $review
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $input = $request->only(['id']);
        $delete=Review::find($input['id']);
        $delete->delete();
        return redirect()->route("review.index");
    }
}
