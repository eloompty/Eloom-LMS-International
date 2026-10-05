<?php

namespace Modules\Trainer\Http\Controllers\Trainer;

use GuzzleHttp\Client;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Modules\Chat\Entities\Chat;
use Modules\Chat\Entities\ChatAttendee;
use Modules\Chat\Entities\ChatGroup;
use Modules\Chat\Entities\ChatMessage;
use Modules\Intake\Entities\IntakeSubject;
use Modules\Student\Entities\StudentIntakeSubject;
use Modules\Trainer\Entities\TrainerIntake;

class IntakeSubjectChatController extends Controller
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
        $trainerIntake = TrainerIntake::where('intake_subject_id', $id)->where('trainer_id', $trainer_id)->first();
        if ($trainerIntake) {
            $chatGroups = ChatGroup::where('type', 'Intake Subject')->where('type_id', $trainerIntake->intake_subject_id)->get();
            activityLog('Trainer', 'Opened intake subject chat list of ' . $trainerIntake->intakeSubject->subject->name . ' from web');
            return view('trainer::trainer.subject.chat.index', compact('trainerIntake', 'chatGroups'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create($id, $trainer_intake_id)
    {
        $trainer_id = Auth::guard('trainer')->user()->id;
        $trainerIntake = TrainerIntake::findorfail($trainer_intake_id);
        if ($trainerIntake->intake_subject_id == $id) {
            $studentIntakeSubjects = StudentIntakeSubject::where('intake_subject_id', $id)->whereIn('status', [1, 3])->get();
            activityLog('Trainer', 'Opened intake subject chat create of ' . $trainerIntake->intakeSubject->subject->name . ' from web');
            return view('trainer::trainer.subject.chat.create', compact('trainerIntake', 'studentIntakeSubjects'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id, $trainer_intake_id)
    {
        $intakeSubject = IntakeSubject::findorfail($id);
        $trainerIntake = TrainerIntake::findorfail($trainer_intake_id);
        if ($trainerIntake->intake_subject_id == $id) {
            $data = $request->all();
            $data['type'] = 'Group';
            if ($request->hasfile('image')) {
                $imageName = time() . '.' . request()->image->getClientOriginalExtension();
                request()->image->move(public_path('/images/chats'), $imageName);
                $data['image'] = 'images/chats/' . $imageName;
            }
            $chat = Chat::create($data);
            ChatGroup::create([
                'chat_id' => $chat->id,
                'type' => 'Intake Subject',
                'type_id' => $id,
            ]);
            ChatAttendee::create([
                'chat_id' => $chat->id,
                'user_id' => Auth::guard('trainer')->user()->id,
                'user_type' => 'Trainer',
                'is_owner' => 1,
            ]);
            foreach ($data['students'] as $key => $value) {
                ChatAttendee::create([
                    'chat_id' => $chat->id,
                    'user_id' => $value,
                    'user_type' => 'Student',
                ]);
            }
            return redirect()->route('trainer.subject.chat.index', $id)->with('success', 'Chat has been successfully created');
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
        $chatGroup = ChatGroup::findorfail($id);
        $chat = Chat::findorfail($chatGroup->chat_id);
        $chatMessages = ChatMessage::where('chat_id', $chat->id)->orderBy('id', 'desc')->paginate(10);
        $self_id = Auth::guard('trainer')->user()->id;
        $trainerIntake = TrainerIntake::where('intake_subject_id', $chatGroup->type_id)->where('trainer_id', $self_id)->first();
        if ($trainerIntake) {
            $messages = [];
            foreach ($chatMessages as $key => $value) {
                if ($value->chatAttendee->user_type == 'Trainer' && $value->chatAttendee->user_id == $self_id) {
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
            return view('trainer::trainer.subject.chat.message', compact('chatGroup', 'chat', 'messages', 'trainerIntake'));
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $chatGroup = ChatGroup::findorfail($id);
        $chat = Chat::findorfail($chatGroup->chat_id);
        $self_id = Auth::guard('trainer')->user()->id;
        $trainerIntake = TrainerIntake::where('intake_subject_id', $chatGroup->type_id)->where('trainer_id', $self_id)->first();
        if ($trainerIntake) {
            $studentIntakeSubjects = StudentIntakeSubject::where('intake_subject_id', $chatGroup->type_id)->whereIn('status', [1, 3])->get();
            $students = [];
            foreach ($studentIntakeSubjects as $key => $value) {
                $chatStudent = ChatAttendee::where('chat_id', $chat->id)->where('user_type', 'Student')->where('user_id', $value->studentIntakeCourse->student_id)->first();
                if ($chatStudent) $is_attendee = true;
                else $is_attendee = false;
                $students[] = [
                    'id' => $value->studentIntakeCourse->student_id,
                    'name' => userName('Student', $value->studentIntakeCourse->student_id),
                    'is_attendee' => $is_attendee
                ];
            }
            return view('trainer::trainer.subject.chat.edit', compact('chat', 'students', 'trainerIntake'));
        } else {
            return abort(404);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $chat = Chat::findorfail($id);
        $chatData['title'] = $data['title'];
        $chatData['description'] = $data['description'];
        $chatData['status'] = $data['status'];
        if ($request->hasfile('image')) {
            $imageName = time() . '.' . request()->image->getClientOriginalExtension();
            request()->image->move(public_path('/images/chats'), $imageName);
            $chatData['image'] = 'images/chats/' . $imageName;
        }
        $chat->update($chatData);

        $chatStudents = ChatAttendee::where('chat_id', $chat->id)->where('user_type', 'Student')->pluck('user_id')->toArray();
        $editStudents = $data['students'];

        $studentsToAdd = array_diff($editStudents, $chatStudents);
        $studentsToUpdate = array_intersect($editStudents, $chatStudents);
        $studentsToDelete = array_diff($chatStudents, $editStudents);

        foreach ($studentsToAdd as $studentId) {
            ChatAttendee::create([
                'chat_id' => $chat->id,
                'user_id' => $studentId,
                'user_type' => 'Student',
                'status' => $data['status']
            ]);
        }

        ChatAttendee::where('chat_id', $chat->id)->where('user_type', 'Student')->whereIn('user_id', $studentsToDelete)->delete();

        foreach ($studentsToUpdate as $studentUpdateId) {
            ChatAttendee::where('chat_id', $chat->id)->where('user_type', 'Student')->where('user_id', $studentUpdateId)
                ->update(['status' => $data['status']]);
        }
        return redirect()->route('trainer.subject.chat.index', $chat->chatGroup->type_id)->with('success', 'Chat has been updated');
    }

    public function sendMessage(Request $request, $id)
    {
        $data = $request->all();
        $chat = Chat::find($id);
        $trainer_id = Auth::guard('trainer')->user()->id;
        $chatAttendee = ChatAttendee::where('chat_id', $chat->id)->where('user_type', 'Trainer')->where('user_id', $trainer_id)->first();
        ChatMessage::create([
            'chat_id' => $id,
            'chat_attendee_id' => $chatAttendee->id,
            'message_type' => 'text',
            'message' => $data['message'],
        ]);
        return redirect()->route('trainer.subject.chat.show', $chat->chatGroup->id);
    }

    public function createMessage(Request $request)
    {
        $data = $request->all();
        $chat = Chat::find($data['chat_id']);
        
        $trainer_id = Auth::guard('trainer')->user()->id;
        $chatAttendee = ChatAttendee::where('chat_id', $chat->id)->where('user_type', 'Trainer')->where('user_id', $trainer_id)->first();
        ChatMessage::create([
            'chat_id' => $chat->id,
            'chat_attendee_id' => $chatAttendee->id,
            'message_type' => 'text',
            'message' => $data['message'],
        ]);
        $userName = userName('Trainer', $trainer_id);
        $userImageLink = asset(Auth::guard('trainer')->user()->image);

        // Emit the message to Socket.IO server
        emitMessageToSocket($chat->id, $trainer_id, $userName, 'Trainer', $userImageLink, $data['message'], 'text');

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
            $self_id = Auth::guard('trainer')->user()->id;
            if ($value->chatAttendee->user_type == 'Trainer' && $value->chatAttendee->user_id == $self_id) {
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
