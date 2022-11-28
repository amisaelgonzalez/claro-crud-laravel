<?php

namespace App\Http\Controllers;

use App\Enum\EmailStatusEnum;
use App\Http\Requests\StoreEmailRequest;
use App\Models\Email;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()) {
            $query = Email::where('user_id', Auth::id());
            $total = $query->count();
            $emails = $query->offset($request->query('offset', 0))->limit($request->query('limit', 10))->get();

            return response()->json([
                'rows'              => $emails,
                'total'             => $total,
                'totalNotFiltered'  => $total,
            ]);
        }

        return view('emails.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('emails.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreEmailRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreEmailRequest $request)
    {
        Email::create($request->validated() + [
            'status'    => EmailStatusEnum::PENDING,
            'user_id'   => Auth::id(),
        ]);

        return redirect()->route('emails.index')->with([
            'msg' => 'Email created',
        ]);
    }
}
