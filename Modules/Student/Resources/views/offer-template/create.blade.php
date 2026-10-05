@extends('user::layouts.master')
@section('title', 'Admin | Create Offer Letter Template')

@include('student::offer-template._form', [
    'pageTitle'        => 'Create Offer Letter Template',
    'crumb'            => 'Create',
    'formAction'       => route('admin.offer.template.store'),
    'submitLabel'      => 'Save Template',
    'templateName'     => old('name', ''),
    'templateStatus'   => old('status', ''),
    'initialSections'  => [],
])
