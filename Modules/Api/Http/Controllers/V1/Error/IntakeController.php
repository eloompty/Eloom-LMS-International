<?php

namespace Modules\Api\Http\Controllers\V1\Error;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Course\Entities\Unit;
use Modules\Intake\Entities\IntakeCourse;
use Modules\Intake\Entities\IntakeUnit;
use Modules\Student\Entities\StudentIntakeCourse;
use Modules\Student\Entities\StudentIntakeUnit;
use Modules\Trainer\Entities\TrainerIntake;

class IntakeController extends Controller
{
    /**
     * @OA\Post(
     ** path="/intake/course",
     *   tags={"Intakes"},
     *   summary="Add missing intake unit",
     *   description="Add missing intake unit",
     *   operationId="intakeCourse",
     *   @OA\Parameter(
     *      name="intake_course_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer",
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="unit_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer",
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="Not Found"
     *   ),
     *   @OA\Response(
     *      response=403,
     *      description="Forbidden"
     *    ),
     *   @OA\Response(
     *      response=405,
     *      description="Method not allowed"
     *    ),
     *   @OA\Response(
     *      response=401,
     *      description="Unathorized"
     *    ),
     *   @OA\Response(
     *      response=422,
     *      description="Unprocessable Entity"
     *    ),
     *)
     **/
    public function intakeCourse(Request $request)
    {
        $data = $request->all();
        $intake_course = IntakeCourse::find($data['intake_course_id']);
        $unit = Unit::find($data['unit_id']);
        if ($intake_course && $unit) {
            $intake_unit = IntakeUnit::where('intake_course_id', $data['intake_course_id'])->where('unit_id', $data['unit_id'])->first();
            if ($intake_unit) {
                $response =  [
                    'status' => false,
                    'status_code' => 422,
                    'message' => 'Already exists'
                ];
                return response($response, 422);
            } else {
                $data['status'] = 3;
                $intake_unit = IntakeUnit::create($data);
                if ($intake_course->trainer_id != NULL) {
                    TrainerIntake::create([
                        'trainer_id' => $intake_course->trainer_id,
                        'intake_course_id' => $data['intake_course_id'],
                        'intake_semester_id' => $intake_unit->intake_semester_id,
                        'intake_subject_id' => $intake_unit->intake_subject_id,
                        'intake_unit_id' => $intake_unit->id,
                        'duration' => $unit->duration,
                        'status' => 3
                    ]);
                }
                $studentIntakeCourse = StudentIntakeCourse::where('intake_course_id', $data['intake_course_id'])->get();
                foreach ($studentIntakeCourse as $key => $value) {
                    StudentIntakeUnit::create([
                        'student_intake_course_id' => $value->id,
                        'intake_unit_id' => $intake_unit->id,
                        'duration' => $unit->duration,
                        'status' => 3
                    ]);
                }
                $response =  [
                    'status' => false,
                    'status_code' => 200,
                    'message' => 'Successfully added'
                ];
                return response($response, 200);
            }
        } else {
            $response =  [
                'status' => false,
                'status_code' => 422,
                'message' => 'Invalid intake_course_id/unit_id'
            ];
            return response($response, 422);
        }
    }
}
