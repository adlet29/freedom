<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class ManagerController extends Controller
{
    private $filesDir;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->filesDir = storage_path('app/files/');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('manager', [
            'tickets' => Ticket::whereHas('user', function ($query){
                    return $query;
                })
                ->with('user')
                ->notViewed()
                ->orderBy('id', 'DESC')
                ->paginate(20)
        ]);
    }

    public function status_change($ticket_id)
    {
        Ticket::findOrFail($ticket_id)->update([
            'viewed' => true
        ]);

        return redirect()->route('manager.index');
    }
}
