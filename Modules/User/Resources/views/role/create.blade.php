@extends('user::layouts.master')
@section('title', 'Admin | Add Role')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Add Role</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    @if (Auth::user('admin')->theme == 'theme2' || Auth::user('admin')->theme == 'theme3')
                    <li class="breadcrumb-item"><a href="{{ route('admin.setting.menu') }}">Settings Menu</a></li>
                    @endif
                    <li class="breadcrumb-item"><a href="{{ route('admin.role.index') }}">Roles</a></li>
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
        <form id="addrole" action="{{ route('admin.role.store') }}" method="POST">
            @csrf
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> Add Role</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name">Name</label> <span class="required">*</span>
                                <input type="text" name="name" class="form-control" id="name" placeholder="Enter Name">
                            </div>
                            <div class="form-group">
                                <div class="icheck-info d-inline">
                                    <input type="checkbox" class="form-controll select_all" id="checkboxInfo0">
                                    <label for="checkboxInfo0" class="check view">Select All</label>
                                </div>
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
                                                <input type="checkbox" class="checkbox" id="checkboxInfo1" name="action[company][]" value="view">
                                                <label for="checkboxInfo1" class="check view"></label>
                                            </div>
                                        </td>
                                        <td></td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimary1" name="action[company][]" value="edit">
                                                <label for="checkboxPrimary1" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Company Delivery Site</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfo2" name="action[company_delivery_site][]" value="view">
                                                <label for="checkboxInfo2" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccess2" name="action[company_delivery_site][]" value="add">
                                                <label for="checkboxSuccess2" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimary2" name="action[company_delivery_site][]" value="edit">
                                                <label for="checkboxPrimary2" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDanger2" name="action[company_delivery_site][]" value="delete">
                                                <label for="checkboxDanger2" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Course</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfo3" name="action[course][]" value="view">
                                                <label for="checkboxInfo3" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccess3" name="action[course][]" value="add">
                                                <label for="checkboxSuccess3" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimary3" name="action[course][]" value="edit">
                                                <label for="checkboxPrimary3" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDanger3" name="action[course][]" value="delete">
                                                <label for="checkboxDanger3" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Semester</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoSemester" name="action[semester][]" value="view">
                                                <label for="checkboxInfoSemester" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccessSemester" name="action[semester][]" value="add">
                                                <label for="checkboxSuccessSemester" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimarySemester" name="action[semester][]" value="edit">
                                                <label for="checkboxPrimarySemester" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDangerSemester" name="action[semester][]" value="delete">
                                                <label for="checkboxDangerSemester" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Subject</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoSubject" name="action[subject][]" value="view">
                                                <label for="checkboxInfoSubject" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccessSubject" name="action[subject][]" value="add">
                                                <label for="checkboxSuccessSubject" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimarySubject" name="action[subject][]" value="edit">
                                                <label for="checkboxPrimarySubject" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDangerSubject" name="action[subject][]" value="delete">
                                                <label for="checkboxDangerSubject" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Unit</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfo4" name="action[unit][]" value="view">
                                                <label for="checkboxInfo4" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccess4" name="action[unit][]" value="add">
                                                <label for="checkboxSuccess4" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimary4" name="action[unit][]" value="edit">
                                                <label for="checkboxPrimary4" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDanger4" name="action[unit][]" value="delete">
                                                <label for="checkboxDanger4" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Unit Resource</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfo5" name="action[unit_resource][]" value="view">
                                                <label for="checkboxInfo5" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccess5" name="action[unit_resource][]" value="add">
                                                <label for="checkboxSuccess5" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimary5" name="action[unit_resource][]" value="edit">
                                                <label for="checkboxPrimary5" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDanger5" name="action[unit_resource][]" value="delete">
                                                <label for="checkboxDanger5" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Unit Assignment</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfo28" name="action[unit_assignment][]" value="view">
                                                <label for="checkboxInfo28" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccess28" name="action[unit_assignment][]" value="add">
                                                <label for="checkboxSuccess28" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimary28" name="action[unit_assignment][]" value="edit">
                                                <label for="checkboxPrimary28" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDanger28" name="action[unit_assignment][]" value="delete">
                                                <label for="checkboxDanger28" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Unit Fee</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoUnitFee" name="action[unit_fee][]" value="view">
                                                <label for="checkboxInfoUnitFee" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccessUnitFee" name="action[unit_fee][]" value="add">
                                                <label for="checkboxSuccessUnitFee" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimaryUnitFee" name="action[unit_fee][]" value="edit">
                                                <label for="checkboxPrimaryUnitFee" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDangerUnitFee" name="action[unit_fee][]" value="delete">
                                                <label for="checkboxDangerUnitFee" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Intake</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfo6" name="action[intake][]" value="view">
                                                <label for="checkboxInfo6" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccess6" name="action[intake][]" value="add">
                                                <label for="checkboxSuccess6" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimary6" name="action[intake][]" value="edit">
                                                <label for="checkboxPrimary6" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDanger6" name="action[intake][]" value="delete">
                                                <label for="checkboxDanger6" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Intake Course</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfo7" name="action[intake_course][]" value="view">
                                                <label for="checkboxInfo7" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccess7" name="action[intake_course][]" value="add">
                                                <label for="checkboxSuccess7" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimary7" name="action[intake_course][]" value="edit">
                                                <label for="checkboxPrimary7" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDanger7" name="action[intake_course][]" value="delete">
                                                <label for="checkboxDanger7" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Intake Course Time Table</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfo8" name="action[intake_course_time_table][]" value="view">
                                                <label for="checkboxInfo8" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccess8" name="action[intake_course_time_table][]" value="add">
                                                <label for="checkboxSuccess8" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimary8" name="action[intake_course_time_table][]" value="edit">
                                                <label for="checkboxPrimary8" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDanger8" name="action[intake_course_time_table][]" value="delete">
                                                <label for="checkboxDanger8" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Social Category</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfo9" name="action[social_category][]" value="view">
                                                <label for="checkboxInfo9" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccess9" name="action[social_category][]" value="add">
                                                <label for="checkboxSuccess9" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimary9" name="action[social_category][]" value="edit">
                                                <label for="checkboxPrimary9" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDanger9" name="action[social_category][]" value="delete">
                                                <label for="checkboxDanger9" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Student</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfo10" name="action[student][]" value="view">
                                                <label for="checkboxInfo10" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccess10" name="action[student][]" value="add">
                                                <label for="checkboxSuccess10" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimary10" name="action[student][]" value="edit">
                                                <label for="checkboxPrimary10" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDanger10" name="action[student][]" value="delete">
                                                <label for="checkboxDanger10" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Student Intake</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfo11" name="action[student_intake_course][]" value="view">
                                                <label for="checkboxInfo11" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccess11" name="action[student_intake_course][]" value="add">
                                                <label for="checkboxSuccess11" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimary11" name="action[student_intake_course][]" value="edit">
                                                <label for="checkboxPrimary11" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDanger11" name="action[student_intake_course][]" value="delete">
                                                <label for="checkboxDanger11" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Student Intake Unit</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfo27" name="action[student_intake_unit][]" value="view">
                                                <label for="checkboxInfo27" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimary27" name="action[student_intake_unit][]" value="edit">
                                                <label for="checkboxPrimary27" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDanger27" name="action[student_intake_unit][]" value="delete">
                                                <label for="checkboxDanger27" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Student Social</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfo12" name="action[student_social][]" value="view">
                                                <label for="checkboxInfo12" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccess12" name="action[student_social][]" value="add">
                                                <label for="checkboxSuccess12" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimary12" name="action[student_social][]" value="edit">
                                                <label for="checkboxPrimary12" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDanger12" name="action[student_social][]" value="delete">
                                                <label for="checkboxDanger12" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Student Dashboard</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoDashboard" name="action[student_dashboard][]" value="view">
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
                                                <input type="checkbox" class="checkbox" id="checkboxInfo13" name="action[trainer][]" value="view">
                                                <label for="checkboxInfo13" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccess13" name="action[trainer][]" value="add">
                                                <label for="checkboxSuccess13" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimary13" name="action[trainer][]" value="edit">
                                                <label for="checkboxPrimary13" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDanger13" name="action[trainer][]" value="delete">
                                                <label for="checkboxDanger13" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Trainer Intake</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfo14" name="action[trainer_intake][]" value="view">
                                                <label for="checkboxInfo14" class="check view"></label>
                                            </div>
                                        </td>
                                        <td></td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimary14" name="action[trainer_intake][]" value="edit">
                                                <label for="checkboxPrimary14" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDanger14" name="action[trainer_intake][]" value="delete">
                                                <label for="checkboxDanger14" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Trainer Qualification</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfo15" name="action[trainer_qualification][]" value="view">
                                                <label for="checkboxInfo15" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccess15" name="action[trainer_qualification][]" value="add">
                                                <label for="checkboxSuccess15" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimary15" name="action[trainer_qualification][]" value="edit">
                                                <label for="checkboxPrimary15" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDanger15" name="action[trainer_qualification][]" value="delete">
                                                <label for="checkboxDanger15" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Trainer Professional Development</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfo16" name="action[trainer_professional_development][]" value="view">
                                                <label for="checkboxInfo16" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccess16" name="action[trainer_professional_development][]" value="add">
                                                <label for="checkboxSuccess16" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimary16" name="action[trainer_professional_development][]" value="edit">
                                                <label for="checkboxPrimary16" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDanger16" name="action[trainer_professional_development][]" value="delete">
                                                <label for="checkboxDanger16" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Trainer Work Placement</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfo17" name="action[trainer_placement][]" value="view">
                                                <label for="checkboxInfo17" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccess17" name="action[trainer_placement][]" value="add">
                                                <label for="checkboxSuccess17" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimary17" name="action[trainer_placement][]" value="edit">
                                                <label for="checkboxPrimary17" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDanger17" name="action[trainer_placement][]" value="delete">
                                                <label for="checkboxDanger17" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Trainer Dashboard</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoTrainerDashboard" name="action[trainer_dashboard][]" value="view">
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
                                                <input type="checkbox" class="checkbox" id="checkboxInfo18" name="action[university][]" value="view">
                                                <label for="checkboxInfo18" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccess18" name="action[university][]" value="add">
                                                <label for="checkboxSuccess18" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimary18" name="action[university][]" value="edit">
                                                <label for="checkboxPrimary18" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDanger18" name="action[university][]" value="delete">
                                                <label for="checkboxDanger18" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>University Qualification</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfo19" name="action[university_qualification][]" value="view">
                                                <label for="checkboxInfo19" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccess19" name="action[university_qualification][]" value="add">
                                                <label for="checkboxSuccess19" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimary19" name="action[university_qualification][]" value="edit">
                                                <label for="checkboxPrimary19" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDanger19" name="action[university_qualification][]" value="delete">
                                                <label for="checkboxDanger19" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Resource</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfo20" name="action[resource][]" value="view">
                                                <label for="checkboxInfo20" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccess20" name="action[resource][]" value="add">
                                                <label for="checkboxSuccess20" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimary20" name="action[resource][]" value="edit">
                                                <label for="checkboxPrimary20" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDanger20" name="action[resource][]" value="delete">
                                                <label for="checkboxDanger20" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Resource Category</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfo21" name="action[resource_category][]" value="view">
                                                <label for="checkboxInfo21" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccess21" name="action[resource_category][]" value="add">
                                                <label for="checkboxSuccess21" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimary21" name="action[resource_category][]" value="edit">
                                                <label for="checkboxPrimary21" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDanger21" name="action[resource_category][]" value="delete">
                                                <label for="checkboxDanger21" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Role</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfo22" name="action[role][]" value="view">
                                                <label for="checkboxInfo22" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccess22" name="action[role][]" value="add">
                                                <label for="checkboxSuccess22" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimary22" name="action[role][]" value="edit">
                                                <label for="checkboxPrimary22" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDanger22" name="action[role][]" value="delete">
                                                <label for="checkboxDanger22" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>User</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfo23" name="action[user][]" value="view">
                                                <label for="checkboxInfo23" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccess23" name="action[user][]" value="add">
                                                <label for="checkboxSuccess23" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimary23" name="action[user][]" value="edit">
                                                <label for="checkboxPrimary23" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Assignment</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfo24" name="action[assignment][]" value="view">
                                                <label for="checkboxInfo24" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccess24" name="action[assignment][]" value="add">
                                                <label for="checkboxSuccess24" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimary24" name="action[assignment][]" value="edit">
                                                <label for="checkboxPrimary24" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDanger24" name="action[assignment][]" value="delete">
                                                <label for="checkboxDanger24" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Assignment Grade</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfo25" name="action[assignment_grade][]" value="view">
                                                <label for="checkboxInfo25" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccess25" name="action[assignment_grade][]" value="add">
                                                <label for="checkboxSuccess25" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimary25" name="action[assignment_grade][]" value="edit">
                                                <label for="checkboxPrimary25" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDanger25" name="action[assignment_grade][]" value="delete">
                                                <label for="checkboxDanger25" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Assignment Submission</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfo26" name="action[assignment_submission][]" value="view">
                                                <label for="checkboxInfo26" class="check view"></label>
                                            </div>
                                        </td>
                                        <td></td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimary26" name="action[assignment_submission][]" value="edit">
                                                <label for="checkboxPrimary26" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Country</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfo29" name="action[country][]" value="view">
                                                <label for="checkboxInfo29" class="check view"></label>
                                            </div>
                                        </td>
                                        <td></td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimary29" name="action[country][]" value="edit">
                                                <label for="checkboxPrimary29" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Settings</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfo30" name="action[setting][]" value="view">
                                                <label for="checkboxInfo30" class="check view"></label>
                                            </div>
                                        </td>
                                        <td></td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimary30" name="action[setting][]" value="edit">
                                                <label for="checkboxPrimary30" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Group Online Class</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfo31" name="action[online_class_group][]" value="view">
                                                <label for="checkboxInfo31" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccess31" name="action[online_class_group][]" value="add">
                                                <label for="checkboxSuccess31" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimary31" name="action[online_class_group][]" value="edit">
                                                <label for="checkboxPrimary31" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td></td>
                                    </tr>
                                   <!--  <tr>
                                        <td>Agent</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfo32" name="action[agent][]" value="view">
                                                <label for="checkboxInfo32" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccess32" name="action[agent][]" value="add">
                                                <label for="checkboxSuccess32" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimary32" name="action[agent][]" value="edit">
                                                <label for="checkboxPrimary32" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDanger32" name="action[agent][]" value="delete">
                                                <label for="checkboxDanger32" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Agent Branch</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfo33" name="action[agent_branch][]" value="view">
                                                <label for="checkboxInfo33" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccess33" name="action[agent_branch][]" value="add">
                                                <label for="checkboxSuccess33" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimary33" name="action[agent_branch][]" value="edit">
                                                <label for="checkboxPrimary33" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDanger33" name="action[agent_branch][]" value="delete">
                                                <label for="checkboxDanger33" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Agent Branch User</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfo34" name="action[agent_branch_user][]" value="view">
                                                <label for="checkboxInfo34" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccess34" name="action[agent_branch_user][]" value="add">
                                                <label for="checkboxSuccess34" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimary34" name="action[agent_branch_user][]" value="edit">
                                                <label for="checkboxPrimary34" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDanger34" name="action[agent_branch_user][]" value="delete">
                                                <label for="checkboxDanger34" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr> -->
                                    <tr>
                                        <td>Course Fee</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfo35" name="action[course_fee][]" value="view">
                                                <label for="checkboxInfo35" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccess35" name="action[course_fee][]" value="add">
                                                <label for="checkboxSuccess35" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimary35" name="action[course_fee][]" value="edit">
                                                <label for="checkboxPrimary35" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDanger35" name="action[course_fee][]" value="delete">
                                                <label for="checkboxDanger35" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Intake Course Fee</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfo36" name="action[intake_course_fee][]" value="view">
                                                <label for="checkboxInfo36" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccess36" name="action[intake_course_fee][]" value="add">
                                                <label for="checkboxSuccess36" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimary36" name="action[intake_course_fee][]" value="edit">
                                                <label for="checkboxPrimary36" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDanger36" name="action[intake_course_fee][]" value="delete">
                                                <label for="checkboxDanger36" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Student Intake Course Fee</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfo37" name="action[student_intake_course_fee][]" value="view">
                                                <label for="checkboxInfo37" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccess37" name="action[student_intake_course_fee][]" value="add">
                                                <label for="checkboxSuccess37" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimary37" name="action[student_intake_course_fee][]" value="edit">
                                                <label for="checkboxPrimary37" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDanger37" name="action[student_intake_course_fee][]" value="delete">
                                                <label for="checkboxDanger37" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Student Intake Unit Fee</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoStudentIntakeUnitFee" name="action[student_intake_unit_fee][]" value="view">
                                                <label for="checkboxInfoStudentIntakeUnitFee" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccessStudentIntakeUnitFee" name="action[student_intake_unit_fee][]" value="add">
                                                <label for="checkboxSuccessStudentIntakeUnitFee" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimaryStudentIntakeUnitFee" name="action[student_intake_unit_fee][]" value="edit">
                                                <label for="checkboxPrimaryStudentIntakeUnitFee" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDangerStudentIntakeUnitFee" name="action[student_intake_unit_fee][]" value="delete">
                                                <label for="checkboxDangerStudentIntakeUnitFee" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Payment</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoPayment" name="action[payment][]" value="view">
                                                <label for="checkboxInfoPayment" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccessPayment" name="action[payment][]" value="add">
                                                <label for="checkboxSuccessPayment" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimaryPayment" name="action[payment][]" value="edit">
                                                <label for="checkboxPrimaryPayment" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDangerPayment" name="action[payment][]" value="delete">
                                                <label for="checkboxDangerPayment" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Offer Status</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoOfferStatus" name="action[offer_status][]" value="view">
                                                <label for="checkboxInfoOfferStatus" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccessOfferStatus" name="action[offer_status][]" value="add">
                                                <label for="checkboxSuccessOfferStatus" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimaryOfferStatus" name="action[offer_status][]" value="edit">
                                                <label for="checkboxPrimaryOfferStatus" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDangerOfferStatus" name="action[offer_status][]" value="delete">
                                                <label for="checkboxDangerOfferStatus" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Condition</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoCondition" name="action[condition][]" value="view">
                                                <label for="checkboxInfoCondition" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccessCondition" name="action[condition][]" value="add">
                                                <label for="checkboxSuccessCondition" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimaryCondition" name="action[condition][]" value="edit">
                                                <label for="checkboxPrimaryCondition" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDangerCondition" name="action[condition][]" value="delete">
                                                <label for="checkboxDangerCondition" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Credit</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoCredit" name="action[credit][]" value="view">
                                                <label for="checkboxInfoCredit" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccessCredit" name="action[credit][]" value="add">
                                                <label for="checkboxSuccessCredit" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimaryCredit" name="action[credit][]" value="edit">
                                                <label for="checkboxPrimaryCredit" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDangerCredit" name="action[credit][]" value="delete">
                                                <label for="checkboxDangerCredit" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Database Backup</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoDatabaseBackup" name="action[database_backup][]" value="view">
                                                <label for="checkboxInfoDatabaseBackup" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccessDatabaseBackup" name="action[database_backup][]" value="add">
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
                                                <input type="checkbox" class="checkbox" id="checkboxInfoEmail" name="action[email][]" value="view">
                                                <label for="checkboxInfoEmail" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccessEmail" name="action[email][]" value="add">
                                                <label for="checkboxSuccessEmail" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimaryEmail" name="action[email][]" value="edit">
                                                <label for="checkboxPrimaryEmail" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDangerEmail" name="action[email][]" value="delete">
                                                <label for="checkboxDangerEmail" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Email Template Settings</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoEmailTemplate" name="action[email_template][]" value="view">
                                                <label for="checkboxInfoEmailTemplate" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccessEmailTemplate" name="action[email_template][]" value="add">
                                                <label for="checkboxSuccessEmailTemplate" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimaryEmailTemplate" name="action[email_template][]" value="edit">
                                                <label for="checkboxPrimaryEmailTemplate" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDangerEmailTemplate" name="action[email_template][]" value="delete">
                                                <label for="checkboxDangerEmailTemplate" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Email User</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoEmailUser" name="action[email_user][]" value="view">
                                                <label for="checkboxInfoEmailUser" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccessEmailUser" name="action[email_user][]" value="add">
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
                                                <input type="checkbox" class="checkbox" id="checkboxInfoTemplate" name="action[template][]" value="view">
                                                <label for="checkboxInfoTemplate" class="check view"></label>
                                            </div>
                                        </td>
                                        <td></td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimaryTemplate" name="action[template][]" value="edit">
                                                <label for="checkboxPrimaryTemplate" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Student Report</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoStudentReport" name="action[student_report][]" value="view">
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
                                                <input type="checkbox" class="checkbox" id="checkboxInfoAnalyticsDashboard" name="action[analytics_dashboard][]" value="view">
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
                                                <input type="checkbox" class="checkbox" id="checkboxInfoStudentRiskReport" name="action[student_risk_report][]" value="view">
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
                                                <input type="checkbox" class="checkbox" id="checkboxInfoIntakeReport" name="action[intake_report][]" value="view">
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
                                                <input type="checkbox" class="checkbox" id="checkboxInfoAgentReport" name="action[agent_report][]" value="view">
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
                                                <input type="checkbox" class="checkbox" id="checkboxInfoDuePaysReport" name="action[due_pays_report][]" value="view">
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
                                                <input type="checkbox" class="checkbox" id="checkboxInfoFeeReceivedReport" name="action[fee_received_report][]" value="view">
                                                <label for="checkboxInfoFeeReceivedReport" class="check view"></label>
                                            </div>
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <!-- <tr>
                                        <td>Fee Commission Report</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoFeeCommissionReport" name="action[commission_report][]" value="view">
                                                <label for="checkboxInfoFeeCommissionReport" class="check view"></label>
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
                                                <input type="checkbox" class="checkbox" id="checkboxInfoCourseCompletionReport" name="action[course_completion_report][]" value="view">
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
                                                <input type="checkbox" class="checkbox" id="checkboxInfoReportTemplate" name="action[report_template][]" value="view">
                                                <label for="checkboxInfoReportTemplate" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccessReportTemplate" name="action[report_template][]" value="add">
                                                <label for="checkboxSuccessReportTemplate" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimaryReportTemplate" name="action[report_template][]" value="edit">
                                                <label for="checkboxPrimaryReportTemplate" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Classroom</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoClassroom" name="action[classroom][]" value="view">
                                                <label for="checkboxInfoClassroom" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccessClassroom" name="action[classroom][]" value="add">
                                                <label for="checkboxSuccessClassroom" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimaryClassroom" name="action[classroom][]" value="edit">
                                                <label for="checkboxPrimaryClassroom" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Document Type</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoDocumentType" name="action[document_type][]" value="view">
                                                <label for="checkboxInfoDocumentType" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccessDocumentType" name="action[document_type][]" value="add">
                                                <label for="checkboxSuccessDocumentType" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimaryDocumentType" name="action[document_type][]" value="edit">
                                                <label for="checkboxPrimaryDocumentType" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDangerDocumentType" name="action[document_type][]" value="delete">
                                                <label for="checkboxDangerDocumentType" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Student Document Type</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoStudentDocumentType" name="action[student_document_type][]" value="view">
                                                <label for="checkboxInfoStudentDocumentType" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccessStudentDocumentType" name="action[student_document_type][]" value="add">
                                                <label for="checkboxSuccessStudentDocumentType" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimaryStudentDocumentType" name="action[student_document_type][]" value="edit">
                                                <label for="checkboxPrimaryStudentDocumentType" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDangerStudentDocumentType" name="action[student_document_type][]" value="delete">
                                                <label for="checkboxDangerStudentDocumentType" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Student Document</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoStudentDocument" name="action[student_document][]" value="view">
                                                <label for="checkboxInfoStudentDocument" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccessStudentDocument" name="action[student_document][]" value="add">
                                                <label for="checkboxSuccessStudentDocument" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimaryStudentDocument" name="action[student_document][]" value="edit">
                                                <label for="checkboxPrimaryStudentDocument" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDangerStudentDocument" name="action[student_document][]" value="delete">
                                                <label for="checkboxDangerStudentDocument" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>CRM Lead</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoCRMLead" name="action[lead][]" value="view">
                                                <label for="checkboxInfoCRMLead" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccessCRMLead" name="action[lead][]" value="add">
                                                <label for="checkboxSuccessCRMLead" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimaryCRMLead" name="action[lead][]" value="edit">
                                                <label for="checkboxPrimaryCRMLead" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDangerCRMLead" name="action[lead][]" value="delete">
                                                <label for="checkboxDangerCRMLead" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Zoho CRM Lead</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoZohoCRMLead" name="action[zoho_lead][]" value="view">
                                                <label for="checkboxInfoZohoCRMLead" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccessZohoCRMLead" name="action[zoho_lead][]" value="add">
                                                <label for="checkboxSuccessZohoCRMLead" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimaryZohoCRMLead" name="action[zoho_lead][]" value="edit">
                                                <label for="checkboxPrimaryZohoCRMLead" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDangerZohoCRMLead" name="action[zoho_lead][]" value="delete">
                                                <label for="checkboxDangerZohoCRMLead" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Fee Type</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoFeeType" name="action[fee_type][]" value="view">
                                                <label for="checkboxInfoFeeType" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccessFeeType" name="action[fee_type][]" value="add">
                                                <label for="checkboxSuccessFeeType" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimaryFeeType" name="action[fee_type][]" value="edit">
                                                <label for="checkboxPrimaryFeeType" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDangerFeeType" name="action[fee_type][]" value="delete">
                                                <label for="checkboxDangerFeeType" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Marking Type</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoMarkingType" name="action[marking_type][]" value="view">
                                                <label for="checkboxInfoMarkingType" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccessMarkingType" name="action[marking_type][]" value="add">
                                                <label for="checkboxSuccessMarkingType" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimaryMarkingType" name="action[marking_type][]" value="edit">
                                                <label for="checkboxPrimaryMarkingType" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDangerMarkingType" name="action[marking_type][]" value="delete">
                                                <label for="checkboxDangerMarkingType" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Scholarship</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoScholarship" name="action[scholarship][]" value="view">
                                                <label for="checkboxInfoScholarship" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccessScholarship" name="action[scholarship][]" value="add">
                                                <label for="checkboxSuccessScholarship" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimaryScholarship" name="action[scholarship][]" value="edit">
                                                <label for="checkboxPrimaryScholarship" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDangerScholarship" name="action[scholarship][]" value="delete">
                                                <label for="checkboxDangerScholarship" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Scholarship Application</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoScholarshipApp" name="action[scholarship_application][]" value="view">
                                                <label for="checkboxInfoScholarshipApp" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccessScholarshipApp" name="action[scholarship_application][]" value="add">
                                                <label for="checkboxSuccessScholarshipApp" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimaryScholarshipApp" name="action[scholarship_application][]" value="edit">
                                                <label for="checkboxPrimaryScholarshipApp" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDangerScholarshipApp" name="action[scholarship_application][]" value="delete">
                                                <label for="checkboxDangerScholarshipApp" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Ticket Support</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoTicketSupport" name="action[ticket][]" value="view">
                                                <label for="checkboxInfoTicketSupport" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccessTicketSupport" name="action[ticket][]" value="add">
                                                <label for="checkboxSuccessTicketSupport" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimaryTicketSupport" name="action[ticket][]" value="edit">
                                                <label for="checkboxPrimaryTicketSupport" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDangerTicketSupport" name="action[ticket][]" value="delete">
                                                <label for="checkboxDangerTicketSupport" class="check delete"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Chat</td>
                                        <td>
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxInfoChat" name="action[chat][]" value="view">
                                                <label for="checkboxInfoChat" class="check view"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-success d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxSuccessChat" name="action[chat][]" value="add">
                                                <label for="checkboxSuccessChat" class="check add"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxPrimaryChat" name="action[chat][]" value="edit">
                                                <label for="checkboxPrimaryChat" class="check edit"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="icheck-danger d-inline">
                                                <input type="checkbox" class="checkbox" id="checkboxDangerChat" name="action[chat][]" value="delete">
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

    $('.select_all').on('change', function() {
        $('.checkbox').prop('checked', $(this).prop("checked"));
    });
    //deselect "checked all", if one of the listed checkbox category is unchecked amd select "checked all" if all of the listed checkbox category is checked
    $('.checkbox').change(function() { //".checkbox" change
        if ($('.checkbox:checked').length == $('.checkbox').length) {
            $('.select_all').prop('checked', true);
        } else {
            $('.select_all').prop('checked', false);
        }
    });
</script>
@endsection
