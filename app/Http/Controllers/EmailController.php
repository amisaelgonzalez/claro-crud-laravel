<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmailRequest;
use App\Services\EmailService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, EmailService $emailService): View|JsonResponse
    {
        if($request->ajax()) {
            $emailsPaginated = $emailService->getPaginatedByUser($request->query('offset', 0), $request->query('limit', 0));

            return response()->json($emailsPaginated);
        }

        return view('emails.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('emails.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmailRequest $request, EmailService $emailService): RedirectResponse
    {
        $emailService->store($request->validated());

        return redirect()->route('emails.index')->with([
            'msg' => 'Email created',
        ]);
    }
}
