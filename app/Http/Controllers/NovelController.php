<?php

namespace App\Http\Controllers;

use App\Models\Novel;
use Illuminate\Http\Request;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class NovelController extends Controller
{
    public function index()
    {
        $novel = Novel::paginate(20);
        return view("novel.index",["novels"=>$novel]);
    }
    public function indexN(Request $request)
    {
        $input = $request->only(['name']);

        $validate_data = [
            'name' => 'required|string'
        ];

        //Se comprueba si los datos son validos
        $validator = Validator::make($input, $validate_data);

        if (!$validator->fails()){
        $novel = Novel::where('title', 'LIKE', '%'.$input['name'].'%')->paginate(20);
        return view("novel.index",["novels"=>$novel]);
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\novel  $novel
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $input = $request->only(['id']);
        $delete=Novel::find($input['id']);
        $delete->delete();
        return redirect()->route("novel.index");
    }
}
