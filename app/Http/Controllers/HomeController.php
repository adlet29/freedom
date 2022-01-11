<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket;
use App\Models\User;

class HomeController extends Controller
{

    private $email; // manager email
    private $filesDir;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->email = $this->getManagerEmailAddress();
        $this->filesDir = storage_path('app/files/');
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
        $page = Auth::user()->is_manager ? 'manager' : 'clients';
        $list = [];
        if ($page == 'manager') {
            $list = Ticket::join('users', 'tickets.user_id', '=', 'users.id')
            ->where('is_manager', false)
            ->get(['users.name', 'users.email', 'tickets.*']);
        }
        
        return view($page, ['tickets' => $list]);
    }

    public function createRequest(Request $request)
    {
        $request->validate([
            'subject' => 'required|max:255',
            'message' => 'required'
        ]);

        $client_id = Auth::user()->id;
        $retData = $this->checkTime($client_id);
        if ($retData['check']) {
            $file_path = $this->uploadFile($request->file('file'));
            $data = [
                'subject' => $request->subject, 
                'message' => $request->message, 
                'user_id' => $client_id,
                'file_path' => $file_path,
            ];
            $ticket_id = Ticket::create($data)->id;
            if ((int)$ticket_id > 0) {
                $data['ticket_id'] = $ticket_id;
                $data['user_name'] = Auth::user()->name;
                \App\Jobs\Mailer::dispatch($this->email, $data);
            }
        }

        if ($retData['check'] && (int)$ticket_id > 0) {
            $alert = [
                'status' => 'success',
                'message' => "Заявка #$ticket_id успешно создано"
            ];
        }
        if (!$retData['check']) {
            $alert = [
                'status' => 'info',
                'message' => "Следующая отправка после " . $retData['date_deadline']
            ];
        }

        return view('clients', $alert);
    }

    private function checkTime($id)
    {
        $is_check = true;
        $tickets = Ticket::where('user_id', $id)->orderBy('created_at', 'desc')->first();
        if ($tickets) {
            $deadline = strtotime((string)$tickets->created_at . '+ 24 hours');
            if (time() < $deadline) {
                $is_check = false;
            }
        }
        return [
            'check' => $is_check,
            'date_deadline' => (!$is_check) ? date('H:i - d.m.Y', $deadline) : null
        ];
    }

    /**
     * save file => path
     * @return string||null
     */
    private function uploadFile($file = null)
    {
        $file_path = null;
        if (!empty($file)) {
            $original_name = $file->getClientOriginalName();
            $type = explode('.', $original_name)[1];
            $file_name = uniqid() . '.' . $type;
            $file->move($this->filesDir, $file_name);
            $file_path = $this->filesDir .'/'.$file_name;
        }
        return $file_path;
    }


}
