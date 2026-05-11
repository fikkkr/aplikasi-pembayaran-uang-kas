<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login - GCT A Kas</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link href="{{ asset('argon/css/argon-dashboard.css') }}" rel="stylesheet" />
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
                                    <h4 class="font-weight-bolder">Masuk Bendahara 💸</h4>
                                    <p class="mb-0">Masukkan Email & Password GCT A Team</p>
                                </div>
                                <div class="card-body">
                                    @if ($errors->any())
                                        <div class="alert alert-danger text-white text-sm">{{ $errors->first() }}</div>
                                    @endif
                                    <form role="form" action="/login" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <input type="email" name="email" class="form-control form-control-lg" placeholder="Email (admin@gct.com)" required>
                                        </div>
                                        <div class="mb-3">
                                            <input type="password" name="password" class="form-control form-control-lg" placeholder="Password" required>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-lg btn-primary btn-lg w-100 mt-4 mb-0">Sign in</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 end-0 text-center justify-content-center flex-column">
                            <div class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center overflow-hidden" 
                                style="background-image: url('{{ asset('argon/img/gw/pawkoneng.jpeg') }}'); background-size: cover; background-position: center;">
                                <span class="mask bg-gradient-primary opacity-4"></span>
                                <h4 class="mt-5 text-white font-weight-bolder position-relative" style="z-index: 1;">"Bayar kas lancar, Bendahara teu ambek ambekan😹😹"</h4>
                                <p class="text-white position-relative" style="z-index: 1;">GCT A Team - XI PPLG 1</p>
                            </div>
                        </div>

                    </div> </div> </div> </section>
    </main>
</body>
</html>