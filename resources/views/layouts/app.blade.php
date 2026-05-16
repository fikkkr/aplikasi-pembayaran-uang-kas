<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Kas XI PPLG 1</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link href="{{ asset('argon/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('argon/css/nucleo-svg.css') }}" rel="stylesheet" />
    <link rel="icon" type="image/png" href="{{ asset('argon/img/gw/logo_kas.png') }}">
    <link id="pagestyle" href="{{ asset('argon/css/argon-dashboard.css?v=2.0.4') }}" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>

    <style>
        /* SIDEBAR BASE */
        .sidenav {
            background-color: #ffffff !important;
            position: fixed !important;
            border-radius: 1.5rem !important;
            box-shadow: 0 8px 26px rgba(0,0,0,0.1) !important;
            z-index: 1050;
            transition: all 0.3s ease;
        }

        /* DESKTOP (Layar Lebar) */
        @media (min-width: 1200px) {
            .sidenav {
                top: 20px !important;
                left: 20px !important;
                bottom: 20px !important;
                width: 250px !important;
                height: calc(100vh - 40px) !important;
            }
            .main-content {
                margin-left: 285px !important;
            }
        }

        /* TABLET & HP (Layar Kecil) */
        @media (max-width: 1199.98px) {
            .sidenav {
                left: -250px !important;
                height: 100vh !important;
                top: 0 !important;
                bottom: 0 !important;
                border-radius: 0 !important;
            }
            .g-sidenav-pinned .sidenav {
                left: 0 !important;
            }
            .main-content {
                margin-left: 0 !important;
            }
        }

        .bg-primary-custom {
            background-color: #5e72e4;
            height: 300px;
            width: 100%;
            position: absolute;
            top: 0;
            z-index: 0;
        }

        @media (max-width: 575.98px) {
            h4.font-weight-bolder { font-size: 1rem !important; }
            .text-xs { font-size: 0.65rem !important; }
            .icon-box { width: 35px !important; height: 35px !important; }
            .icon-box i { font-size: 0.8rem !important; }
            .card-stats { padding: 10px !important; }
            .mb-4 { margin-bottom: 1rem !important; }
            .bg-primary-custom { height: 200px !important; }
        }
    </style>
</head>

<body class="g-sidenav-show bg-gray-100">
    <div class="bg-primary-custom"></div>

    <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 fixed-start" id="sidenav-main">
        <div class="sidenav-header">
            <a class="navbar-brand m-0" href="#">
                <img src="{{ asset('argon/img/gw/logo_kas.png') }}" class="navbar-brand-img h-100" alt="main_logo">
                <span class="ms-1 font-weight-bold">Kas XI PPLG 1</span>
            </a>
        </div>
        <hr class="horizontal dark mt-0">
        
        <div class="collapse navbar-collapse w-auto h-auto" id="sidenav-collapse-main">
            <ul class="navbar-nav">
                {{-- DASHBOARD --}}
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}" href="/dashboard">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-tv-2 text-primary text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Dashboard</span>
                    </a>
                </li>

                {{-- MENU BARU: MONITORING KAS MINGGUAN (PERIODE) --}}
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('pembayaran.index') ? 'active' : '' }}" href="{{ route('pembayaran.index') }}">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-calendar-grid-58 text-info text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Kas Mingguan</span>
                    </a>
                </li>

                {{-- DATA MURID --}}
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('murid*') ? 'active' : '' }}" href="/murid">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-bullet-list-67 text-warning text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Data Murid</span>
                    </a>
                </li>

                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Transaksi Umum</h6>
                </li>

                {{-- PEMASUKAN UMUM --}}
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('pembayaran.umum') ? 'active' : '' }}" href="{{ route('pembayaran.umum') }}">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-cloud-download-95 text-success text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Pemasukan Umum</span>
                    </a>
                </li>

                {{-- CATAT PENGELUARAN --}}
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('pembayaran.pengeluaran') ? 'active' : '' }}" href="{{ route('pembayaran.pengeluaran') }}">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-cloud-upload-96 text-danger text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Catat Pengeluaran</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>

    <main class="main-content">
        <div class="container-fluid py-4" style="position: relative; z-index: 1;">
            @yield('content')
        </div>
    </main>

    <script src="{{ asset('argon/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('argon/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('argon/js/argon-dashboard.min.js?v=2.0.4') }}"></script>
</body>
</html>