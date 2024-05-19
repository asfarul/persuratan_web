@extends('dashboard.layouts.app')
@section('title', 'Permissions')
@section('page_title', 'Data Permissions')
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
    <li class="breadcrumb-item text-muted">Permissions</li>
    <!--end::Item-->
@endpush
@section('content')
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-xxl">
            <!--begin::Layout-->
            <div class="d-flex flex-column flex-lg-row">
                <!--begin::Sidebar-->
                <div class="flex-column flex-lg-row-auto w-100 w-lg-200px w-xl-300px mb-10">
                    <!--begin::Card-->
                    <div class="card card-flush">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <!--begin::Card title-->
                            <div class="card-title">
                                <h2 class="mb-0">Hak Akses Baru</h2>
                            </div>
                            <!--end::Card title-->
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            <form class="form" method="POST" action="{{ route('dashboard.permissions.store') }}"
                                novalidate="novalidate" id="kt_permission">
                                @csrf
                                <div class="fv-row mb-5">
                                    <input type="text" class="form-control form-control-solid" name="akses"
                                        placeholder="Nama Hak Akses" />
                                </div>

                                <!--begin::Checkboxes-->
                                <!--begin::Input group-->
                                <div class="fv-row mb-8">
                                    <label class="fs-6 fw-semibold mb-2"></label>
                                    <div class="form-check form-check-custom form-check-solid mb-3">
                                        <input class="form-check-input" type="checkbox" value="read" name="opsi[]"
                                            id="flexCheckDefault" checked />
                                        <label class="form-check-label" for="flexCheckDefault">
                                            Lihat
                                        </label>
                                    </div>



                                    <div class="form-check form-check-custom form-check-solid mb-3">
                                        <input class="form-check-input" type="checkbox" value="create" name="opsi[]"
                                            id="flexCheckDefault" checked />
                                        <label class="form-check-label" for="flexCheckDefault">
                                            Tambah
                                        </label>
                                    </div>



                                    <div class="form-check form-check-custom form-check-solid mb-3">
                                        <input class="form-check-input" type="checkbox" value="detail" name="opsi[]"
                                            id="flexCheckDefault" checked />
                                        <label class="form-check-label" for="flexCheckDefault">
                                            Detail
                                        </label>
                                    </div>



                                    <div class="form-check form-check-custom form-check-solid mb-3">
                                        <input class="form-check-input" type="checkbox" value="edit" name="opsi[]"
                                            id="flexCheckDefault" checked />
                                        <label class="form-check-label" for="flexCheckDefault">
                                            Ubah
                                        </label>
                                    </div>


                                    <div class="form-check form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" value="delete" name="opsi[]"
                                            id="flexCheckDefault" checked />
                                        <label class="form-check-label" for="flexCheckDefault">
                                            Hapus
                                        </label>
                                    </div>
                                </div>
                                <!--end::Input group-->
                                <!--end::Checkboxes-->

                                <!--begin::Separator-->
                                <div class="separator my-7"></div>
                                <!--begin::Separator-->
                                <!--begin::Add new contact-->
                                <button type="submit" class="btn btn-primary w-100" id="kt_permission_submit">
                                    <!--begin::Indicator label-->
                                    <span class="indicator-label">Buat</span>
                                    <!--end::Indicator label-->
                                    <!--begin::Indicator progress-->
                                    <span class="indicator-progress">Please wait...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    <!--end::Indicator progress-->

                                </button>
                                <!--end::Add new contact-->
                            </form>
                        </div>
                        <!--end::Card body-->

                    </div>
                    <!--end::Card-->
                </div>
                <!--end::Sidebar-->
                <!--begin::Content-->
                <div class="flex-lg-row-fluid ms-lg-10">
                    <!--begin::Card-->
                    <div class="card card-flush mb-6 mb-xl-9">
                        <!--begin::Card header-->
                        <div class="card-header pt-5">
                            <!--begin::Card title-->
                            <div class="card-title">
                                <h2 class="d-flex align-items-center">Hak Akses
                                    {{-- <span class="text-gray-600 fs-6 ms-1">(14)</span> --}}
                                </h2>
                            </div>
                            <!--end::Card title-->
                            <!--begin::Card toolbar-->
                            <div class="card-toolbar">
                                <!--begin::Search-->
                                <div class="d-flex align-items-center position-relative my-1"
                                    data-kt-docs-table-toolbar="base">
                                    <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                                    <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546"
                                                height="2" rx="1" transform="rotate(45 17.0365 15.1223)"
                                                fill="currentColor" />
                                            <path
                                                d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                                fill="currentColor" />
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon-->
                                    <input type="text" data-kt-docs-table-filter="search"
                                        class="form-control form-control-solid w-250px ps-15"
                                        placeholder="Cari hak akses" />
                                </div>
                                <!--end::Search-->
                                <!--begin::Group actions-->
                                <div class="d-flex justify-content-end align-items-center d-none"
                                    data-kt-docs-table-toolbar="selected">
                                    <div class="fw-bold me-5">
                                        <span class="me-2" data-kt-docs-table-select="selected_count"></span>Selected
                                    </div>
                                    <button type="button" class="btn btn-danger"
                                        data-kt-docs-table-select="delete_selected">Delete Selected</button>
                                </div>
                                <!--end::Group actions-->
                            </div>
                            <!--end::Card toolbar-->
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            <!--begin::Table-->
                            <table id="dataTable" class="table align-middle table-row-dashed fs-6 gy-5">
                                <thead>
                                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                        <th>ID</th>
                                        <th>Nama Hak Akses</th>
                                        <th class="text-end min-w-100px">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-600 fw-semibold">
                                </tbody>
                            </table>
                            <!--end::Table-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Card-->
                </div>
                <!--end::Content-->
            </div>
            <!--end::Layout-->
        </div>
        <!--end::Content container-->
    </div>
@endsection
@push('scripts')
    {{-- <script src="{{ asset('assets/dashboard/') }}/js/custom/apps/user-management/roles/view/view.js"></script> --}}
    {{-- <script src="{{ asset('assets/metro1/') }}/js/permissions/list.js"></script> --}}
    <script>
        // Define form element
        const form = document.getElementById('kt_permission');

        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        var validator = FormValidation.formValidation(
            form, {
                fields: {
                    'akses': {
                        validators: {
                            notEmpty: {
                                message: 'Nama akses tidak boleh kosong'
                            }
                        }
                    },
                    'opsi[]': {
                        validators: {
                            notEmpty: {
                                message: 'Opsi tidak boleh kosong'
                            }
                        }
                    }
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
        const submitButton = document.getElementById('kt_permission_submit');
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
                        }, 2000);
                    }
                });
            }
        });

        // // Show popup confirmation
        // Swal.fire({
        //     text: "Form has been successfully submitted!",
        //     icon: "success",
        //     buttonsStyling: false,
        //     confirmButtonText: "Ok, got it!",
        //     customClass: {
        //         confirmButton: "btn btn-primary"
        //     }
        // });

        var dataTable = $('#dataTable').DataTable({
            searchDelay: 500,
            processing: true,
            serverSide: true,
            order: [],
            // stateSave: true,
            select: {
                style: "multi",
                selector: 'td:first-child input[type="checkbox"]',
                className: "row-selected",
            },
            ajax: {
                url: "{!! route('dashboard.permissions.index') !!}",

            },
            columns: [{
                    data: "id"
                },
                {
                    data: "name"
                },
                {
                    data: 'action',
                    orderable: false,
                    searchable: false
                },
            ],
            columnDefs: [{
                targets: -1,
                className: "text-end",
            }, ],
            // Add data-filter attribute
            // createdRow: function(row, data, dataIndex) {
            //     $(row)
            //         .find("td:eq(4)")
            //         .attr("data-filter", data.CreditCardType);
            // },
        });

        const filterSearch = document.querySelector(
            '[data-kt-docs-table-filter="search"]'
        );
        filterSearch.addEventListener("keyup", function(e) {
            dataTable.search(e.target.value).draw();
        });


        // Handle delete button click
        dataTable.on('click', '.btn-delete', function() {
            var userId = $(this).data('id');
            var url = '{!! route('dashboard.roles.destroy', ':id') !!}';
            url = url.replace(':id', userId);
            $('#deleteForm').attr('action', url);
            Swal.fire({
                text: "Apakah anda yakin ingin menghapus " + userId + "?",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Tidak, batalkan",
                customClass: {
                    confirmButton: "btn fw-bold btn-danger",
                    cancelButton: "btn fw-bold btn-active-light-primary",
                },
            }).then(function(result) {
                if (result.value) {
                    // Simulate delete request -- for demo purpose only
                    $.ajax({
                        url: url,
                        type: "POST",
                        data: {
                            _method: "DELETE",
                        },
                        headers: {
                            "X-CSRF-TOKEN": $(
                                'meta[name="csrf-token"]'
                            ).attr("content"),
                        },
                        success: function(data) {
                            if (data.status == "success") {
                                dataTable.ajax.reload();
                                toastr.success(data.message);
                            } else {
                                toastr.error(data.responseJSON.message);
                            }
                        },
                    });
                } else if (result.dismiss === "cancel") {}
            });

        });
    </script>
@endpush
