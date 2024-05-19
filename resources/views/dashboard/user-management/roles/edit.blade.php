@extends('dashboard.layouts.app')
@section('title', 'Roles')
@section('page_title', 'Edit Role')
@push('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush
@push('breadcrumbs')
    <!--begin::Item-->
    <li class="breadcrumb-item">
        <span class="bullet bg-gray-400 w-5px h-2px"></span>
    </li>
    <!--end::Item-->
    <!--begin::Item-->
    <li class="breadcrumb-item text-muted">Manajemen Pengguna</li>
    <!--end::Item-->
    <!--begin::Item-->
    <li class="breadcrumb-item">
        <span class="bullet bg-gray-400 w-5px h-2px"></span>
    </li>
    <!--end::Item-->
    <!--begin::Item-->
    <li class="breadcrumb-item text-muted"><a class="text-muted text-hover-primary"
            href="{{ route('dashboard.roles.index') }}">Roles</a></li>
    <!--end::Item-->
    <!--begin::Item-->
    <li class="breadcrumb-item">
        <span class="bullet bg-gray-400 w-5px h-2px"></span>
    </li>
    <!--end::Item-->
    <!--begin::Item-->
    <li class="breadcrumb-item text-muted">Edit Role</li>
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
                        <h3 class="fw-bold m-0">Ubah Data Role</h3>
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
                    <form id="kt_roles_form" class="form" action="{{ route('dashboard.roles.update', $role->id) }}"
                        method="POST" data-kt-redirect-url="{{ route('dashboard.roles.index') }}">
                        @csrf
                        <!--begin::Card body-->
                        <div class="card-body border-top p-9">

                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label required fw-semibold fs-6">Nama Role</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">
                                    <input type="text" name="name"
                                        class="form-control form-control-lg form-control-solid {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                        placeholder="Masukkan nama role" value="{{ old('name') ?? $role->name }}" />
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label fw-semibold fs-6">Hak Akses</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">
                                    <select name="permissions[]" class="form-select form-select-solid"
                                        data-control="select2" multiple="multiple" data-actions-box="true"
                                        data-close-on-select="false" data-placeholder="Pilih hak akses"
                                        data-allow-clear="true">
                                        @foreach ($permissions as $id => $permissions)
                                            <option value="{{ $id }}"
                                                {{ in_array($id, old('permissions', [])) ||(isset($role) &&$role->permissions()->pluck('name', 'id')->contains($id))? 'selected': '' }}>
                                                {{ $permissions }}</option>
                                        @endforeach
                                    </select>
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
                            <button type="submit" class="btn btn-primary" id="kt_roles_submit">
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
    <script src="{{ asset('assets/metro1/') }}/js/roles/form.js"></script>
    <script>
        @if (Session::has('success'))
            toastr.success("{!! Session::get('success') !!}");
        @endif

        @if (Session::has('error'))
            toastr.error("{!! Session::get('error') !!}");
        @endif
    </script>
@endpush
