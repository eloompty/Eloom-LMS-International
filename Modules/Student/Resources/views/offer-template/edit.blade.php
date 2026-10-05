@extends('user::layouts.master')
@section('title', 'Admin | Edit Offer Letter Template')

@include('student::offer-template._form', [
    'pageTitle'        => 'Edit Offer Letter Template',
    'crumb'            => 'Edit',
    'formAction'       => route('admin.offer.template.update', $template->id),
    'submitLabel'      => 'Update Template',
    'templateName'     => old('name', $template->name),
    'templateStatus'   => (string) old('status', $template->status),
    'initialSections'  => json_decode($template->layout, true) ?: [],
])
