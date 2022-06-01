<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::paginate(20);
        return view("user.index",["users"=>$user]);
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
        $user = User::where('name', 'LIKE', '%'.$input['name'].'%')->paginate(20);
        return view("user.index",["users"=>$user]);
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $input = $request->only(['id']);
        $delete=User::find($input['id']);
        $delete->delete();
        return redirect()->route("user.index");
    }
    public function is_admin($indice){
        $user=User::find($indice);
        $user->role_id=1;
        $user->save();
        return redirect()->route("users.index");
    }
}
