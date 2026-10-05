@extends('user::layouts.master')
@section('title', 'Admin | Add Intake Course')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Add Intake Course</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.index') }}">Intakes</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.intake.course.index', $intake->id) }}">Courses</a></li>
                    <li class="breadcrumb-item active">Add</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- form start -->
        <form id="addintakecourse" action="{{ route('admin.intake.course.store', $intake->id) }}" method="POST">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Add {{ $intake->name }} Course</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="course_id">Course</label> <span class="required">*</span>
                                    <select class="form-control" name="course_id" id="course">
                                        <option value="">-- Select Course --</option>
                                        @foreach($courses as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="reference_name">Reference Name</label> <span class="required">*</span>
                                    <input type="text" name="reference_name" class="form-control" id="reference_name" placeholder="Enter Reference Name">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="starting_date">Starting Date</label> <span class="required">*</span>
                                    <input type="date" name="starting_date" class="form-control" id="starting_date" placeholder="Enter Starting Date">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="ending_date">Ending Date</label> <span class="required">*</span>
                                    <input type="date" name="ending_date" class="form-control" id="ending_date" placeholder="Enter Ending Date">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="marking_type">Marking Type</label> <span class="required">*</span>
                                    <select name="marking_type" class="form-control" id="marking_type">
                                        <option value="" selected disabled>-- Select Marking Type --</option>
                                        <option value="Subject">Subject</option>
                                        <option value="Unit">Unit</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="trainer_id">Teacher</label>
                                    <select class="form-control" name="trainer_id" id="trainer">
                                        <option value="">-- Select Teacher --</option>
                                        @foreach($trainers as $key => $value)
                                        <option value="{{ $value->id }}">{{ userName('Trainer', $value->id) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="status">Status</label> <span class="required">*</span>
                                    <select name="status" class="form-control" id="status">
                                        <option value="" disabled>-- Select Status --</option>
                                        <option value="1" selected>Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                                <div class="col-12"><hr><h6 class="text-muted">Offer Letter Course Details</h6></div>
                                <div class="form-group col-md-3">
                                    <label for="study_mode">Study Mode</label>
                                    <input type="text" name="study_mode" class="form-control" id="study_mode" placeholder="e.g. Face to Face">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="study_location">Study Location</label>
                                    <input type="text" name="study_location" class="form-control" id="study_location" placeholder="Enter Study Location">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="work_placement">Work Placement Required</label>
                                    <input type="text" name="work_placement" class="form-control" id="work_placement" placeholder="e.g. No work placement required">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="hours_per_week">Hours Per Week</label>
                                    <input type="text" name="hours_per_week" class="form-control" id="hours_per_week" placeholder="e.g. 20 hours per week">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="holiday_breaks">Holiday Breaks</label>
                                    <input type="text" name="holiday_breaks" class="form-control" id="holiday_breaks" placeholder="Enter Holiday Breaks">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="entry_requirements">Entry Requirements</label>
                                    <input type="text" name="entry_requirements" class="form-control" id="entry_requirements" placeholder="Enter Entry Requirements">
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!--/.col (left) -->
            </div>
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- Semester Table -->
                    <table id="semester" class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
                                <th>Credits</th>
                                <th>Starting Date</th>
                                <th>Ending Date</th>
                                <th>Due Date</th>
                                <th>Sequence</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Each Semester row will contain a nested subject table -->
                        </tbody>
                    </table>
                    <!-- /.card -->
                </div>
                <!--/.col (left) -->
            </div>
            <!-- /.row -->
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection

@section('scripts')
<!-- jquery-validation -->
<script src="{{ asset('themes/AdminLTE/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('themes/AdminLTE/plugins/jquery-validation/additional-methods.min.js') }}"></script>

<script>
    $(function() {
        $('#addintakecourse').validate({
            rules: {
                course_id: {
                    required: true,
                },
                reference_name: {
                    required: true,
                },
                starting_date: {
                    required: true,
                },
                ending_date: {
                    required: true,
                },
                marking_type: {
                    required: true
                },
                status: {
                    required: true
                },
            },
            messages: {
                course_id: "Please select one course",
                reference_name: "Please enter reference",
                starting_date: "Please enter starting date",
                ending_date: "Please enter ending date",
                marking_type: "Please select one marking type",
                status: "Please select one status",
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });

    $(document).ready(function() {
        $('#course').on('change', function() {
            var id = $('#course').val();
            $.ajax({
                'url': "{{url('admin/intake/course/semester/get')}}?course_id=" + id,
                'method': "GET",
                'contentType': 'application/json',
            }).done(function(data) {
                let semesters = data;
                var table = $('#semester').DataTable({
                    "lengthMenu": [200],
                    "bPaginate": false,
                    "aaData": semesters,
                    "columns": [{
                            "data": "id",
                            render: function(data, type, row) {
                                return '<input class="form-control trackStartingDate" id="semester_id" name="semester_id[]" type="hidden" required value =' + row.id + '>';
                            }
                        },
                        {
                            "data": "name"
                        },
                        {
                            "data": "credits"
                        },
                        {
                            "data": "starting_date",
                            render: function(data, type, row) {
                                return '<input class="form-control trackStartingDate" id="starting-date" name="semester_starting_date[]" type="date" value =' + row.starting_date + '>';
                            }
                        },
                        {
                            "data": "ending_date",
                            render: function(data, type, row) {
                                return '<input class="form-control trackEndingDate" id="ending-date" name="semester_ending_date[]" type="date" value =' + row.ending_date + '>';
                            }
                        },
                        {
                            "data": "due_date",
                            render: function(data, type, row) {
                                return '<input class="form-control trackDueDate" id="due-date" name="semester_due_date[]" type="date" value =' + row.due_date + '>';
                            }
                        },
                        {
                            "data": "sequence",
                            render: function(data, type, row) {
                                return '<input class="form-control trackSequence" id="sequence" name="semester_sequence[]" type="number" value =' + row.sequence + '>';
                            }
                        },
                        {
                            "data": "status",
                            "class": "td-status",
                            "name": "semester_status[]",
                            "render": function(val, type, row) {
                                return createSelect(val);
                            }
                        }
                    ],
                    columnDefs: [{
                        "defaultContent": "-",
                        "targets": "_all"
                    }],
                    "bDestroy": true,
                    "drawCallback": function(settings) {
                        $(".td-status").on("change", function() {
                            var $row = $(this).parents("tr");
                            var rowData = table.row($row).data();
                            rowData.MarkupValue = $(this).val();
                        });

                        // Append Subject Table
                        $('#semester tbody tr').each(function() {
                            var semesterID = $(this).find('input[name="semester_id[]"]').val();
                            appendSubjectTable($(this), semesterID);
                        });
                    }
                });
            });
        });

        function appendSubjectTable($semesterRow, semesterID) {
            // Add Subject Table after each semester row
            $semesterRow.after(`
                <tr>
                    <td colspan="8">
                        <table id="subject_${semesterID}" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Credits</th>
                                    <th>Starting Date</th>
                                    <th>Ending Date</th>
                                    <th>Due Date</th>
                                    <th>Sequence</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Subject rows will be populated here -->
                            </tbody>
                        </table>
                    </td>
                </tr>
            `);

            // Fetch and populate subjects
            fetchSubjectsForSemester(semesterID);
        }

        function fetchSubjectsForSemester(semesterID) {
            $.ajax({
                'url': "{{url('admin/intake/course/semester/subject/get')}}?semester_id=" + semesterID,
                'method': "GET",
                'contentType': 'application/json',
            }).done(function(data) {
                let subjects = data;
                var sem_id = semesterID;
                var table = $('#subject_' + semesterID).DataTable({
                    "lengthMenu": [200],
                    "bPaginate": false,
                    "aaData": subjects,
                    "columns": [{
                            "data": "id",
                            render: function(data, type, row) {
                                return '<input class="form-control" id="subject_id" name="semester[' + sem_id + '][' + row.id + '][subject_id]" type="hidden" required value =' + row.id + '>';
                            }
                        },
                        {
                            "data": "name"
                        },
                        {
                            "data": "credits"
                        },
                        {
                            "data": "starting_date",
                            render: function(data, type, row) {
                                return '<input class="form-control trackStartingDate" id="starting-date" name="semester[' + sem_id + '][' + row.id + '][subject_starting_date]" type="date" value =' + row.starting_date + '>';
                            }
                        },
                        {
                            "data": "ending_date",
                            render: function(data, type, row) {
                                return '<input class="form-control trackEndingDate" id="ending-date" name="semester[' + sem_id + '][' + row.id + '][subject_ending_date]" type="date" value =' + row.ending_date + '>';
                            }
                        },
                        {
                            "data": "due_date",
                            render: function(data, type, row) {
                                return '<input class="form-control trackDueDate" id="due-date" name="semester[' + sem_id + '][' + row.id + '][subject_due_date]" type="date" value =' + row.due_date + '>';
                            }
                        },
                        {
                            "data": "sequence",
                            render: function(data, type, row) {
                                return '<input class="form-control trackSequence" id="sequence" name="semester[' + sem_id + '][' + row.id + '][subject_sequence]" type="number" value =' + row.sequence + '>';
                            }
                        },
                    ],
                    columnDefs: [{
                        "defaultContent": "-",
                        "targets": "_all"
                    }],
                    "bDestroy": true,
                    "drawCallback": function(settings) {
                        $(".td-status").on("change", function() {
                            var $row = $(this).parents("tr");
                            var rowData = table.row($row).data();
                            rowData.MarkupValue = $(this).val();
                        });

                        // Append Unit Table
                        $('#subject_' + semesterID + ' tbody tr').each(function() {
                            var subjectID = $(this).find('input[name="subject_id[]"]').val();
                            appendUnitTable($(this), subjectID);
                        });
                    }
                });
            });
        }

        // function appendUnitTable($subjectRow, subjectID) {
        //     // Add Unit Table after each subject row
        //     $subjectRow.after(`
        //         <tr>
        //             <td colspan="8">
        //                 <table id="unit_${subjectID}" class="table table-striped table-hover">
        //                     <thead>
        //                         <tr>
        //                             <th>Id</th>
        //                             <th>Name</th>
        //                             <th>Code</th>
        //                             <th>Starting Date</th>
        //                             <th>Ending Date</th>
        //                             <th>Due Date</th>
        //                             <th>Sequence</th>
        //                             <th>Status</th>
        //                         </tr>
        //                     </thead>
        //                     <tbody>
        //                         <!-- Unit rows will be populated here -->
        //                     </tbody>
        //                 </table>
        //             </td>
        //         </tr>
        //     `);

        //     // Fetch and populate units
        //     fetchUnitsForSubject(subjectID);
        // }

        // function fetchUnitsForSubject(subjectID) {
        //     $.ajax({
        //         'url': "{{url('admin/intake/course/semester/subject/unit/get')}}?subject_id=" + subjectID,
        //         'method': "GET",
        //         'contentType': 'application/json',
        //     }).done(function(data) {
        //         let units = data;
        //         var table = $('#unit_' + subjectID).DataTable({
        //             "lengthMenu": [ 200 ],
        //             "bPaginate": false,
        //             "aaData": units,
        //             "columns": [
        //                 {
        //                     "data": "id",
        //                     render: function(data, type, row) {
        //                         return '<input class="form-control" id="unit_id" name="unit_id[]" type="hidden" required value =' + row.id + '>';
        //                     }
        //                 },
        //                 {
        //                     "data": "name"
        //                 },
        //                 {
        //                     "data": "code"
        //                 },
        //                 {
        //                     "data": "starting_date",
        //                     render: function(data, type, row) {
        //                         return '<input class="form-control trackStartingDate" id="starting-date" name="unit_starting_date[]" type="date" value =' + row.starting_date + '>';
        //                     }
        //                 },
        //                 {
        //                     "data": "ending_date",
        //                     render: function(data, type, row) {
        //                         return '<input class="form-control trackEndingDate" id="ending-date" name="unit_ending_date[]" type="date" value =' + row.ending_date + '>';
        //                     }
        //                 },
        //                 {
        //                     "data": "due_date",
        //                     render: function(data, type, row) {
        //                         return '<input class="form-control trackDueDate" id="due-date" name="unit_due_date[]" type="date" value =' + row.due_date + '>';
        //                     }
        //                 },
        //                 {
        //                     "data": "sequence",
        //                     render: function(data, type, row) {
        //                         return '<input class="form-control trackSequence" id="sequence" name="unit_sequence[]" type="number" value =' + row.sequence + '>';
        //                     }
        //                 },
        //                 {
        //                     "data": "status",
        //                     "class": "td-status",
        //                     "name": "unit_status[]",
        //                     "render": function(val, type, row) {
        //                         return createSelect(val);
        //                     }
        //                 }
        //             ],
        //             columnDefs: [{
        //                 "defaultContent": "-",
        //                 "targets": "_all"
        //             }],
        //             "bDestroy": true,
        //             "drawCallback": function(settings) {
        //                 $(".td-status").on("change", function() {
        //                     var $row = $(this).parents("tr");
        //                     var rowData = table.row($row).data();
        //                     rowData.MarkupValue = $(this).val();
        //                 });
        //             }
        //         });
        //     });
        // }

        function createSelect(selItem) {
            var offices = [0, 1, 3];
            var valuesShown = ['InActive', 'Active', 'Locked'];
            var sel = "<select name='semester_status[]'><option>Select Status</option>";
            for (var i = 0; i < offices.length; ++i) {
                if (offices[i] == selItem) {
                    sel += "<option selected value = '" + offices[i] + "' >" + valuesShown[i] + "</option>";
                } else {
                    sel += "<option  value = '" + offices[i] + "' >" + valuesShown[i] + "</option>";
                }
            }
            sel += "</select>";
            return sel;
        }
    });
</script>
@endsection