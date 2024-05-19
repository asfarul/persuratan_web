<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
    <title>PERSURATAN WEB | Login</title>
    <meta charset="utf-8" />
    <meta name="description" content="Admin Panel Sistem Front Office PERSURATAN WEB" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="canonical" href="{{ url('/') }}" />
    <link rel="shortcut icon" href="{{ asset('assets/metro1/') }}/media/logos/favicon.ico" />
    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="{{ asset('assets/metro1/') }}/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/metro1/') }}/css/style.bundle.css" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->
    <!--Begin::Google Tag Manager -->
    {{-- <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src = '../../../../../../www.googletagmanager.com/gtm5445.html?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-5FS8GGP');
    </script> --}}
    <!--End::Google Tag Manager -->
</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_body" class="app-blank app-blank bgi-size-cover bgi-position-center bgi-no-repeat">
    <!--begin::Theme mode setup on page load-->
    <script>
        var defaultThemeMode = "light";
        var themeMode;
        if (document.documentElement) {
            if (document.documentElement.hasAttribute("data-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-theme-mode");
            } else {
                if (localStorage.getItem("data-theme") !== null) {
                    themeMode = localStorage.getItem("data-theme");
                } else {
                    themeMode = defaultThemeMode;
                }
            }
            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }
            document.documentElement.setAttribute("data-theme", themeMode);
        }
    </script>
    <!--end::Theme mode setup on page load-->
    <!--Begin::Google Tag Manager (noscript) -->
    <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5FS8GGP" height="0" width="0"
            style="display:none;visibility:hidden"></iframe>
    </noscript>
    <!--End::Google Tag Manager (noscript) -->
    <!--begin::Root-->
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <!--begin::Page bg image-->
        <style>
            /* body {
                background-image: url('{{ asset('assets/metro1/') }}/media/auth/bg10.jpg');
            }

            [data-theme="dark"] body {
                background-image: url('{{ asset('assets/metro1/') }}/media/auth/bg10-dark.jpg');
            } */
        </style>
        <!--end::Page bg image-->
        <!--begin::Authentication - Sign-in -->
        <div class="d-flex flex-column flex-lg-row flex-column-fluid">
            <!--begin::Aside-->
            <div class="d-flex flex-lg-row-fluid">
                <!--begin::Content-->
                <div class="d-flex flex-column flex-center pb-0 pb-lg-10 p-10 w-100">
                    <!--begin::Image-->
                    PERSURATAN WEB
                    {{-- <img class="theme-light-show mx-auto mw-100 w-150px w-lg-500px mb-10 mb-lg-20"
                        src="{{ asset('assets/metro1/') }}/media/logos/bank-kalbar-logo.png" alt="" />
                    <img class="theme-dark-show mx-auto mw-100 w-150px w-lg-500px mb-10 mb-lg-20" --}}
                        {{-- src="{{ asset('assets/metro1/') }}/media/logos/bank-kalbar-logo.png" alt="" /> --}}
                    <!--end::Image-->
                    <!--begin::Title-->
                    {{-- <h1 class="text-gray-800 fs-2qx fw-bold text-center mb-7">Admin Panel</h1> --}}
                    <!--end::Title-->
                    <!--begin::Text-->
                    {{-- <div class="text-gray-600 fs-base text-center fw-semibold">Kinerja Efektif, Semangat Kembangkan
                        Potensi Secara Online.
                    </div> --}}
                    <!--end::Text-->
                </div>
                <!--end::Content-->
            </div>
            <!--begin::Aside-->
            <!--begin::Body-->
            <div class="d-flex flex-column-fluid flex-lg-row-auto justify-content-center justify-content-lg-end p-12">
                <!--begin::Wrapper-->
                <div class="bg-body d-flex flex-center rounded-4 w-md-600px p-10">
                    <!--begin::Content-->
                    <div class="w-md-400px">
                        <!--begin::Form-->
                        <form class="form w-100" method="POST" novalidate="novalidate" id="kt_sign_in_form"
                            action="{{ route('login') }}" data-kt-redirect-url="{{ route('dashboard.home') }}">
                            @csrf
                            <!--begin::Heading-->
                            <div class="text-center mb-11">
                                <!--begin::Title-->
                                {{-- <img class="mx-auto mw-100 w-50px w-lg-50px mb-2 mb-lg-2"
                                    src="{{ asset('assets/metro1/') }}/media/logos/logo.png"
                                    alt="" /> --}}
                                {{-- <div class="text-dark fw-semibold fs-5 mb-5">PERSURATAN WEB</div> --}}
                                <h1 class="text-dark fw-bolder mb-3">Sign In</h1>
                                <!--end::Title-->
                                <!--begin::Subtitle-->
                                {{-- <div class="text-gray-500 fw-semibold fs-6">Your Social Campaigns</div> --}}
                                <!--end::Subtitle=-->
                            </div>
                            <!--begin::Input group=-->
                            <div class="fv-row mb-8">
                                <!--begin::Email-->
                                <input type="text" placeholder="Nomor Induk" name="nip"
                                    autocomplete="on" class="form-control bg-transparent" />
                                <!--end::Email-->
                            </div>
                            <!--end::Input group=-->
                            <div class="fv-row mb-3">
                                <!--begin::Password-->
                                <input type="password" placeholder="Password" name="password" autocomplete="off"
                                    class="form-control bg-transparent" />
                                <!--end::Password-->
                            </div>
                            <!--end::Input group=-->
                            @if ($errors->get('password'))
                                <ul {{ $attributes->merge(['class' => 'text-sm text-red-600 space-y-1']) }}>
                                    @foreach ($errors->get('password') as $message)
                                        <li>{{ $message }}</li>
                                    @endforeach
                                </ul>
                            @endif
                            <!--begin::Submit button-->
                            <div class="d-grid mb-10">
                                <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
                                    <!--begin::Indicator label-->
                                    <span class="indicator-label">Sign In</span>
                                    <!--end::Indicator label-->
                                    <!--begin::Indicator progress-->
                                    <span class="indicator-progress">Please wait...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    <!--end::Indicator progress-->
                                </button>
                            </div>
                            <!--end::Submit button-->
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Content-->
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Body-->
        </div>
        <!--end::Authentication - Sign-in-->
    </div>
    <!--end::Root-->
    <!--begin::Javascript-->
    <script>
        var hostUrl = "{{ asset('assets/metro1/') }}/index.html";
    </script>
    <!--begin::Global Javascript Bundle(mandatory for all pages)-->
    <script src="{{ asset('assets/metro1/') }}/plugins/global/plugins.bundle.js"></script>
    <script src="{{ asset('assets/metro1/') }}/js/scripts.bundle.js"></script>
    <!--end::Global Javascript Bundle-->
    <!--begin::Custom Javascript(used for this page only)-->
    <script src="{{ asset('assets/metro1/') }}/js/custom/authentication/sign-in/general.js"></script>
    <!--end::Custom Javascript-->
    <!--end::Javascript-->
    <script>
        @error('nip')
            Swal.fire({
                text: "NIP dan Password yang dimasukkan salah!",
                icon: "error",
                buttonsStyling: false,
                confirmButtonText: "Baik, Saya mengerti!",
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            });
        @enderror
    </script>
</body>
<!--end::Body-->

</html>
