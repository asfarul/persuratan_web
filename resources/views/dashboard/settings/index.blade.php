@extends('dashboard.layouts.app')
@section('title', 'Pengaturan Smart TV')
@section('page_title', 'Pengaturan Smart TV')
@push('breadcrumbs')
    <!--begin::Item-->
    <li class="breadcrumb-item">
        <span class="bullet bg-gray-400 w-5px h-2px"></span>
    </li>
    <!--end::Item-->
    <!--begin::Item-->
    <li class="breadcrumb-item text-muted">Smart TV</li>
    <!--end::Item-->
    <!--begin::Item-->
    <li class="breadcrumb-item">
        <span class="bullet bg-gray-400 w-5px h-2px"></span>
    </li>
    <!--end::Item-->
    <!--begin::Item-->
    <li class="breadcrumb-item text-muted"><a class="text-muted text-hover-primary"
            href="{{ route('dashboard.settings.index') }}">Pengaturan</a></li>
    <!--end::Item-->
@endpush
@section('content')
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-xxl">
            <!--begin::Card-->
            <div class="card">
                <!--begin::Card header-->
                <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
                    data-bs-target="#kt_roles" aria-expanded="true" aria-controls="kt_roles">
                    <!--begin::Card title-->
                    <div class="card-title m-0">
                        <h3 class="fw-bold m-0">Smart TV</h3>
                    </div>
                    <!--end::Card title-->
                </div>
                <!--begin::Card header-->
                <!--begin::Content-->
                <div id="kt_account_settings_profile_details" class="collapse show">
                    @if (Session::has('errors'))
                        <div
                            class="notice d-flex bg-light-danger rounded border-danger border border-dashed rounded-3 p-6 mx-10">
                            <!--begin::Wrapper-->
                            <div class="d-flex flex-stack flex-grow-1">
                                <!--begin::Content-->
                                <div class="fw-semibold">
                                    <h4 class="text-danger fw-bold">Terjadi kesalahan!</h4>
                                    <div class="fs-6 text-gray-700">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                {{-- @foreach ($errors as $error) --}}
                                                <li>{{ $error }}</li>
                                                {{-- @endforeach --}}
                                            @endforeach
                                        </ul>
                                        {{-- <a href="#" class="fw-bold">Learn more</a>. --}}
                                    </div>
                                </div>
                                <!--end::Content-->
                            </div>
                            <!--end::Wrapper-->
                        </div>
                    @endif
                    <!--begin::Form-->
                    <form id="kt_form" class="form" action="{{ route('dashboard.settings.store') }}" method="POST"
                        data-kt-redirect-url="{{ route('dashboard.settings.index') }}" enctype="multipart/form-data">
                        @csrf
                        <!--begin::Card body-->
                        <div class="card-body border-top p-9">
                            @foreach ($settings as $setting)
                                <!--begin::Input group-->
                                <div class="row mb-6">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label fw-semibold fs-6">{{ $setting->title }}</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8 fv-row">
                                        <input type="text" name="{{ $setting->key }}"
                                            value="{{ old($setting->key, $setting->value) }}"
                                            class="form-control form-control-lg form-control-solid {{ $errors->has($setting->key) ? 'is-invalid' : '' }}" />
                                            <span class="form-text text-muted">{{ $setting->deskripsi }}</span>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Input group-->
                            @endforeach

                        </div>
                        <!--end::Card body-->
                        <!--begin::Actions-->
                        <div class="card-footer d-flex justify-content-end py-6 px-9">
                           
                            <button type="submit" class="btn btn-primary" id="kt_submit">
                                <!--begin::Indicator label-->
                                <span class="indicator-label">Simpan Pengaturan</span>
                                <!--end::Indicator label-->
                                <!--begin::Indicator progress-->
                                <span class="indicator-progress">Please wait...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                <!--end::Indicator progress-->

                            </button>
                        </div>
                        <!--end::Actions-->
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Content-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Content container-->
    </div>
@endsection
@push('scripts')
    {{-- <script src="{{ asset('assets/dashboard/') }}/js/sliders/list.js"></script> --}}
    {{-- <script src="{{ asset('assets/metro1/') }}/js/roles/form.js"></script> --}}
    <script>

        // Define form element
        const form = document.getElementById('kt_form');

        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        var validator = FormValidation.formValidation(
            form, {
                fields: {
                    'nama': {
                        validators: {
                            notEmpty: {
                                message: 'Nama tidak boleh kosong'
                            }
                        }
                    },
                    'logo': {
                        validators: {
                            notEmpty: {
                                message: 'Logo tidak boleh kosong'
                            }
                        }
                    },
                },

                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row',
                        eleInvalidClass: '',
                        eleValidClass: ''
                    })
                }
            }
        );


        // Submit button handler
        const submitButton = document.getElementById('kt_submit');
        submitButton.addEventListener('click', function(e) {
            // Prevent default button action
            e.preventDefault();

            // Validate form before submit
            if (validator) {
                validator.validate().then(function(status) {

                    if (status == 'Valid') {
                        // Show loading indication
                        submitButton.setAttribute('data-kt-indicator', 'on');

                        // Disable button to avoid multiple click
                        submitButton.disabled = true;

                        // Simulate form submission. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                        setTimeout(function() {
                            // Remove loading indication
                            submitButton.removeAttribute('data-kt-indicator');

                            // Enable button
                            submitButton.disabled = false;

                            form.submit(); // Submit form
                        }, 1000);
                    }
                });
            }
        });
    </script>
@endpush
