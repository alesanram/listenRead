<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use Illuminate\Http\Request;

class ChapterController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->only(['id_novel','name','type','number','audio','text']);

        $validate_data = [
            'id_novel'=>'required|integer',
            'name' => 'required|string|min:4',
            'text' => 'string|min:20',
            'number'=>'required|integer'
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
        $novel=Novel::find($input['novel_id']);
        $permiso=DB::select('select * from user_novel where user_id= ? AND novel_id=?', [$novel->id,Auth::user()->id]);
        if($novel->user_id==Auth::user()->id ||count($permiso)==0){
            if($input['type']=='Text'){
                $chapter=Chapter::create([
                    'name'=>$input['name'],
                    'number'=>$input['number'],
                    'text'=>$input['text'],
                    'is_publish'=>false,
                    'type'=>'Text',
                    'text'=>$input['text'],
                    'novel_id'=>$novel->id,
                    'creator_id'=>Auth::user()->id,
                    'author_id'=>$novel->user_id
                ]);
            }
            else{
                if ($request->hasFile('audio') && $request->file('audio')->isValid()){
                    $audio=$request->file('audio')->storeAs("local/".Auth::user()->name, ('chapter_'.$input['number'].'_'.date().'.'. $request->file('audio')->extension()));
                    $url=Storage::url($audio);
                    $chapter=Chapter::create([
                        'name'=>$input['name'],
                        'number'=>$input['number'],
                        'text'=>$input['text'],
                        'is_publish'=>false,
                        'type'=>'Audio',
                        'rute'=>$url,
                        'novel_id'=>$novel->id,
                        'creator_id'=>Auth::user()->id,
                        'author_id'=>$novel->user_id
                    ]);
                }
            }
            return response()->json([
                'success' => true,
                'message' => 'Chapter Create successfully.',
                'data' => $chapters
            ], 200);
        }
        else{
            return response()->json([
                'success' => false,
                'message' => 'User authentication failed.'
            ], 401);
        }
    }
    public function publish($indice){
        $chapter=Chapter::find($indice);
        if(!$chapters){
            return response()->json([
                'success' => true,
                'message' => 'Chapters Not Found'
            ], 404);
        }
        if($chapter->author_id==Auth::user()->id){
            $chapter->is_publish=true;
            $chapter->date_publish=date('Y-m-d');
            $chapter->save();
            return response()->json([
                'success' => true,
                'message' => 'Chapter Publish successfully.'
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
     * Display the specified resource.
     *
     * @param  \App\Models\Chapter  $chapter
     * @return \Illuminate\Http\Response
     */
    public function show($indice)
    {
        $chapters=Chapter::find($indice);

        if(!$chapters){
            return response()->json([
                'success' => true,
                'message' => 'Chapters Not Found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Chapter Data fetched successfully.',
            'data' => $chapters
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Chapter  $chapter
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $input = $request->only(['id','name', 'number','audio','text']);

        $validate_data = [
            'id'=>'required|integer',
            'name' => 'required|string|min:4',
            'text' => 'string|min:20',
            'number'=>'required|integer'
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
        $chapter=Chapter::find($input['id']);
        if($chapter->creator_id !=Auth::user()->id){
            return response()->json([
                'success' => false,
                'message' => 'User authentication failed.'
            ], 401);
        }
        if($input['type']=='Text'){
            $chapter->name=$input['name'];
            $chapter->number=$input['number'];
            $chapter->text=$input['text'];
            $chapter->save();
        }
        else{
            if ($request->hasFile('audio') && $request->file('audio')->isValid()){
                $audio=$request->file('audio')->storeAs("local/".Auth::user()->name, ('chapter_'.$input['number'].'_'.date().'.'. $request->file('audio')->extension()));
                $url=Storage::url($audio);
                Storage::delete($chapter->rute);
            }
            else{
                $url=$chapter->rute;
            }
            $chapter->name=$input['name'];
            $chapter->number=$input['number'];
            $chapter->rute=$url;
            $chapter->save();
        }
        return response()->json([
            'success' => true,
            'message' => 'Chapter Update successfully.'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Chapter  $chapter
     * @return \Illuminate\Http\Response
     */
    public function destroy($indice)
    {
        $chapter=Chapter::find($indice);
        if($chapter->creator_id !=Auth::user()->id || $chapter->author_id !=Auth::user()->id){
            return response()->json([
                'success' => false,
                'message' => 'User authentication failed.'
            ], 401);
        }
        else{
            $chapter->delete();
            return response()->json([
                'success' => true,
                'message' => 'Chapter Delete successfully.'
            ], 200);
        }
    }
}
