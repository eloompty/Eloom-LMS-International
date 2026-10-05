<?php

namespace Modules\Chat\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Chat\Entities\Chat;
use Modules\Chat\Entities\ChatAttendee;
use Modules\Chat\Entities\ChatGroup;
use Modules\Chat\Entities\ChatMessage;
use Modules\Intake\Entities\Intake;
use Modules\Intake\Entities\IntakeCourse;
use Modules\Intake\Entities\IntakeSemester;
use Modules\Intake\Entities\IntakeSubject;
use Modules\Intake\Entities\IntakeUnit;
use Modules\Student\Entities\StudentIntakeSubject;
use Modules\Trainer\Entities\TrainerIntake;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the chat.
     * @return Renderable
     */
    public function index(Request $request)
    {
        if (checkRole('chat', 'view') == true) {
            activityLog('Admin', 'Opened Chat List');
            $status = $request->status;
            if ($status == NULL || $status == "" || $status == 1) {
                $status = 1;
            }
            $chats = Chat::where('status', $status)->orderby('id', 'desc')->get();
            foreach ($chats as $key => $value) {
                if ($value->type == 'Personal') {
                    $title = explode(',', $value->title);
                    $trainer_name = userName('Trainer', $title[0]);
                    $student_name = userName('Student', $title[1]);
                    $chats[$key]['title'] = $trainer_name . ' - ' . $student_name;
                }
            }
            return view('chat::index', compact('chats', 'status'))->with('no', 1);
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for creating a new chat.
     * @return Renderable
     */
    public function create()
    {
        if (checkRole('chat', 'add') == true) {
            activityLog('Admin', 'Opened create chat page');
            $intakes = Intake::where('status', 1)->pluck('name', 'id');
            return view('chat::create', compact('intakes'));
        } else {
            return redirect()->route('admin.chat.index')->with('failure', 'This user does not have permission to create chat');
        }
    }

    /**
     * Store a newly created chat in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
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
            'type_id' => $data['intake_subject_id'],
            'status' => $data['status']
        ]);
        if (isset($data['teacher_id'])) {
            ChatAttendee::create([
                'chat_id' => $chat->id,
                'user_id' => $data['teacher_id'],
                'user_type' => 'Trainer',
                'is_owner' => 1,
                'status' => $data['status']
            ]);
        }
        foreach ($data['students'] as $key => $value) {
            ChatAttendee::create([
                'chat_id' => $chat->id,
                'user_id' => $value,
                'user_type' => 'Student',
                'status' => $data['status']
            ]);
        }
        return redirect()->route('admin.chat.index')->with('success', 'Chat has been successfully created');
    }

    /**
     * Show the specified chat.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        if (checkRole('chat', 'view') == true) {
            $chat = Chat::findorfail($id);
            if ($chat->type == 'Personal') {
                $title = explode(',', $chat->title);
                $trainer_name = userName('Trainer', $title[0]);
                $student_name = userName('Student', $title[1]);
                $chat->title = $trainer_name . ' - ' . $student_name;
            }
            $chatMessages = ChatMessage::where('chat_id', $id)->orderBy('id', 'desc')->paginate(10);
            $messages = [];
            foreach ($chatMessages as $key => $value) {
                $self_id = Auth::guard('user')->user()->id;
                if ($value->chatAttendee->user_type == 'Admin' && $value->chatAttendee->user_id == $self_id) {
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
            return view('chat::message', compact('chat', 'messages'));
        } else {
            return abort(404);
        }
    }

    /**
     * Show the form for editing the specified chat.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $chat = Chat::findorfail($id);
        if (checkRole('chat', 'edit') == true && $chat->type == 'Group') {
            activityLog('Admin', 'Opened edit chat page');
            $chatgroup = $chat->chatGroup;
            $intakeSubject = IntakeSubject::find($chatgroup->type_id);
            $intakeStudents = StudentIntakeSubject::where('intake_subject_id', $intakeSubject->id)->whereIn('status', [1, 3])->get();
            $students = [];
            foreach ($intakeStudents as $key => $value) {
                $chatStudent = ChatAttendee::where('chat_id', $chat->id)->where('user_type', 'Student')->where('user_id', $value->studentIntakeCourse->student_id)->first();
                if ($chatStudent) $is_attendee = true;
                else $is_attendee = false;
                $students[] = [
                    'id' => $value->studentIntakeCourse->student_id,
                    'name' => userName('Student', $value->studentIntakeCourse->student_id),
                    'is_attendee' => $is_attendee
                ];
            }
            $intakeTrainer = TrainerIntake::where('intake_subject_id', $intakeSubject->id)->first();
            if ($intakeTrainer) {
                $chatTrainer = ChatAttendee::where('chat_id', $chat->id)->where('user_type', 'Trainer')->where('user_id', $intakeTrainer->trainer_id)->first();
                if ($chatTrainer) $is_attendee_trainer = true;
                else $is_attendee_trainer = false;
                $trainer = [
                    'id' => $intakeTrainer->trainer_id,
                    'name' => userName('Trainer', $intakeTrainer->trainer_id),
                    'is_attendee' => $is_attendee_trainer
                ];
            } else {
                $trainer = NULL;
            }
            return view('chat::edit', compact('chat', 'intakeSubject', 'students', 'trainer'));
        } else {
            return redirect()->route('admin.chat.index')->with('failure', 'This user does not have permission to edit chat');
        }
    }

    /**
     * Update the specified chat in storage.
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
        if (isset($data['teacher_id'])) {
            $trainer = ChatAttendee::where('chat_id', $chat->id)->where('user_type', 'Trainer')->where('user_id', $data['teacher_id'])->first();
            if ($trainer) {
                $trainer->update(['status' => $data['status']]);
            } else {
                $trainer->delete();
                ChatAttendee::create([
                    'chat_id' => $chat->id,
                    'user_id' => $data['teacher_id'],
                    'user_type' => 'Trainer',
                    'is_owner' => 1,
                    'status' => $data['status']
                ]);
            }
        } else {
            $trainer = ChatAttendee::where('chat_id', $chat->id)->where('user_type', 'Trainer')->delete();
        }

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
        return redirect()->route('admin.chat.index')->with('success', 'Chat has been successfully updated');
    }

    /**
     * Remove the specified chat from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    /* Get Course By Intake Id */
    public function getIntakeCourse(Request $request)
    {
        $courses = IntakeCourse::where('intake_id', $request->intake_id)->where('status', 1)->get();
        $data = [];
        foreach ($courses as $key => $value) {
            $data[] = [
                $value->id => $value->course->course_name
            ];
        }
        $mergedData = [];
        foreach ($data as $item) {
            foreach ($item as $key => $value) {
                $mergedData[$key] = $value;
            }
        }
        return response()->json($mergedData);
    }

    /* Get Semester By Course Id */
    public function getIntakeSemester(Request $request)
    {
        $semesters = IntakeSemester::where('intake_course_id', $request->intake_course_id)->get();
        $data = [];
        foreach ($semesters as $key => $value) {
            $data[] = [
                $value->id => $value->semester->name
            ];
        }
        $mergedData = [];
        foreach ($data as $item) {
            foreach ($item as $key => $value) {
                $mergedData[$key] = $value;
            }
        }
        return response()->json($mergedData);
    }

    /* Get Subject By Semester Id */
    public function getIntakeSubject(Request $request)
    {
        $subjects = IntakeSubject::where('intake_semester_id', $request->intake_semester_id)->get();
        $data = [];
        foreach ($subjects as $key => $value) {
            $data[] = [
                $value->id => $value->subject->name
            ];
        }
        $mergedData = [];
        foreach ($data as $item) {
            foreach ($item as $key => $value) {
                $mergedData[$key] = $value;
            }
        }
        return response()->json($mergedData);
    }

    /* Get Unit By Subject Id */
    public function getIntakeUnit(Request $request)
    {
        $units = IntakeUnit::where('intake_subject_id', $request->intake_subject_id)->get();
        $data = [];
        foreach ($units as $key => $value) {
            $data[] = [
                $value->id => $value->unit->name
            ];
        }
        $mergedData = [];
        foreach ($data as $item) {
            foreach ($item as $key => $value) {
                $mergedData[$key] = $value;
            }
        }
        return response()->json($mergedData);
    }

    public function getStudentTeacherFromIntakeSubject(Request $request)
    {
        $trainerIntake = TrainerIntake::where('intake_subject_id', $request->intake_subject_id)->first();
        $studentIntakes = StudentIntakeSubject::where('intake_subject_id', $request->intake_subject_id)->whereIn('status', [1, 3])->get();
        $students = [];
        foreach ($studentIntakes as $key => $value) {
            $students[] = [
                'id' => $value->studentIntakeCourse->student_id,
                'name' => userName('Student', $value->studentIntakeCourse->student_id)
            ];
        }
        $uniqueStudents = [];
        $studentIds = [];

        foreach ($students as $student) {
            if (!in_array($student['id'], $studentIds)) {
                $studentIds[] = $student['id'];
                $uniqueStudents[] = $student;
            }
        }

        $trainer = NULL;
        if ($trainerIntake) {
            $trainer = [
                'id' => $trainerIntake->trainer_id,
                'name' => userName('Trainer', $trainerIntake->trainer_id)
            ];
        }
        $data = [
            'teacher' => $trainer,
            'students' => $uniqueStudents
        ];
        return response()->json($data);
    }

    public function createMessage(Request $request)
    {
        $data = $request->all();
        $chat = Chat::find($data['chat_id']);
        $admin_id = Auth::guard('user')->user()->id;
        $chatAttendee = ChatAttendee::where('chat_id', $chat->id)->where('user_type', 'Admin')->where('user_id', $admin_id)->first();
        if (!$chatAttendee) {
            $chatAttendee = ChatAttendee::create([
                'chat_id' => $chat->id,
                'user_id' => $admin_id,
                'user_type' => 'Admin',
                'is_owner' => 1
            ]);
        }
        ChatMessage::create([
            'chat_id' => $data['chat_id'],
            'chat_attendee_id' => $chatAttendee->id,
            'message_type' => 'text',
            'message' => $data['message'],
        ]);
        $userName = userName('Admin', $admin_id);
        $userImageLink = asset(Auth::guard('user')->user()->image);

        // Emit the message to Socket.IO server
        emitMessageToSocket($chat->id, $admin_id, $userName, 'Admin', $userImageLink, $data['message'], 'text');
        return response()->json($data);
    }

    public function sendMessage(Request $request, $id)
    {
        $data = $request->all();
        $chat = Chat::find($id);
        $admin_id = Auth::guard('user')->user()->id;
        $chatAttendee = ChatAttendee::where('chat_id', $chat->id)->where('user_type', 'Admin')->where('user_id', $admin_id)->first();
        if (!$chatAttendee) {
            $chatAttendee = ChatAttendee::create([
                'chat_id' => $chat->id,
                'user_id' => $admin_id,
                'user_type' => 'Admin',
                'is_owner' => 1
            ]);
        }
        ChatMessage::create([
            'chat_id' => $id,
            'chat_attendee_id' => $chatAttendee->id,
            'message_type' => 'text',
            'message' => $data['message'],
        ]);
        $userName = userName('Admin', $admin_id);
        $userImageLink = asset(Auth::guard('user')->user()->image);


        // Emit the message to Socket.IO server
        emitMessageToSocket($chat->id, $admin_id, $userName, 'Admin', $userImageLink, $data['message'], 'text');

        // return redirect()->route('admin.chat.show', $id);
        // Redirect or return success response
        return redirect()->back()->with('success', 'Message sent!');
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
            $self_id = Auth::guard('user')->user()->id;
            if ($value->chatAttendee->user_type == 'Admin' && $value->chatAttendee->user_id == $self_id) {
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
