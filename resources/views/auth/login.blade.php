<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login - Kas XI PPLG 1</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link href="{{ asset('argon/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link rel="icon" type="image/png" href="{{ asset('public/img/gw/logo.png') }}">
    <link id="pagestyle" href="{{ asset('argon/css/argon-dashboard.css?v=2.0.4') }}" rel="stylesheet" />
</head>
<body class="">
    <main class="main-content mt-0">
        <section>
            <div class="page-header min-vh-100">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-lg-0 mx-auto">
                            <div class="card card-plain">
                                <div class="card-header pb-0 text-start">
                                    <h4 class="font-weight-bolder">Sign In</h4>
                                    <p class="mb-0">Masukkan email dan password untuk masuk ke Kas Sistem</p>
                                </div>
                                <div class="card-body">
                                    {{-- Alert Error jika Login Gagal --}}
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
                        <div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 end-0 text-center justify-content-center flex-column">
                            <div class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center overflow-hidden" 
                                 style="background-image: url('https://raw.githubusercontent.com/creativetimofficial/external-assets/main/argon-dashboard/backgrounds/signin-glam.jpg'); background-size: cover;">
                                <span class="mask bg-gradient-primary opacity-6"></span>
                                <h4 class="mt-5 text-white font-weight-bolder position-relative">"Sistem kas XI PPLG 1 "</h4>
                                <p class="text-white position-relative">Uang kas lancar, Bendahara teu ambek ambekan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>
</html>