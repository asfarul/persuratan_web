@extends('dashboard.layouts.app')
@section('title', 'Cabang')
@section('page_title', 'Tambah Cabang')
@push('breadcrumbs')
    <!--begin::Item-->
    <li class="breadcrumb-item">
        <span class="bullet bg-gray-400 w-5px h-2px"></span>
    </li>
    <!--end::Item-->
    <!--begin::Item-->
    <li class="breadcrumb-item text-muted">Master Data</li>
    <!--end::Item-->
    <!--begin::Item-->
    <li class="breadcrumb-item">
        <span class="bullet bg-gray-400 w-5px h-2px"></span>
    </li>
    <!--end::Item-->
    <!--begin::Item-->
    <li class="breadcrumb-item text-muted"><a class="text-muted text-hover-primary"
            href="{{ route('dashboard.cabang.index') }}">Cabang</a></li>
    <!--end::Item-->
    <!--begin::Item-->
    <li class="breadcrumb-item">
        <span class="bullet bg-gray-400 w-5px h-2px"></span>
    </li>
    <!--end::Item-->
    <!--begin::Item-->
    <li class="breadcrumb-item text-muted">Tambah Cabang</li>
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
                    data-bs-target="#kt" aria-expanded="true" aria-controls="kt">
                    <!--begin::Card title-->
                    <div class="card-title m-0">
                        <h3 class="fw-bold m-0">Tambah Cabng Baru</h3>
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
                    <form id="kt_form" class="form" action="{{ route('dashboard.cabang.store') }}" method="POST"
                        data-kt-redirect-url="{{ route('dashboard.cabang.index') }}">
                        @csrf
                        <!--begin::Card body-->
                        <div class="card-body border-top p-9">
                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label required fw-semibold fs-6">Kode Cabang</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">
                                    <input type="text" name="kode"
                                        class="form-control form-control-lg form-control-solid {{ $errors->has('kode') ? 'is-invalid' : '' }}"
                                        placeholder="Masukkan kode" />
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label required fw-semibold fs-6">Nama Kantor Cabang</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">
                                    <input type="text" name="nama"
                                        class="form-control form-control-lg form-control-solid {{ $errors->has('nama') ? 'is-invalid' : '' }}"
                                        placeholder="Masukkan nama kantor cabang" />
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label fw-semibold fs-6">Alias</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">
                                    <input type="text" name="alias"
                                        class="form-control form-control-lg form-control-solid {{ $errors->has('alias') ? 'is-invalid' : '' }}"
                                        placeholder="Masukkan alias" />
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label required fw-semibold fs-6">Alamat</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">
                                    <input type="text" name="alamat"
                                        class="form-control form-control-lg form-control-solid {{ $errors->has('alamat') ? 'is-invalid' : '' }}"
                                        placeholder="Masukkan alamat" />
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->

                        </div>
                        <!--end::Card body-->
                        <!--begin::Actions-->
                        <div class="card-footer d-flex justify-content-end py-6 px-9">
                            <button type="reset" class="btn btn-light btn-active-light-primary me-2">Batal</button>
                            <!--begin::Add new contact-->
                            <button type="submit" class="btn btn-primary" id="kt_submit">
                                <!--begin::Indicator label-->
                                <span class="indicator-label">Simpan</span>
                                <!--end::Indicator label-->
                                <!--begin::Indicator progress-->
                                <span class="indicator-progress">Please wait...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                <!--end::Indicator progress-->

                            </button>
                            <!--end::Add new contact-->
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
    {{-- <script src="{{ asset('assets/dashboard/') }}/js/roles/list.js"></script> --}}

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
                    'kode': {
                        validators: {
                            notEmpty: {
                                message: 'Kode tidak boleh kosong'
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
