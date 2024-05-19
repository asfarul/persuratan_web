@extends('dashboard.layouts.app')
@section('title', 'Halaman Utama')
{{-- @section('page_title', 'Dashboard') --}}
{{-- @push('breadcrumbs') --}}
{{-- @endpush --}}
@section('content')
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-fluid">
        {{-- @if ($absensiCount != null)
            <div class="row g-5 g-xl-8">
                <h3 class="text-gray mb-5 mt-10">Statistik Kehadiran Seluruh ASN Hari ini</h3>
            </div>
            <div class="row g-5 g-xl-8">
                <div class="col-xl-2">
                    <!--begin::Statistics Widget 5-->
                    <a href="#" class="card bg-success hoverable card-xl-stretch mb-xl-8">
                        <!--begin::Body-->
                        <div class="card-body">
                            <!--begin::Svg Icon | path: icons/duotune/finance/fin006.svg-->
                            <span class="svg-icon svg-icon-white svg-icon-3x ms-n1">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect x="8" y="9" width="3" height="10" rx="1.5"
                                        fill="currentColor"></rect>
                                    <rect opacity="0.5" x="13" y="5" width="3" height="14"
                                        rx="1.5" fill="currentColor"></rect>
                                    <rect x="18" y="11" width="3" height="8" rx="1.5"
                                        fill="currentColor"></rect>
                                    <rect x="3" y="13" width="3" height="6" rx="1.5"
                                        fill="currentColor"></rect>
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                            <div class="text-white fw-bold fs-2 mb-2 mt-5">{{ $absensiCount['tw'] }}</div>
                            <div class="fw-semibold text-white">Tepat Waktu</div>
                        </div>
                        <!--end::Body-->
                    </a>
                    <!--end::Statistics Widget 5-->
                </div>
                <div class="col-xl-2">
                    <!--begin::Statistics Widget 5-->
                    <a href="#" class="card bg-success hoverable card-xl-stretch mb-xl-8">
                        <!--begin::Body-->
                        <div class="card-body">
                            <!--begin::Svg Icon | path: icons/duotune/finance/fin006.svg-->
                            <span class="svg-icon svg-icon-white svg-icon-3x ms-n1">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect x="8" y="9" width="3" height="10" rx="1.5"
                                        fill="currentColor"></rect>
                                    <rect opacity="0.5" x="13" y="5" width="3" height="14"
                                        rx="1.5" fill="currentColor"></rect>
                                    <rect x="18" y="11" width="3" height="8" rx="1.5"
                                        fill="currentColor"></rect>
                                    <rect x="3" y="13" width="3" height="6" rx="1.5"
                                        fill="currentColor"></rect>
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                            <div class="text-white fw-bold fs-2 mb-2 mt-5">{{ $absensiCount['lokasi'] }}</div>
                            <div class="fw-semibold text-white">Absen di Lokasi</div>
                        </div>
                        <!--end::Body-->
                    </a>
                    <!--end::Statistics Widget 5-->
                </div>
                <div class="col-xl-2">
                    <!--begin::Statistics Widget 5-->
                    <a href="#" class="card bg-warning hoverable card-xl-stretch mb-xl-8">
                        <!--begin::Body-->
                        <div class="card-body">
                            <!--begin::Svg Icon | path: icons/duotune/finance/fin006.svg-->
                            <span class="svg-icon svg-icon-white svg-icon-3x ms-n1">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect x="8" y="9" width="3" height="10" rx="1.5"
                                        fill="currentColor"></rect>
                                    <rect opacity="0.5" x="13" y="5" width="3" height="14"
                                        rx="1.5" fill="currentColor"></rect>
                                    <rect x="18" y="11" width="3" height="8" rx="1.5"
                                        fill="currentColor"></rect>
                                    <rect x="3" y="13" width="3" height="6" rx="1.5"
                                        fill="currentColor"></rect>
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                            <div class="text-white fw-bold fs-2 mb-2 mt-5">{{ $absensiCount['luar'] }}</div>
                            <div class="fw-semibold text-white">Absen di Luar Lokasi</div>
                        </div>
                        <!--end::Body-->
                    </a>
                    <!--end::Statistics Widget 5-->
                </div>
                <div class="col-xl-2">
                    <!--begin::Statistics Widget 5-->
                    <a href="#" class="card bg-primary hoverable card-xl-stretch mb-xl-8">
                        <!--begin::Body-->
                        <div class="card-body">
                            <!--begin::Svg Icon | path: icons/duotune/finance/fin006.svg-->
                            <span class="svg-icon svg-icon-white svg-icon-3x ms-n1">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect x="8" y="9" width="3" height="10" rx="1.5"
                                        fill="currentColor"></rect>
                                    <rect opacity="0.5" x="13" y="5" width="3" height="14"
                                        rx="1.5" fill="currentColor"></rect>
                                    <rect x="18" y="11" width="3" height="8" rx="1.5"
                                        fill="currentColor"></rect>
                                    <rect x="3" y="13" width="3" height="6" rx="1.5"
                                        fill="currentColor"></rect>
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                            <div class="text-white fw-bold fs-2 mb-2 mt-5">{{ $absensiCount['manual'] }}</div>
                            <div class="fw-semibold text-white">Absen Manual</div>
                        </div>
                        <!--end::Body-->
                    </a>
                    <!--end::Statistics Widget 5-->
                </div>
                <div class="col-xl-2">
                    <!--begin::Statistics Widget 5-->
                    <a href="#" class="card bg-danger hoverable card-xl-stretch mb-xl-8">
                        <!--begin::Body-->
                        <div class="card-body">
                            <!--begin::Svg Icon | path: icons/duotune/finance/fin006.svg-->
                            <span class="svg-icon svg-icon-white svg-icon-3x ms-n1">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect x="8" y="9" width="3" height="10" rx="1.5"
                                        fill="currentColor"></rect>
                                    <rect opacity="0.5" x="13" y="5" width="3" height="14"
                                        rx="1.5" fill="currentColor"></rect>
                                    <rect x="18" y="11" width="3" height="8" rx="1.5"
                                        fill="currentColor"></rect>
                                    <rect x="3" y="13" width="3" height="6" rx="1.5"
                                        fill="currentColor"></rect>
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                            <div class="text-white fw-bold fs-2 mb-2 mt-5">{{ $absensiCount['ttw'] }}</div>
                            <div class="fw-semibold text-white">Telat Masuk</div>
                        </div>
                        <!--end::Body-->
                    </a>
                    <!--end::Statistics Widget 5-->
                </div>
                <div class="col-xl-2">
                    <!--begin::Statistics Widget 5-->
                    <a href="#" class="card bg-danger hoverable card-xl-stretch mb-xl-8">
                        <!--begin::Body-->
                        <div class="card-body">
                            <!--begin::Svg Icon | path: icons/duotune/finance/fin006.svg-->
                            <span class="svg-icon svg-icon-white svg-icon-3x ms-n1">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect x="8" y="9" width="3" height="10" rx="1.5"
                                        fill="currentColor"></rect>
                                    <rect opacity="0.5" x="13" y="5" width="3" height="14"
                                        rx="1.5" fill="currentColor"></rect>
                                    <rect x="18" y="11" width="3" height="8" rx="1.5"
                                        fill="currentColor"></rect>
                                    <rect x="3" y="13" width="3" height="6" rx="1.5"
                                        fill="currentColor"></rect>
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                            <div class="text-white fw-bold fs-2 mb-2 mt-5">{{ $absensiCount['cp'] }}</div>
                            <div class="fw-semibold text-white">Cepat Pulang</div>
                        </div>
                        <!--end::Body-->
                    </a>
                    <!--end::Statistics Widget 5-->
                </div>
            </div>
        @endif
        @if ((auth()->user()->hasRole('Moderator') ||
        auth()->user()->hasRole('Superadmin')) &&
    !auth()->user()->hasRole('Pegawai'))
            @include('dashboard.layouts.widgets.home.admin')
        @endif
        @if (auth()->user()->hasRole('Pegawai'))
            @include('dashboard.layouts.widgets.home.pegawai')
        @endif --}}
        <!--end::Content container-->
    </div>

@endsection
@push('scripts')
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        Pusher.logToConsole = true;

        var pusher = new Pusher('2aea297483dbb0ad3113', {
            cluster: 'ap1'
        });

        var channel = pusher.subscribe('smart-tv.update');
        channel.bind('smart-tv', function(data) {
            alert(JSON.stringify(data));
        });
    </script>
    {{-- <script src="{{ asset('assets/dashboard/') }}/cdn.amcharts.com/lib/5/index.js"></script> --}}
    {{-- <script src="{{ asset('assets/dashboard/') }}/cdn.amcharts.com/lib/5/xy.js"></script> --}}
    {{-- <script src="{{ asset('assets/dashboard/') }}/cdn.amcharts.com/lib/5/percent.js"></script> --}}
    {{-- <script src="{{ asset('assets/dashboard/') }}/cdn.amcharts.com/lib/5/radar.js"></script> --}}
    {{-- <script src="{{ asset('assets/dashboard/') }}/cdn.amcharts.com/lib/5/themes/Animated.js"></script> --}}
    {{-- <script src="{{ asset('assets/dashboard/') }}/cdn.amcharts.com/lib/5/map.js"></script> --}}
    {{-- <script src="{{ asset('assets/dashboard/') }}/cdn.amcharts.com/lib/5/geodata/worldLow.js"></script> --}}
    {{-- <script src="{{ asset('assets/dashboard/') }}/cdn.amcharts.com/lib/5/geodata/continentsLow.js"></script> --}}
    {{-- <script src="{{ asset('assets/dashboard/') }}/cdn.amcharts.com/lib/5/geodata/usaLow.js"></script> --}}
    {{-- <script src="{{ asset('assets/dashboard/') }}/cdn.amcharts.com/lib/5/geodata/worldTimeZonesLow.js"></script> --}}
    {{-- <script src="{{ asset('assets/dashboard/') }}/cdn.amcharts.com/lib/5/geodata/worldTimeZoneAreasLow.js"></script> --}}
@endpush
