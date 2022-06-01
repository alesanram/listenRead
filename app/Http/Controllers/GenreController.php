<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class GenreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $genres = Genre::paginate(20);
        return view("genre.index",["genres"=>$genres]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("genre.create");
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
            Genre::create([
                'name'=>$input['name']
            ]);
        }
        return redirect('/genre');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Genre  $genre
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
            $genre=Genre::find($input['id']);
            return view("genre.update", ["genre" => $genre]);
        }
        return redirect()->route("genre.index");
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
            $change=Genre::find($input['id']);
            $change->name=$input['name'];
            $change->save();
        }
        return redirect()->route("genre.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Genre  $genre
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $input = $request->only(['id']);
        $delete=Genre::find($input['id']);
        $delete->delete();
        return redirect()->route("genre.index");
    }
}
