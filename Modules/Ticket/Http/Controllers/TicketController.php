<?php

namespace Modules\Ticket\Http\Controllers;

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
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the ticket.
     * @return Renderable
     */
    public function index(Request $request)
    {
        if (checkRole('ticket', 'view') == true) {
            activityLog('Admin', 'Opened Ticket Support List');
            $status = $request->status;
            if ($status == NULL || $status == "" || $status == 1){
                $status = 1;
            }
            $tickets = Ticket::where('status', $status)->orderby('id', 'desc')->get();
            return view('ticket::index', compact('tickets', 'status'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the specified ticket.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        if (checkRole('ticket', 'add') == true) {
            $ticket = Ticket::findorfail($id);
            $closed = '';
            $reopned = '';
            if ($ticket->status == 3) {
                $closed = TicketStatusHistory::where('ticket_id', $ticket->id)->where('status', 3)->orderBy('id', 'desc')->first();
            }
            if ($ticket->status == 4) {
                $reopned = TicketStatusHistory::where('ticket_id', $ticket->id)->where('status', 4)->orderBy('id', 'desc')->first();
            }
            activityLog('Admin', 'Opened Ticket: ' . $ticket->subject);
            return view('ticket::show', compact('ticket', 'closed', 'reopned'));
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
        if ($ticket->status == 1) {
            $ticket->update(['status' => 2]);
            TicketStatusHistory::create([
                'ticket_id' => $id,
                'user_id' => Auth::guard('user')->user()->id,
                'user_type' => 'User',
                'status' => 2
            ]);
        }

        // Save reply
        $reply = TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::guard('user')->user()->id,
            'user_type' => 'User',
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

        return redirect()->route('admin.ticket.show', $ticket->id)->with('success', 'Reply added successfully!');
    }

    /**
     * Close the specified ticket.
     * @param int $id
     * @return Renderable
     */
    public function close($id)
    {
        if (checkRole('ticket', 'edit') == true) {
            $ticket = Ticket::findorfail($id);
            if ($ticket->status != 3) {
                TicketStatusHistory::create([
                    'ticket_id' => $id,
                    'user_id' => Auth::guard('user')->user()->id,
                    'user_type' => 'User',
                    'status' => 3
                ]);
                $ticket->update(['status' => 3]);
                activityLog('Admin', 'Closed Ticket: ' . $ticket->subject);
                return redirect()->route('admin.ticket.show', $id)->with('success', 'Ticket has been closed successfully');
            } else {
                return redirect()->route('admin.ticket.show', $id)->with('failure', 'Ticket has already been already been closed');
            }
        } else {
            return abort(404);
        }
    }

    /**
     * Reopen the specified ticket.
     * @param int $id
     * @return Renderable
     */
    public function reopen($id)
    {
        if (checkRole('ticket', 'edit') == true) {
            $ticket = Ticket::findorfail($id);
            if ($ticket->status == 3) {
                TicketStatusHistory::create([
                    'ticket_id' => $id,
                    'user_id' => Auth::guard('user')->user()->id,
                    'user_type' => 'User',
                    'status' => 4
                ]);
                $ticket->update(['status' => 4]);
                activityLog('Admin', 'Reopened Ticket: ' . $ticket->subject);
                return redirect()->route('admin.ticket.show', $id)->with('success', 'Ticket has been reopned successfully');
            } else {
                return redirect()->route('admin.ticket.show', $id)->with('failure', 'Ticket is already active');
            }
        } else {
            return abort(404);
        }
    }
}
