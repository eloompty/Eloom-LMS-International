{{-- Dynamic block: selected intake courses --}}
<table cellspacing="0" cellpadding="1" border="1" width="700px">
    <tr>
        <td class="smallf tdbg" width="50" height="30"> &nbsp;<b>Course Code</b></td>
        <td class="smallf tdbg" width="150"> &nbsp;<b>Course</b></td>
        <td class="smallf tdbg" width="45"> &nbsp;<b>CRICOS Course Code</b></td>
        <td class="smallf tdbg" width="50"> &nbsp;<b>Start Date</b></td>
        <td class="smallf tdbg" width="50"> &nbsp;<b>Finish Date</b></td>
        <td class="smallf tdbg" width="60"> &nbsp;<b>Duration</b></td>
    </tr>
    @foreach($student_courses as $course)
    <tr>
        <td class="smallf" width="50" height="25">{{ $course->intakeCourse->course->course_code }}</td>
        <td class="smallf" width="150"> {{ $course->intakeCourse->course->course_name }}</td>
        <td class="smallf" width="45"> {{ $course->intakeCourse->course->cricos_code }}</td>
        <td class="smallf" width="50"> {{ dateFormat($course->intakeCourse->starting_date) }}</td>
        <td class="smallf" width="50"> {{ dateFormat($course->intakeCourse->ending_date) }}</td>
        <td class="smallf" width="60"> {{ $course->intakeCourse->duration }} Weeks</td>
    </tr>
    @endforeach
    @if(!empty($letter->credit_description))
    <tr>
        <td class="smallf" colspan="6">{{ $letter->credit_description }}</td>
    </tr>
    @endif
</table>
