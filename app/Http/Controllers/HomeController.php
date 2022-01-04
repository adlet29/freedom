<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $home = (Auth::user()->is_manager) ? 'home' : 'clientes';
        return view($home);
    }

    public function action(Request $request)
    {
        $this->validation($request);
        $file_path = $this->save_file(storage_path('app/files/'), $request->file('file'));
        $data = [];
        $data['subject'] = $request['subject'];
        $data['message'] = $request['message'];
        $data['file_to_path'] = $file_path;
        \App\Jobs\Mailer::dispatch($data);
    }

    /**
     * save file => path
     * @return string||null
     */
    private function save_file($path, $file = null)
    {
        $file_path = null;
        if (!empty($file)) {
            $original_name = $file->getClientOriginalName();
             $type = explode('.', $original_name)[1];
             $file_name = uniqid() . '.' . $type;
             $file->move($path, $file_name);
             $file_path = $path .'/'.$file_name;
        }
        return $file_path;
    }

    /**
     * validation
     */
    private function validation(Request $request) 
    {
        $v = $request->validate([
            'subject' => 'required|max:255',
            'message' => 'required'
        ]);
        return $v;
    }


}
