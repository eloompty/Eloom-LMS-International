<?php

namespace Modules\Student\Http\Controllers\Student;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Ticket\Entities\Ticket;
use Modules\Ticket\Entities\TicketAttachment;
use Modules\Ticket\Entities\TicketReply;
use Modules\Ticket\Entities\TicketStatusHistory;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:student');
    }

    /**
     * Display a listing of the ticket.
     * @return Renderable
     */
    public function index()
    {
        $id = Auth::guard('student')->user()->id;
        $tickets = Ticket::where('user_id', $id)->where('user_type', 'Student')->get();
        activityLog('Student', 'Opened Ticket menu from web');
        return view('student::student.ticket.index', compact('tickets'))->with('no', 1);
    }

    /**
     * Show the form for creating a new ticket.
     * @return Renderable
     */
    public function create()
    {
        activityLog('Student', 'Opened create ticket from web');
        return view('student::student.ticket.create');
    }

    /**
     * Store a newly created ticket in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['user_id'] = Auth::guard('student')->user()->id;
        $data['user_type'] = 'Student';
        $ticket = Ticket::create($data);
        if ($request->hasFile('files')) {
            $files =  $request->file('files');
            foreach ($files as $file) {
                $name = time() . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path() . '/images/tickets/', $name);
                $data['path'] = 'images/tickets/' . $name;
                $data['ticket_id'] = $ticket->id;
                TicketAttachment::create($data);
            }
        }
        return redirect()->route('student.ticket.index')->with('success', 'Your ticket has been opened and has been informed to admin');
    }

    /**
     * Show the specified ticket.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $student_id = Auth::guard('student')->user()->id;
        $ticket = Ticket::where('id', $id)->where('user_id', $student_id)->where('user_type', 'Student')->first();
        if ($ticket) {
            $closed = '';
            $reopned = '';
            if ($ticket->status == 3) {
                $closed = TicketStatusHistory::where('ticket_id', $ticket->id)->where('status', 3)->orderBy('id', 'desc')->first();
            }
            if ($ticket->status == 4) {
                $reopned = TicketStatusHistory::where('ticket_id', $ticket->id)->where('status', 4)->orderBy('id', 'desc')->first();
            }
            return view('student::student.ticket.show', compact('ticket', 'closed', 'reopned'));
        } else {
            return abort(404);
        }
    }

    /**
     * Reply the specified ticket in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function reply(Request $request, $id)
    {
        $request->validate([
            'message' => 'required',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf,docx,xlsx|max:2048'
        ]);

        $ticket = Ticket::findorfail($id);

        // Save reply
        $reply = TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::guard('student')->user()->id,
            'user_type' => 'Student',
            'message' => $request->message,
        ]);

        // Save attachments if there are any
        if ($request->hasFile('attachments')) {
            $files =  $request->file('attachments');
            foreach ($files as $file) {
                $name = time() . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path() . '/images/tickets/', $name);
                $data['path'] = 'images/tickets/' . $name;
                $data['ticket_reply_id'] = $reply->id;
                TicketAttachment::create($data);
            }
        }

        return redirect()->route('student.ticket.show', $ticket->id)->with('success', 'Reply added successfully!');
    }
}
