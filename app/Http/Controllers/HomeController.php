<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket;
use App\Models\User;

class HomeController extends Controller
{
    private $filesDir;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->filesDir = storage_path('app/files');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('clients');
    }

    public function create(Request $request)
    {
        $request->validate([
            'subject' => 'required|max:255',
            'message' => 'required'
        ]);

        $last_ticket_for_a_day = Auth::user()->tickets()->where('created_at', '>=', now()->subDay())->first();
        if($last_ticket_for_a_day){
            $file_path = $this->uploadFile($request->file('file'));

            $ticket = Auth::user()->tickets()->create([
                'subject' => $request->subject, 
                'message' => $request->message,
                'file_path' => $file_path,
            ]);
            
            $managers = User::manager()->get();
            $managers->each->notify(new \App\Notifications\NewTicket($ticket));
            
            session()->flash('success', 'Заявка успешно создана!');
        }
        else{
            $diffMinutes = now()->diffInMinutes($last_ticket_for_a_day->created_at->addDay());
            session()->flash(
                'info', "Следующая отправка через ".
                    trans_choice('time.minutes', $diffMinutes, [
                        'minutes' => $diffMinutes
                    ])
            );
        }

        return view('clients');
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
