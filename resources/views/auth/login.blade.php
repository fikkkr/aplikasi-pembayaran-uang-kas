<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login - Kas XI PPLG 1</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link href="{{ asset('argon/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link rel="icon" type="image/png" href="{{ asset('argon/img/gw/logo.png') }}">
    <link id="pagestyle" href="{{ asset('argon/css/argon-dashboard.css?v=2.0.4') }}" rel="stylesheet" />
</head>
<body class="">
    <main class="main-content mt-0">
        <section>
            <div class="page-header min-vh-100">
                <div class="container">
                    <div class="row">
                        {{-- FORM LOGIN --}}
                        <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-lg-0 mx-auto">
                            <div class="card card-plain">
                                <div class="card-header pb-0 text-start">
                                    <h4 class="font-weight-bolder text-primary text-gradient">Sign In</h4>
                                    <p class="mb-0 text-sm">Masukkan email dan password untuk masuk ke Sistem Kas</p>
                                </div>
                                <div class="card-body">
                                    @if(session('loginError'))
                                        <div class="alert alert-danger text-white text-xs mb-3" style="border-radius: 0.5rem;">
                                            {{ session('loginError') }}
                                        </div>
                                    @endif

                                    <form role="form" action="{{ route('login.proses') }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <input type="email" name="email" class="form-control form-control-lg" placeholder="Email" aria-label="Email" value="{{ old('email') }}" required>
                                            @error('email')
                                                <small class="text-danger text-xs ms-1">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <input type="password" name="password" class="form-control form-control-lg" placeholder="Password" aria-label="Password" required>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-lg btn-primary btn-lg w-100 mt-4 mb-0" style="border-radius: 0.5rem;">Sign in</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        {{-- SISI KANAN: FOTO KELAS TAHURA (FIXED TYPO) --}}
                        <div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 end-0 text-center justify-content-center flex-column">
                            <div class="position-relative h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center overflow-hidden" 
                                style="background-image: url('{{ asset('argon/img/gw/kelas_tahura.jpeg') }}'); background-size: cover; background-position: center;">
                                <span class="mask bg-gradient-primary opacity-6"></span>
                                <h4 class="mt-5 text-white font-weight-bolder position-relative" style="text-shadow: 0px 2px 4px rgba(0,0,0,0.5);">
                                    Sistem Kas XI PPLG 1
                                </h4>
                                <p class="text-white position-relative font-weight-bold" style="text-shadow: 0px 1px 3px rgba(0,0,0,0.5);">
                                    "Uang kas lancar, Bendahara teu ambek-ambekan."
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>
</html>