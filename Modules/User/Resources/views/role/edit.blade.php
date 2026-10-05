@extends('user::layouts.master')
@section('title', 'Admin | Edit Role')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Role</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    @if (Auth::user('admin')->theme == 'theme2' || Auth::user('admin')->theme == 'theme3')
                    <li class="breadcrumb-item"><a href="{{ route('admin.setting.menu') }}">Settings Menu</a></li>
                    @endif
                    <li class="breadcrumb-item"><a href="{{ route('admin.role.index') }}">Roles</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- form start -->
        <form id="addrole" action="{{ route('admin.role.update', $role->id) }}" method="POST">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Edit Role</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name">Name</label> <span class="required">*</span>
                                <input type="text" name="name" class="form-control" id="name" placeholder="Enter Name" value="{{ $role->name }}">
                            </div>
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Menu</th>
                                        <th>View</th>
                                        <th>Add</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Company</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" id="checkboxInfo1" name="action[company][]" @if(in_array('company.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfo1" class="check view"></label>
                                            </div>
                                        </td>
                                        <td></td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" id="checkboxPrimary1" name="action[company][]" @if(in_array('company.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimary1" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Company Delivery Site</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" id="checkboxInfo2" name="action[company_delivery_site][]" @if(in_array('company_delivery_site.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfo2" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" id="checkboxSuccess2" name="action[company_delivery_site][]" @if(in_array('company_delivery_site.add', $user_role)) checked @endif value="add">
                                                <label for="checkboxSuccess2" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" id="checkboxPrimary2" name="action[company_delivery_site][]" @if(in_array('company_delivery_site.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimary2" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" id="checkboxDanger2" name="action[company_delivery_site][]" @if(in_array('company_delivery_site.delete', $user_role)) checked @endif value="delete">
                                                <label for="checkboxDanger2" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Course</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" id="checkboxInfo3" name="action[course][]" @if(in_array('course.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfo3" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" id="checkboxSuccess3" name="action[course][]" @if(in_array('course.add', $user_role)) checked @endif value="add">
                                                <label for="checkboxSuccess3" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" id="checkboxPrimary3" name="action[course][]" @if(in_array('course.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimary3" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" id="checkboxDanger3" name="action[course][]" @if(in_array('course.delete', $user_role)) checked @endif value="delete">
                                                <label for="checkboxDanger3" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Semester</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoSemester" name="action[semester][]" @if(in_array('semester.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfoSemester" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccessSemester" name="action[semester][]" @if(in_array('semester.add', $user_role)) checked @endif  value="add">
                                                <label for="checkboxSuccessSemester" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimarySemester" name="action[semester][]" @if(in_array('semester.edit', $user_role)) checked @endif  value="edit">
                                                <label for="checkboxPrimarySemester" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDangerSemester" name="action[semester][]" @if(in_array('semester.delete', $user_role)) checked @endif  value="delete">
                                                <label for="checkboxDangerSemester" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Subject</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoSubject" name="action[subject][]" @if(in_array('subject.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfoSubject" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccessSubject" name="action[subject][]" @if(in_array('subject.add', $user_role)) checked @endif  value="add">
                                                <label for="checkboxSuccessSubject" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimarySubject" name="action[subject][]" @if(in_array('subject.edit', $user_role)) checked @endif  value="edit">
                                                <label for="checkboxPrimarySubject" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDangerSubject" name="action[subject][]" @if(in_array('subject.delete', $user_role)) checked @endif  value="delete">
                                                <label for="checkboxDangerSubject" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Unit</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" id="checkboxInfo4" name="action[unit][]" @if(in_array('unit.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfo4" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" id="checkboxSuccess4" name="action[unit][]" @if(in_array('unit.add', $user_role)) checked @endif value="add">
                                                <label for="checkboxSuccess4" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" id="checkboxPrimary4" name="action[unit][]" @if(in_array('unit.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimary4" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" id="checkboxDanger4" name="action[unit][]" @if(in_array('unit.delete', $user_role)) checked @endif value="delete">
                                                <label for="checkboxDanger4" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Unit Resource</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" id="checkboxInfo5" name="action[unit_resource][]" @if(in_array('unit_resource.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfo5" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" id="checkboxSuccess5" name="action[unit_resource][]" @if(in_array('unit_resource.add', $user_role)) checked @endif value="add">
                                                <label for="checkboxSuccess5" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" id="checkboxPrimary5" name="action[unit_resource][]" @if(in_array('unit_resource.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimary5" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" id="checkboxDanger5" name="action[unit_resource][]" @if(in_array('unit_resource.delete', $user_role)) checked @endif value="delete">
                                                <label for="checkboxDanger5" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Unit Assignment</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" id="checkboxInfo28" name="action[unit_assignment][]" @if(in_array('unit_assignment.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfo28" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" id="checkboxSuccess28" name="action[unit_assignment][]" @if(in_array('unit_assignment.add', $user_role)) checked @endif value="add">
                                                <label for="checkboxSuccess28" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" id="checkboxPrimary28" name="action[unit_assignment][]" @if(in_array('unit_assignment.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimary28" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" id="checkboxDanger28" name="action[unit_assignment][]" @if(in_array('unit_assignment.delete', $user_role)) checked @endif value="delete">
                                                <label for="checkboxDanger28" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Unit Fee</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoUnitFee" name="action[unit_fee][]" @if(in_array('unit_fee.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfoUnitFee" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccessUnitFee" name="action[unit_fee][]" @if(in_array('unit_fee.add', $user_role)) checked @endif value="add">
                                                <label for="checkboxSuccessUnitFee" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimaryUnitFee" name="action[unit_fee][]" @if(in_array('unit_fee.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimaryUnitFee" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDangerUnitFee" name="action[unit_fee][]" @if(in_array('unit_fee.delete', $user_role)) checked @endif value="delete">
                                                <label for="checkboxDangerUnitFee" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Intake</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" id="checkboxInfo6" name="action[intake][]" @if(in_array('intake.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfo6" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" id="checkboxSuccess6" name="action[intake][]" @if(in_array('intake.add', $user_role)) checked @endif value="add">
                                                <label for="checkboxSuccess6" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" id="checkboxPrimary6" name="action[intake][]" @if(in_array('intake.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimary6" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" id="checkboxDanger6" name="action[intake][]" @if(in_array('intake.delete', $user_role)) checked @endif value="delete">
                                                <label for="checkboxDanger6" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Intake Course</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" id="checkboxInfo7" name="action[intake_course][]" @if(in_array('intake_course.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfo7" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" id="checkboxSuccess7" name="action[intake_course][]" @if(in_array('intake_course.add', $user_role)) checked @endif value="add">
                                                <label for="checkboxSuccess7" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" id="checkboxPrimary7" name="action[intake_course][]" @if(in_array('intake_course.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimary7" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" id="checkboxDanger7" name="action[intake_course][]" @if(in_array('intake_course.delete', $user_role)) checked @endif value="delete">
                                                <label for="checkboxDanger7" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Intake Course Time Table</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" id="checkboxInfo8" name="action[intake_course_time_table][]" @if(in_array('intake_course_time_table.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfo8" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" id="checkboxSuccess8" name="action[intake_course_time_table][]" @if(in_array('intake_course_time_table.add', $user_role)) checked @endif value="add">
                                                <label for="checkboxSuccess8" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" id="checkboxPrimary8" name="action[intake_course_time_table][]" @if(in_array('intake_course_time_table.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimary8" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" id="checkboxDanger8" name="action[intake_course_time_table][]" @if(in_array('intake_course_time_table.delete', $user_role)) checked @endif value="delete">
                                                <label for="checkboxDanger8" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Social Category</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" id="checkboxInfo9" name="action[social_category][]" @if(in_array('social_category.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfo9" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" id="checkboxSuccess9" name="action[social_category][]" @if(in_array('social_category.add', $user_role)) checked @endif value="add">
                                                <label for="checkboxSuccess9" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" id="checkboxPrimary9" name="action[social_category][]" @if(in_array('social_category.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimary9" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" id="checkboxDanger9" name="action[social_category][]" @if(in_array('social_category.delete', $user_role)) checked @endif value="delete">
                                                <label for="checkboxDanger9" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Student</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" id="checkboxInfo10" name="action[student][]" @if(in_array('student.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfo10" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" id="checkboxSuccess10" name="action[student][]" @if(in_array('student.add', $user_role)) checked @endif value="add">
                                                <label for="checkboxSuccess10" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" id="checkboxPrimary10" name="action[student][]" @if(in_array('student.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimary10" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" id="checkboxDanger10" name="action[student][]" @if(in_array('student.delete', $user_role)) checked @endif value="delete">
                                                <label for="checkboxDanger10" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Student Intake</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" id="checkboxInfo11" name="action[student_intake_course][]" @if(in_array('student_intake_course.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfo11" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" id="checkboxSuccess11" name="action[student_intake_course][]" @if(in_array('student_intake_course.add', $user_role)) checked @endif value="add">
                                                <label for="checkboxSuccess11" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" id="checkboxPrimary11" name="action[student_intake_course][]" @if(in_array('student_intake_course.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimary11" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" id="checkboxDanger11" name="action[student_intake_course][]" @if(in_array('student_intake_course.delete', $user_role)) checked @endif value="delete">
                                                <label for="checkboxDanger11" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Student Intake Unit</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" id="checkboxInfo27" name="action[student_intake_unit][]" @if(in_array('student_intake_unit.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfo27" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" id="checkboxPrimary27" name="action[student_intake_unit][]" @if(in_array('student_intake_unit.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimary27" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" id="checkboxDanger27" name="action[student_intake_unit][]" @if(in_array('student_intake_unit.delete', $user_role)) checked @endif value="delete">
                                                <label for="checkboxDanger27" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Student Social</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" id="checkboxInfo12" name="action[student_social][]" @if(in_array('student_social.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfo12" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" id="checkboxSuccess12" name="action[student_social][]" @if(in_array('student_social.add', $user_role)) checked @endif value="add">
                                                <label for="checkboxSuccess12" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" id="checkboxPrimary12" name="action[student_social][]" @if(in_array('student_social.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimary12" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" id="checkboxDanger12" name="action[student_social][]" @if(in_array('student_social.delete', $user_role)) checked @endif value="delete">
                                                <label for="checkboxDanger12" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Student Dashboard</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" id="checkboxInfoDashboard" name="action[student_dashboard][]" @if(in_array('student_dashboard.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfoDashboard" class="check view"></label>
                                            </div>
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Trainer</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" id="checkboxInfo13" name="action[trainer][]" @if(in_array('trainer.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfo13" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" id="checkboxSuccess13" name="action[trainer][]" @if(in_array('trainer.add', $user_role)) checked @endif value="add">
                                                <label for="checkboxSuccess13" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" id="checkboxPrimary13" name="action[trainer][]" @if(in_array('trainer.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimary13" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" id="checkboxDanger13" name="action[trainer][]" @if(in_array('trainer.delete', $user_role)) checked @endif value="delete">
                                                <label for="checkboxDanger13" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Trainer Intake</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" id="checkboxInfo14" name="action[trainer_intake][]" @if(in_array('trainer_intake.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfo14" class="check view"></label>
                                            </div>
                                        </td>
                                        <td></td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" id="checkboxPrimary14" name="action[trainer_intake][]" @if(in_array('trainer_intake.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimary14" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" id="checkboxDanger14" name="action[trainer_intake][]" @if(in_array('trainer_intake.delete', $user_role)) checked @endif value="delete">
                                                <label for="checkboxDanger14" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Trainer Qualification</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" id="checkboxInfo15" name="action[trainer_qualification][]" @if(in_array('trainer_qualification.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfo15" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" id="checkboxSuccess15" name="action[trainer_qualification][]" @if(in_array('trainer_qualification.add', $user_role)) checked @endif value="add">
                                                <label for="checkboxSuccess15" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" id="checkboxPrimary15" name="action[trainer_qualification][]" @if(in_array('trainer_qualification.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimary15" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" id="checkboxDanger15" name="action[trainer_qualification][]" @if(in_array('trainer_qualification.delete', $user_role)) checked @endif value="delete">
                                                <label for="checkboxDanger15" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Trainer Professional Development</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" id="checkboxInfo16" name="action[trainer_professional_development][]" @if(in_array('trainer_professional_development.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfo16" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" id="checkboxSuccess16" name="action[trainer_professional_development][]" @if(in_array('trainer_professional_development.add', $user_role)) checked @endif value="add">
                                                <label for="checkboxSuccess16" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" id="checkboxPrimary16" name="action[trainer_professional_development][]" @if(in_array('trainer_professional_development.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimary16" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" id="checkboxPrimary16" name="action[trainer_professional_development][]" @if(in_array('trainer_professional_development.delete', $user_role)) checked @endif value="delete">
                                                <label for="checkboxDanger16" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Trainer Work Placement</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" id="checkboxInfo17" name="action[trainer_placement][]" @if(in_array('trainer_placement.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfo17" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" id="checkboxSuccess17" name="action[trainer_placement][]" @if(in_array('trainer_placement.add', $user_role)) checked @endif value="add">
                                                <label for="checkboxSuccess17" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" id="checkboxPrimary17" name="action[trainer_placement][]" @if(in_array('trainer_placement.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimary17" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" id="checkboxDanger17" name="action[trainer_placement][]" @if(in_array('trainer_placement.delete', $user_role)) checked @endif value="delete">
                                                <label for="checkboxDanger17" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Trainer Dashboard</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" id="checkboxInfoTrainerDashboard" name="action[trainer_dashboard][]" @if(in_array('trainer_dashboard.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfoTrainerDashboard" class="check view"></label>
                                            </div>
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>University</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" id="checkboxInfo18" name="action[university][]" @if(in_array('university.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfo18" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" id="checkboxSuccess18" name="action[university][]" @if(in_array('university.add', $user_role)) checked @endif value="add">
                                                <label for="checkboxSuccess18" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" id="checkboxPrimary18" name="action[university][]" @if(in_array('university.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimary18" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" id="checkboxDanger18" name="action[university][]" @if(in_array('university.delete', $user_role)) checked @endif value="delete">
                                                <label for="checkboxDanger18" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>University Qualification</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" id="checkboxInfo19" name="action[university_qualification][]" @if(in_array('university_qualification.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfo19" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" id="checkboxSuccess19" name="action[university_qualification][]" @if(in_array('university_qualification.add', $user_role)) checked @endif value="add">
                                                <label for="checkboxSuccess19" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" id="checkboxPrimary19" name="action[university_qualification][]" @if(in_array('university_qualification.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimary19" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Resource</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" id="checkboxInfo20" name="action[resource][]" @if(in_array('resource.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfo20" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" id="checkboxSuccess20" name="action[resource][]" @if(in_array('resource.add', $user_role)) checked @endif value="add">
                                                <label for="checkboxSuccess20" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" id="checkboxPrimary20" name="action[resource][]" @if(in_array('resource.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimary20" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" id="checkboxDanger20" name="action[resource][]" @if(in_array('resource.delete', $user_role)) checked @endif value="delete">
                                                <label for="checkboxDanger20" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Resource Category</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" id="checkboxInfo21" name="action[resource_category][]" @if(in_array('resource_category.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfo21" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" id="checkboxSuccess21" name="action[resource_category][]" @if(in_array('resource_category.add', $user_role)) checked @endif value="add">
                                                <label for="checkboxSuccess21" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" id="checkboxPrimary21" name="action[resource_category][]" @if(in_array('resource_category.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimary21" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" id="checkboxDanger21" name="action[resource_category][]" @if(in_array('resource_category.delete', $user_role)) checked @endif value="delete">
                                                <label for="checkboxDanger21" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Role</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" id="checkboxInfo22" name="action[role][]" @if(in_array('role.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfo22" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" id="checkboxSuccess22" name="action[role][]" @if(in_array('role.add', $user_role)) checked @endif value="add">
                                                <label for="checkboxSuccess22" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" id="checkboxPrimary22" name="action[role][]" @if(in_array('role.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimary22" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>User</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" id="checkboxInfo23" name="action[user][]" @if(in_array('user.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfo23" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" id="checkboxSuccess23" name="action[user][]" @if(in_array('user.add', $user_role)) checked @endif value="add">
                                                <label for="checkboxSuccess23" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" id="checkboxPrimary23" name="action[user][]" @if(in_array('user.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimary23" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Assignment</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" id="checkboxInfo24" name="action[assignment][]" @if(in_array('assignment.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfo24" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" id="checkboxSuccess24" name="action[assignment][]" @if(in_array('assignment.add', $user_role)) checked @endif value="add">
                                                <label for="checkboxSuccess24" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" id="checkboxPrimary24" name="action[assignment][]" @if(in_array('assignment.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimary24" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" id="checkboxDanger24" name="action[assignment][]" @if(in_array('assignment.delete', $user_role)) checked @endif value="delete">
                                                <label for="checkboxDanger24" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Assignment Grade</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" id="checkboxInfo25" name="action[assignment_grade][]" @if(in_array('assignment.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfo25" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" id="checkboxSuccess25" name="action[assignment_grade][]" @if(in_array('assignment_grade.add', $user_role)) checked @endif value="add">
                                                <label for="checkboxSuccess25" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" id="checkboxPrimary25" name="action[assignment_grade][]" @if(in_array('assignment_grade.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimary25" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" id="checkboxDanger25" name="action[assignment_grade][]" @if(in_array('assignment_grade.delete', $user_role)) checked @endif value="delete">
                                                <label for="checkboxDanger25" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Assignment Submission</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" id="checkboxInfo26" name="action[assignment_submission][]" @if(in_array('assignment_submission.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfo26" class="check view"></label>
                                            </div>
                                        </td>
                                        <td></td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimary26" name="action[assignment_submission][]" @if(in_array('assignment_submission.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimary26" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Country</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfo29" @if(in_array('country.view', $user_role)) checked @endif name="action[country][]" value="view">
                                                <label for="checkboxInfo29" class="check view"></label>
                                            </div>
                                        </td>
                                        <td></td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimary29" @if(in_array('country.edit', $user_role)) checked @endif name="action[country][]" value="edit">
                                                <label for="checkboxPrimary29" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Settings</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfo30" @if(in_array('setting.view', $user_role)) checked @endif name="action[setting][]" value="view">
                                                <label for="checkboxInfo30" class="check view"></label>
                                            </div>
                                        </td>
                                        <td></td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimary30" @if(in_array('setting.edit', $user_role)) checked @endif name="action[setting][]" value="edit">
                                                <label for="checkboxPrimary30" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Group Online Class</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfo31" @if(in_array('online_class_group.view', $user_role)) checked @endif name="action[online_class_group][]" value="view">
                                                <label for="checkboxInfo31" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccess31" @if(in_array('online_class_group.add', $user_role)) checked @endif name="action[online_class_group][]" value="add">
                                                <label for="checkboxSuccess31" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimary31" @if(in_array('online_class_group.edit', $user_role)) checked @endif name="action[online_class_group][]" value="edit">
                                                <label for="checkboxPrimary31" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <!-- <tr>
                                        <td>Agent</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" id="checkboxInfo32" name="action[agent][]" @if(in_array('agent.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfo32" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" id="checkboxSuccess32" name="action[agent][]" @if(in_array('agent.add', $user_role)) checked @endif value="add">
                                                <label for="checkboxSuccess32" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" id="checkboxPrimary32" name="action[agent][]" @if(in_array('agent.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimary32" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" id="checkboxDanger32" name="action[agent][]" @if(in_array('agent.delete', $user_role)) checked @endif value="delete">
                                                <label for="checkboxDanger32" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Agent Branch</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" id="checkboxInfo33" name="action[agent_branch][]" @if(in_array('agent_branch.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfo33" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" id="checkboxSuccess33" name="action[agent_branch][]" @if(in_array('agent_branch.add', $user_role)) checked @endif value="add">
                                                <label for="checkboxSuccess33" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" id="checkboxPrimary33" name="action[agent_branch][]" @if(in_array('agent_branch.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimary33" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" id="checkboxDanger33" name="action[agent_branch][]" @if(in_array('agent_branch.delete', $user_role)) checked @endif value="delete">
                                                <label for="checkboxDanger33" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Agent Branch User</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" id="checkboxInfo34" name="action[agent_branch_user][]" @if(in_array('agent_branch_user.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfo34" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" id="checkboxSuccess34" name="action[agent_branch_user][]" @if(in_array('agent_branch_user.add', $user_role)) checked @endif value="add">
                                                <label for="checkboxSuccess34" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" id="checkboxPrimary34" name="action[agent_branch_user][]" @if(in_array('agent_branch_user.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimary34" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" id="checkboxDanger34" name="action[agent_branch_user][]" @if(in_array('agent_branch_user.delete', $user_role)) checked @endif value="delete">
                                                <label for="checkboxDanger34" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr> -->
                                    <tr>
                                        <td>Course Fee</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" id="checkboxInfo35" name="action[course_fee][]" @if(in_array('course_fee.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfo35" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" id="checkboxSuccess35" name="action[course_fee][]" @if(in_array('course_fee.add', $user_role)) checked @endif value="add">
                                                <label for="checkboxSuccess35" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" id="checkboxPrimary35" name="action[course_fee][]" @if(in_array('course_fee.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimary35" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" id="checkboxDanger35" name="action[course_fee][]" @if(in_array('course_fee.delete', $user_role)) checked @endif value="delete">
                                                <label for="checkboxDanger35" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Intake Course Fee</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" id="checkboxInfo36" name="action[intake_course_fee][]" @if(in_array('intake_course_fee.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfo36" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" id="checkboxSuccess36" name="action[intake_course_fee][]" @if(in_array('intake_course_fee.add', $user_role)) checked @endif value="add">
                                                <label for="checkboxSuccess36" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" id="checkboxPrimary36" name="action[intake_course_fee][]" @if(in_array('intake_course_fee.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimary36" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" id="checkboxDanger36" name="action[intake_course_fee][]" @if(in_array('intake_course_fee.delete', $user_role)) checked @endif value="delete">
                                                <label for="checkboxDanger36" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Student Intake Course Fee</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" id="checkboxInfo37" name="action[student_intake_course_fee][]" @if(in_array('student_intake_course_fee.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfo37" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" id="checkboxSuccess37" name="action[student_intake_course_fee][]" @if(in_array('student_intake_course_fee.add', $user_role)) checked @endif value="add">
                                                <label for="checkboxSuccess37" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" id="checkboxPrimary37" name="action[student_intake_course_fee][]" @if(in_array('student_intake_course_fee.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimary37" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" id="checkboxDanger37" name="action[student_intake_course_fee][]" @if(in_array('student_intake_course_fee.delete', $user_role)) checked @endif value="delete">
                                                <label for="checkboxDanger37" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Student Intake Unit Fee</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoStudentIntakeUnitFee" name="action[student_intake_unit_fee][]" @if(in_array('student_intake_unit_fee.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfoStudentIntakeUnitFee" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccessStudentIntakeUnitFee" name="action[student_intake_unit_fee][]" @if(in_array('student_intake_unit_fee.add', $user_role)) checked @endif value="add">
                                                <label for="checkboxSuccessStudentIntakeUnitFee" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimaryStudentIntakeUnitFee" name="action[student_intake_unit_fee][]" @if(in_array('student_intake_unit_fee.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimaryStudentIntakeUnitFee" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDangerStudentIntakeUnitFee" name="action[student_intake_unit_fee][]" @if(in_array('student_intake_unit_fee.delete', $user_role)) checked @endif value="delete">
                                                <label for="checkboxDangerStudentIntakeUnitFee" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Payment</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoPayment" name="action[payment][]" @if(in_array('payment.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfoPayment" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccessPayment" name="action[payment][]" @if(in_array('payment.add', $user_role)) checked @endif value="add">
                                                <label for="checkboxSuccessPayment" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimaryPayment" name="action[payment][]" @if(in_array('payment.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimaryPayment" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDangerPayment" name="action[payment][]" @if(in_array('payment.delete', $user_role)) checked @endif value="delete">
                                                <label for="checkboxDangerPayment" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Offer Status</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoOfferStatus" name="action[offer_status][]" @if(in_array('offer_status.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfoOfferStatus" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccessOfferStatus" name="action[offer_status][]" @if(in_array('offer_status.add', $user_role)) checked @endif value="add">
                                                <label for="checkboxSuccessOfferStatus" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimaryOfferStatus" name="action[offer_status][]" @if(in_array('offer_status.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimaryOfferStatus" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDangerOfferStatus" name="action[offer_status][]" @if(in_array('offer_status.delete', $user_role)) checked @endif value="delete">
                                                <label for="checkboxDangerOfferStatus" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Condition</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoCondition" name="action[condition][]" @if(in_array('condition.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfoCondition" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccessCondition" name="action[condition][]" @if(in_array('condition.add', $user_role)) checked @endif value="add">
                                                <label for="checkboxSuccessCondition" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimaryCondition" name="action[condition][]" @if(in_array('condition.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimaryCondition" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDangerCondition" name="action[condition][]" @if(in_array('condition.delete', $user_role)) checked @endif value="delete">
                                                <label for="checkboxDangerCondition" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Credit</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoCredit" name="action[credit][]" @if(in_array('credit.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfoCredit" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccessCredit" name="action[credit][]" @if(in_array('credit.add', $user_role)) checked @endif value="add">
                                                <label for="checkboxSuccessCredit" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimaryCredit" name="action[credit][]" @if(in_array('credit.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimaryCredit" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDangerCredit" name="action[credit][]" @if(in_array('credit.delete', $user_role)) checked @endif value="delete">
                                                <label for="checkboxDangerCredit" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Database Backup</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoDatabaseBackup" name="action[database_backup][]" @if(in_array('database_backup.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfoDatabaseBackup" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccessDatabaseBackup" name="action[database_backup][]" @if(in_array('database_backup.add', $user_role)) checked @endif value="add">
                                                <label for="checkboxSuccessDatabaseBackup" class="check add"></label>
                                            </div>
                                        </td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Email Settings</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoEmail" name="action[email][]" @if(in_array('email.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfoEmail" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccessEmail" name="action[email][]" @if(in_array('email.add', $user_role)) checked @endif value="add">
                                                <label for="checkboxSuccessEmail" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimaryEmail" name="action[email][]" @if(in_array('email.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimaryEmail" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDangerEmail" name="action[email][]" @if(in_array('email.delete', $user_role)) checked @endif value="delete">
                                                <label for="checkboxDangerEmail" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Email Template Settings</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoEmailTemplate" name="action[email_template][]" @if(in_array('email_template.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfoEmailTemplate" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccessEmailTemplate" name="action[email_template][]" @if(in_array('email_template.add', $user_role)) checked @endif value="add">
                                                <label for="checkboxSuccessEmailTemplate" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimaryEmailTemplate" name="action[email_template][]" @if(in_array('email_template.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimaryEmailTemplate" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDangerEmailTemplate" name="action[email_template][]" @if(in_array('email_template.delete', $user_role)) checked @endif value="delete">
                                                <label for="checkboxDangerEmailTemplate" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Email User</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoEmailUser" name="action[email_user][]" @if(in_array('email_user.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfoEmailUser" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccessEmailUser" name="action[email_user][]" @if(in_array('email_user.add', $user_role)) checked @endif value="add">
                                                <label for="checkboxSuccessEmailUser" class="check add"></label>
                                            </div>
                                        </td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Template</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoTemplate" name="action[template][]" @if(in_array('template.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfoTemplate" class="check view"></label>
                                            </div>
                                        </td>
                                        <td></td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimaryTemplate" name="action[template][]" @if(in_array('template.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimaryTemplate" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Student Report</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoStudentReport" name="action[student_report][]" @if(in_array('student_report.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfoStudentReport" class="check view"></label>
                                            </div>
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Analytics Dashboard</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoAnalyticsDashboard" name="action[analytics_dashboard][]" @if(in_array('analytics_dashboard.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfoAnalyticsDashboard" class="check view"></label>
                                            </div>
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Student Risk Report</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoStudentRiskReport" name="action[student_risk_report][]" @if(in_array('student_risk_report.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfoStudentRiskReport" class="check view"></label>
                                            </div>
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Intake Report</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoIntakeReport" name="action[intake_report][]" @if(in_array('intake_report.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfoIntakeReport" class="check view"></label>
                                            </div>
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <!-- <tr>
                                        <td>Agent Report</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoAgentReport" name="action[agent_report][]" @if(in_array('agent_report.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfoAgentReport" class="check view"></label>
                                            </div>
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr> -->
                                    <tr>
                                        <td>Payment Due Report</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoDuePaysReport" name="action[due_pays_report][]" @if(in_array('due_pays_report.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfoDuePaysReport" class="check view"></label>
                                            </div>
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Fee Received Report</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoFeeReceivedReport" name="action[fee_received_report][]" @if(in_array('fee_received_report.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfoFeeReceivedReport" class="check view"></label>
                                            </div>
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <!-- <tr>
                                        <td>Commission Report</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoCommissionReport" name="action[commission_report][]" @if(in_array('commission_report.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfoCommissionReport" class="check view"></label>
                                            </div>
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr> -->
                                    <tr>
                                        <td>Course Completion Report</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoCourseCompletionReport" name="action[course_completion_report][]" @if(in_array('course_completion_report.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfoCourseCompletionReport" class="check view"></label>
                                            </div>
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Report Template</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoReportTemplate" name="action[report_template][]" @if(in_array('report_template.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfoReportTemplate" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccessReportTemplate" name="action[report_template][]" @if(in_array('report_template.add', $user_role)) checked @endif value="add">
                                                <label for="checkboxSuccessReportTemplate" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimaryReportTemplate" name="action[report_template][]" @if(in_array('report_template.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimaryReportTemplate" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Classroom</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoClassroom" name="action[classroom][]" @if(in_array('classroom.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfoClassroom" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccessClassroom" name="action[classroom][]" @if(in_array('classroom.add', $user_role)) checked @endif value="add">
                                                <label for="checkboxSuccessClassroom" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimaryClassroom" name="action[classroom][]" @if(in_array('classroom.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimaryClassroom" class="check add"></label>
                                            </div>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Document Type</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoDocumentType" name="action[document_type][]" @if(in_array('document_type.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfoDocumentType" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccessDocumentType" name="action[document_type][]" @if(in_array('document_type.add', $user_role)) checked @endif value="add">
                                                <label for="checkboxSuccessDocumentType" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimaryDocumentType" name="action[document_type][]" @if(in_array('document_type.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimaryDocumentType" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDangerDocumentType" name="action[document_type][]" @if(in_array('document_type.delete', $user_role)) checked @endif value="delete">
                                                <label for="checkboxDangerDocumentType" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Student Document Type</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoStudentDocumentType" name="action[student_document_type][]" @if(in_array('student_document_type.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfoStudentDocumentType" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccessStudentDocumentType" name="action[student_document_type][]" @if(in_array('student_document_type.add', $user_role)) checked @endif value="add">
                                                <label for="checkboxSuccessStudentDocumentType" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimaryStudentDocumentType" name="action[student_document_type][]" @if(in_array('student_document_type.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimaryStudentDocumentType" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDangerStudentDocumentType" name="action[student_document_type][]" @if(in_array('student_document_type.delete', $user_role)) checked @endif value="delete">
                                                <label for="checkboxDangerStudentDocumentType" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Student Document</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoStudentDocument" name="action[student_document][]" @if(in_array('student_document.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfoStudentDocument" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccessStudentDocument" name="action[student_document][]" @if(in_array('student_document.add', $user_role)) checked @endif value="add">
                                                <label for="checkboxSuccessStudentDocument" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimaryStudentDocument" name="action[student_document][]" @if(in_array('student_document.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimaryStudentDocument" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDangerStudentDocument" name="action[student_document][]" @if(in_array('student_document.delete', $user_role)) checked @endif value="delete">
                                                <label for="checkboxDangerStudentDocument" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>CRM Lead</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoCRMLead" name="action[lead][]" @if(in_array('lead.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfoCRMLead" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccessCRMLead" name="action[lead][]" @if(in_array('lead.add', $user_role)) checked @endif value="add">
                                                <label for="checkboxSuccessCRMLead" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimaryCRMLead" name="action[lead][]" @if(in_array('lead.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimaryCRMLead" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDangerCRMLead" name="action[lead][]" @if(in_array('lead.delete', $user_role)) checked @endif value="delete">
                                                <label for="checkboxDangerCRMLead" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Zoho CRM Lead</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoZohoCRMLead" name="action[zoho_lead][]" @if(in_array('zoho_lead.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfoZohoCRMLead" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccessZohoCRMLead" name="action[zoho_lead][]" @if(in_array('zoho_lead.add', $user_role)) checked @endif value="add">
                                                <label for="checkboxSuccessZohoCRMLead" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimaryZohoCRMLead" name="action[zoho_lead][]" @if(in_array('zoho_lead.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimaryZohoCRMLead" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDangerZohoCRMLead" name="action[zoho_lead][]" @if(in_array('zoho_lead.delete', $user_role)) checked @endif value="delete">
                                                <label for="checkboxDangerZohoCRMLead" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Fee Type</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoFeeType" name="action[fee_type][]" @if(in_array('fee_type.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfoFeeType" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccessFeeType" name="action[fee_type][]" @if(in_array('fee_type.add', $user_role)) checked @endif value="add">
                                                <label for="checkboxSuccessFeeType" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimaryFeeType" name="action[fee_type][]" @if(in_array('fee_type.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimaryFeeType" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDangerFeeType" name="action[fee_type][]" @if(in_array('fee_type.delete', $user_role)) checked @endif value="delete">
                                                <label for="checkboxDangerFeeType" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Marking Type</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoMarkingType" name="action[marking_type][]" @if(in_array('marking_type.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfoMarkingType" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccessMarkingType" name="action[marking_type][]" @if(in_array('marking_type.add', $user_role)) checked @endif value="add">
                                                <label for="checkboxSuccessMarkingType" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimaryMarkingType" name="action[marking_type][]" @if(in_array('marking_type.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimaryMarkingType" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDangerMarkingType" name="action[marking_type][]" @if(in_array('marking_type.delete', $user_role)) checked @endif value="delete">
                                                <label for="checkboxDangerMarkingType" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Scholarship</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoScholarship" name="action[scholarship][]" @if(in_array('scholarship.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfoScholarship" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccessScholarship" name="action[scholarship][]" @if(in_array('scholarship.add', $user_role)) checked @endif value="add">
                                                <label for="checkboxSuccessScholarship" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimaryScholarship" name="action[scholarship][]" @if(in_array('scholarship.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimaryScholarship" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDangerScholarship" name="action[scholarship][]" @if(in_array('scholarship.delete', $user_role)) checked @endif value="delete">
                                                <label for="checkboxDangerScholarship" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Scholarship Application</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoScholarshipApp" name="action[scholarship_application][]" @if(in_array('scholarship_application.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfoScholarshipApp" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccessScholarshipApp" name="action[scholarship_application][]" @if(in_array('scholarship_application.add', $user_role)) checked @endif value="add">
                                                <label for="checkboxSuccessScholarshipApp" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimaryScholarshipApp" name="action[scholarship_application][]" @if(in_array('scholarship_application.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimaryScholarshipApp" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDangerScholarshipApp" name="action[scholarship_application][]" @if(in_array('scholarship_application.delete', $user_role)) checked @endif value="delete">
                                                <label for="checkboxDangerScholarshipApp" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Ticket Support</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoTicketSupport" name="action[ticket][]" @if(in_array('ticket.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfoTicketSupport" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccessTicketSupport" name="action[ticket][]" @if(in_array('ticket.add', $user_role)) checked @endif value="add">
                                                <label for="checkboxSuccessTicketSupport" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimaryTicketSupport" name="action[ticket][]" @if(in_array('ticket.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimaryTicketSupport" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDangerTicketSupport" name="action[ticket][]" @if(in_array('ticket.delete', $user_role)) checked @endif value="delete">
                                                <label for="checkboxDangerTicketSupport" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Chat</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoChat" name="action[chat][]" @if(in_array('chat.view', $user_role)) checked @endif value="view">
                                                <label for="checkboxInfoChat" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccessChat" name="action[chat][]" @if(in_array('chat.add', $user_role)) checked @endif value="add">
                                                <label for="checkboxSuccessChat" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimaryChat" name="action[chat][]" @if(in_array('chat.edit', $user_role)) checked @endif value="edit">
                                                <label for="checkboxPrimaryChat" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDangerChat" name="action[chat][]" @if(in_array('chat.delete', $user_role)) checked @endif value="delete">
                                                <label for="checkboxDangerChat" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Menu</th>
                                        <th>View</th>
                                        <th>Add</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                    </tr>
                                </tfoot>
                            </table>
                            <div class="form-group">
                                <label for="status">Status</label> <span class="required">*</span>
                                <select name="status" class="form-control" id="status">
                                    <option value="" selected disabled>-- Select Status --</option>
                                    <option @if($role->status == '1')selected @endif value="1">Active</option>
                                    <option @if($role->status == '0')selected @endif value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
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
        $('#addrole').validate({
            rules: {
                name: {
                    required: true,
                },
                status: {
                    required: true
                },
            },
            messages: {
                name: "Please enter name",
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
</script>
@endsection
