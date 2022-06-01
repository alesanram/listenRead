<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function reportNovel()
    {
        $notifications=DB::table('notifications')->where('user_id','=',0,'AND','action','LIKE','"%acion:report,novel:%"')
        ->select('title,description,action')->paginate(20);
        return view("notification.index",["notifications"=>$notifications]);

    }

    public function reportReview()
    {
        $notifications=DB::table('notifications')->where('user_id','=',0,'AND','action','LIKE','"%acion:report,review:%"')
        ->select('title,description,action')->paginate(20);
        return view("notification.index",["notifications"=>$notifications]);
    }

    public function reportComment()
    {
        $notifications=DB::table('notifications')->where('user_id','=',0,'AND','action','LIKE','"%acion:report,novel:%"')
        ->select('title,description,action')->paginate(20);
        return view("notification.index",["notifications"=>$notifications]);
    }
    public function closeCount()
    {
        $notifications=DB::table('notifications')->where('user_id','=',0,'AND','action','LIKE','"%acion:close,user:%"')
        ->select('title,description,action')->paginate(20);
        return view("notification.index",["notifications"=>$notifications]);
    }

    public function message(){
        return view('notification.create');
    }
    public function send(Request $request){
        $input = $request->only(['title','description','message']);

        $validate_data = [
            'message' => 'required|string|min:20',
            'title' => 'required|string|min:10',
            'description' => 'required|string|min:10'
        ];

        //Se comprueba si los datos son validos
        $validator = Validator::make($input, $validate_data);

        if (!$validator->fails() ) {
            $user=User::all();
            $action='noAction';
            foreach($users as $user){
                Notification::create(
                    'title'->$input['title'],
                    'description'->$input['description'],
                    'action'->$action,
                    'message'->$input['message'],
                    'user_id'->$user->id
                );
            }
        }
    }
}
