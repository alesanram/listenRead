<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = Tag::paginate(20);
        return view("tag.index",["tags"=>$tags]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("tag.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->only(['name']);

        $validate_data = [
            'name' => 'required|string|min:4'
        ];

        //Se comprueba si los datos son validos
        $validator = Validator::make($input, $validate_data);

        if (!$validator->fails()){
            Tag::create([
                'name'=>$input['name']
            ]);
        }
        return redirect('/tag');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $input = $request->only(['id']);

        $validate_data = [
            'id' => 'required|integer'
        ];

        //Se comprueba si los datos son validos
        $validator = Validator::make($input, $validate_data);
        if (!$validator->fails()){
            $tag=Tag::find($input['id']);
            return view("tag.update", ["tag" => $tag]);
        }
        return redirect()->route("tag.index");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $input = $request->only(['id','name']);

        $validate_data = [
            'id' => 'required|integer',
            'name' => 'required|string'
        ];

        //Se comprueba si los datos son validos
        $validator = Validator::make($input, $validate_data);

        if (!$validator->fails()){
            $change=Tag::find($input['id']);
            $change->name=$input['name'];
            $change->save();
        }
        return redirect()->route("tag.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $input = $request->only(['id']);
        $delete=Tag::find($input['id']);
        $delete->delete();
        return redirect()->route("tag.index");
    }
}
