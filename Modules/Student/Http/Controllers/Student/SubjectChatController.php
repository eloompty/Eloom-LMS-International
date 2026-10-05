<?php

namespace Modules\Student\Http\Controllers\Student;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Chat\Entities\Chat;
use Modules\Chat\Entities\ChatAttendee;
use Modules\Chat\Entities\ChatGroup;
use Modules\Chat\Entities\ChatMessage;
use Modules\Student\Entities\StudentIntakeSubject;

class SubjectChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:student');
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index($id)
    {
        $studentIntake = StudentIntakeSubject::findorfail($id);
        $student_id = Auth::guard('student')->user()->id;
        if ($studentIntake->studentIntakeCourse->student_id == $student_id) {
            $chatGroups = ChatGroup::where('type', 'Intake Subject')->where('type_id', $studentIntake->intake_subject_id)->where('status', 1)->get();
            $chats = [];
            foreach ($chatGroups as $key => $value) {
                $chatAttendee = ChatAttendee::where('user_id', $student_id)->where('user_type', 'Student')->where('chat_id', $value->chat_id)->first();
                if ($chatAttendee) {
                    $chats[] = Chat::find($value->chat_id);
                }
            }
            return view('student::student.subject.chat.index', compact('studentIntake', 'chats'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $chat = Chat::findorfail($id);
        $student_id = Auth::guard('student')->user()->id;
        $chatAttendee = ChatAttendee::where('chat_id', $chat->id)->where('user_type', 'Student')->where('user_id', $student_id)->first();
        if ($chatAttendee) {
            $studentIntake = StudentIntakeSubject::findorfail($chat->chatGroup->type_id);
            $chatMessages = ChatMessage::where('chat_id', $id)->orderBy('id', 'desc')->paginate(10);
            $messages = [];
            foreach ($chatMessages as $key => $value) {
                $self_id = Auth::guard('student')->user()->id;
                if ($value->chatAttendee->user_type == 'Student' && $value->chatAttendee->user_id == $self_id) {
                    $self = true;
                } else {
                    $self = false;
                }

                if ($value->message_type == 'text') {
                    $message = $value->message;
                } else {
                    $multipleImages = $value->chatAttachments;
                    foreach ($multipleImages as $image) {
                        $images[] = asset($image->path);
                    }
                    $message = $images;
                }
                $messages[] = [
                    'id' => $value->id,
                    'message' => $message,
                    'user_name' => userName($value->chatAttendee->user_type, $value->chatAttendee->user_id),
                    'user_image' => asset(userImage($value->chatAttendee->user_type, $value->chatAttendee->user_id)),
                    'self' => $self,
                    'date_time' => dateTimeFormat($value->created_at)
                ];
            }
            $messages = array_reverse($messages);
            return view('student::student.subject.chat.message', compact('studentIntake', 'messages', 'chat'));
        } else {
            return abort(404);
        }
    }

    public function sendMessage(Request $request, $id)
    {
        $data = $request->all();
        $chat = Chat::find($id);
        $student_id = Auth::guard('student')->user()->id;
        $chatAttendee = ChatAttendee::where('chat_id', $chat->id)->where('user_type', 'Student')->where('user_id', $student_id)->first();
        ChatMessage::create([
            'chat_id' => $id,
            'chat_attendee_id' => $chatAttendee->id,
            'message_type' => 'text',
            'message' => $data['message'],
        ]);
        return redirect()->route('student.subject.chat.show', $id);
    }

    public function createMessage(Request $request)
    {
        $data = $request->all();
        $chat = Chat::find($data['chat_id']);

        $student_id = Auth::guard('student')->user()->id;
        $chatAttendee = ChatAttendee::where('chat_id', $chat->id)->where('user_type', 'Student')->where('user_id', $student_id)->first();
        ChatMessage::create([
            'chat_id' => $chat->id,
            'chat_attendee_id' => $chatAttendee->id,
            'message_type' => 'text',
            'message' => $data['message'],
        ]);
        $userName = userName('Student', $student_id);
        $userImageLink = asset(Auth::guard('student')->user()->image);

        // Emit the message to Socket.IO server
        emitMessageToSocket($chat->id, $student_id, $userName, 'Student', $userImageLink, $data['message'], 'text');

        return response()->json($data);
    }

    public function loadMessages(Request $request)
    {
        $chatId = $request->input('chatId');
        $page = $request->input('page', 1); // Get the current page from the request, default to 1

        // Fetch paginated messages
        $chatMessages = ChatMessage::where('chat_id', $chatId)
            ->orderBy('id', 'desc') // Order messages from newest to oldest
            ->paginate(10, ['*'], 'page', $page); // Load 10 messages per page

        $messages = [];
        foreach ($chatMessages as $key => $value) {
            $self_id = Auth::guard('student')->user()->id;
            if ($value->chatAttendee->user_type == 'Student' && $value->chatAttendee->user_id == $self_id) {
                $self = true;
            } else {
                $self = false;
            }

            if ($value->message_type == 'text') {
                $message = $value->message;
            } else {
                $multipleImages = $value->chatAttachments;
                foreach ($multipleImages as $image) {
                    $images[] = asset($image->path);
                }
                $message = $images;
            }
            $messages[] = [
                'id' => $value->id,
                'message' => $message,
                'user_name' => userName($value->chatAttendee->user_type, $value->chatAttendee->user_id),
                'user_image' => asset(userImage($value->chatAttendee->user_type, $value->chatAttendee->user_id)),
                'self' => $self,
                'date_time' => dateTimeFormat($value->created_at)
            ];
        }
        return response()->json($messages);
    }
}
