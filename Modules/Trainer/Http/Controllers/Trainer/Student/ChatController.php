<?php

namespace Modules\Trainer\Http\Controllers\Trainer\Student;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Chat\Entities\Chat;
use Modules\Chat\Entities\ChatAttendee;
use Modules\Chat\Entities\ChatMessage;
use Modules\Student\Entities\Student;
use Modules\Trainer\Entities\Trainer;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:trainer');
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index($id)
    {
        $trainer_id = Auth::guard('trainer')->user()->id;
        $trainerCheck = Trainer::findorfail($trainer_id);
        $studentCheck = Student::findorfail($id);
        $title = $trainer_id . ',' . $id;
        $chat = Chat::where('title', $title)->where('status', 1)->first();
        if ($chat) {
            $chatAttendeeStudent = ChatAttendee::where('chat_id', $chat->id)->where('user_type', 'Student')->where('user_id', $id)->first();
            $chatAttendeeTrainer = ChatAttendee::where('chat_id', $chat->id)->where('user_type', 'Trainer')->where('user_id', $trainer_id)->first();
            if ($chatAttendeeStudent && $chatAttendeeTrainer) {
                $message = true;
            } else {
                $message = false;
            }
        } else {
            $message = false;
        }
        $trainer_name = userName('Trainer', $trainer_id);
        $student_name = userName('Student', $id);
        if ($message == false) {
            $chat = Chat::create([
                'title' => $title,
                'description' => 'Personal Message Between Teacher:' . $trainer_name . ' and Student: ' . $student_name,
                'type' => 'Personal',
            ]);
            ChatAttendee::create([
                'chat_id' => $chat->id,
                'user_id' => $trainer_id,
                'user_type' => 'Trainer',
                'is_owner' => 1,
            ]);
            ChatAttendee::create([
                'chat_id' => $chat->id,
                'user_id' => $id,
                'user_type' => 'Student',
                'is_owner' => 0,
            ]);
        }
        $chat->title = $trainer_name . ' - ' . $student_name;
        $chatMessages = ChatMessage::where('chat_id', $chat->id)->orderBy('id','desc')->paginate(10);
        $messages = [];
        foreach ($chatMessages as $key => $value) {
            if ($value->chatAttendee->user_type == 'Trainer' && $value->chatAttendee->user_id == $trainer_id) {
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
        return view('trainer::trainer.students.chat.index', compact('chat', 'messages'));
    }

    public function sendMessage(Request $request, $id)
    {
        $data = $request->all();
        $chat = Chat::find($id);
        $trainer_id = Auth::guard('trainer')->user()->id;
        $chatAttendee = ChatAttendee::where('chat_id', $chat->id)->where('user_type', 'Trainer')->where('user_id', $trainer_id)->first();
        $chatAttendeeStudent = ChatAttendee::where('chat_id', $chat->id)->where('user_type', 'Student')->first();
        ChatMessage::create([
            'chat_id' => $id,
            'chat_attendee_id' => $chatAttendee->id,
            'message_type' => 'text',
            'message' => $data['message'],
        ]);
        return redirect()->route('trainer.students.chat.index', $chatAttendeeStudent->user_id);
    }
}
