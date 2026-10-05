<?php

namespace Modules\Student\Http\Controllers\Student;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Chat\Entities\Chat;
use Modules\Chat\Entities\ChatAttendee;
use Modules\Chat\Entities\ChatMessage;
use Modules\Student\Entities\Student;
use Modules\Student\Entities\StudentIntakeCourse;
use Modules\Trainer\Entities\Trainer;
use Modules\Trainer\Entities\TrainerIntake;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:student');
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $student_id = Auth::guard('student')->user()->id;
        $studentIntakeCourses = StudentIntakeCourse::where('student_id', $student_id)->where('status', 1)->get();
        $trainerIds = [];
        foreach ($studentIntakeCourses as $key => $value) {
            $trainerIntakes = TrainerIntake::where('intake_course_id', $value->intake_course_id)->where('status', 1)->get();
            foreach ($trainerIntakes as $trainerIntake) {
                $trainerIds[] = $trainerIntake->trainer_id;
            }
        }
        $trainerIds = array_unique($trainerIds);
        $trainers = [];
        foreach ($trainerIds as $trainerId) {
            $trainers[] = [
                'id' => $trainerId,
                'name' => userName('Trainer', $trainerId)
            ];
        }
        return view('student::student.chat.index', compact('trainers'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($trainer_id)
    {
        $id = Auth::guard('student')->user()->id;
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
        $chatMessages = ChatMessage::where('chat_id', $chat->id)->orderBy('id', 'desc')->paginate(10);
        $messages = [];
        foreach ($chatMessages as $key => $value) {
            if ($value->chatAttendee->user_type == 'Student' && $value->chatAttendee->user_id == $id) {
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
        return view('student::student.chat.message', compact('chat', 'messages'));
    }
}
