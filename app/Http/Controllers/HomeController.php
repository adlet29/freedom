<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket;
use App\Models\User;

class HomeController extends Controller
{

    private $email; // manager email

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->email = $this->getManagerEmailAddress();
    }

    private function getManagerEmailAddress()
    {
        $managers = User::select('email')->where('is_manager', true)->get();
        $email_address = '';
        if (sizeof($managers)) {
            $email_address = $managers[0]->email; // first manager email
        }
        return $email_address;
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $home = (Auth::user()->is_manager) ? 'manager' : 'clientes';
        return view($home);
    }

    public function action(Request $request)
    {
        $this->validation($request);
        $client_id = Auth::user()->id;
        if ($this->checkTime($client_id)) {
            $file_path = $this->uploadFile(storage_path('app/files/'), $request->file('file'));
            $data = [
                'subject' => $request->subject, 
                'message' => $request->message, 
                'user_id' => $client_id,
                'file_path' => $file_path,
            ];
            $ticket_id = Ticket::create($data)->id;
            if ($ticket_id > 0) {
                $data['ticket_id'] = $ticket_id;
                $data['user_name'] = Auth::user()->name;
                //\App\Jobs\Mailer::dispatch($this->email, $data);
            }
        }

        return redirect('/home');
    }

    private function checkTime($id)
    {
        $check = true;
        $tickets = Ticket::where('user_id', $id)->orderBy('created_at', 'desc')->first();
        if ($tickets) {
            $h = 86400;
            $time = strtotime((string)$tickets->created_at);
            if (time() < $time + $h) {
                $check = false;
            }
        }
        return $check;
    }

    /**
     * save file => path
     * @return string||null
     */
    private function uploadFile($path, $file = null)
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
