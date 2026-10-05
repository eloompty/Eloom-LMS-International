{{-- Dynamic block: subjects of each selected intake course, grouped per course --}}
@foreach($course_subjects as $group)
<table cellspacing="0" cellpadding="1" border="1" width="700px">
    <tr>
        <td class="smallf tdbg" colspan="5"><b>{{ $group['course_name'] }}</b></td>
    </tr>
    <tr>
        <td class="smallf tdbg" width="60" height="25"> &nbsp;<b>Subject Code</b></td>
        <td class="smallf tdbg" width="220"> &nbsp;<b>Subject</b></td>
        <td class="smallf tdbg" width="70"> &nbsp;<b>Start Date</b></td>
        <td class="smallf tdbg" width="70"> &nbsp;<b>Finish Date</b></td>
        <td class="smallf tdbg" width="90"> &nbsp;<b>Credits / Hours</b></td>
    </tr>
    @forelse($group['subjects'] as $is)
    <tr>
        <td class="smallf" width="60"> &nbsp;{{ optional($is->subject)->code }}</td>
        <td class="smallf" width="220"> &nbsp;{{ optional($is->subject)->name }}</td>
        <td class="smallf" width="70"> &nbsp;@if($is->starting_date){{ dateFormat($is->starting_date) }}@else - @endif</td>
        <td class="smallf" width="70"> &nbsp;@if($is->ending_date){{ dateFormat($is->ending_date) }}@else - @endif</td>
        <td class="smallf" width="90"> &nbsp;{{ optional($is->subject)->credits ?: '-' }} / {{ optional($is->subject)->teaching_hours ?: '-' }}</td>
    </tr>
    @empty
    <tr>
        <td class="smallf" colspan="5"> &nbsp;No subjects found for this course.</td>
    </tr>
    @endforelse
</table>
<br>
@endforeach
