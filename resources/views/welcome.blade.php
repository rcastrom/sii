<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="SII">
    <meta name="author" content="Ricardo Castro">

    <title>SII:: IT Ensenada</title>

    <!-- Bootstrap Core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>


    <!-- Custom Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.5.5/css/simple-line-icons.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('css/stylish-portfolio.min.css') }}" rel="stylesheet">

</head>

<body id="page-top">

<!-- Navigation -->
<a class="menu-toggle rounded" href="#">
    <i class="fas fa-bars"></i>
</a>
<nav id="sidebar-wrapper">
    <ul class="sidebar-nav">
        <li class="sidebar-brand">
            <a class="js-scroll-trigger" href="#page-top">Accesos rápidos</a>
        </li>
        <li class="sidebar-nav-item">
            <a class="js-scroll-trigger" href="https://www.ensenada.tecnm.mx/" target="_self">WWW</a>
        </li>
        <li class="sidebar-nav-item">
            <a class="js-scroll-trigger" href="https://spe.ensenada.tecnm.mx/" target="_self">SPE</a>
        </li>
        <li class="sidebar-nav-item">
            <a class="js-scroll-trigger" href="https://aspirante.ensenada.tecnm.mx/">Ficha</a>
        </li>
        <li class="sidebar-nav-item">
            <a class="js-scroll-trigger" href="https://noadeudo.ensenada.tecnm.mx/">No Adeudo</a>
        </li>
    </ul>
</nav>

<!-- Header -->
<header class="masthead d-flex">
    <div class="container text-center my-auto">
        <h1 class="mb-1">SII</h1>
        <h3 class="mb-5">
            <em>TecNM Campus Ensenada</em>
        </h3>
        @if (Route::has('login'))
            @auth
                <a class="btn btn-primary btn-xl js-scroll-trigger" href="/">Regresar</a>
            @else
                <a class="btn btn-primary btn-xl js-scroll-trigger" href="{{ route('login') }}">Ingresar</a>
            @endauth
        @endif
    </div>
    <div class="overlay"></div>
</header>

<!-- Portfolio -->
<section class="content-section" id="portfolio">
    <div class="container">
        <div class="content-section-heading text-center">
            <h3 class="text-secondary mb-0">Otros accesos</h3>
            <h2 class="mb-5">Sitios adicionales de la Institución </h2>
        </div>
        <div class="row no-gutters">
            <div class="col-lg-6">
                <a class="portfolio-item" href="https://aspirante.ensenada.tecnm.mx">
                    <div class="caption">
                        <div class="caption-content">
                            <div class="h2">¿Nuevo ingreso?</div>
                            <p class="mb-0">Acceso al sistema de solicitud de ficha para nuevo ingreso</p>
                        </div>
                    </div>
                    <img class="img-fluid" src="{{ asset('img/portfolio-1.jpg') }}" alt="">
                </a>
            </div>
            <div class="col-lg-6">
                <a class="portfolio-item" href="https://spe.ensenada.tecnm.mx">
                    <div class="caption">
                        <div class="caption-content">
                            <div class="h2">SPE</div>
                            <p class="mb-0">Sistema de Pago Electrónico</p>
                        </div>
                    </div>
                    <img class="img-fluid" src="{{ asset('img/portfolio-2.jpg') }}" alt="">
                </a>
            </div>
            <div class="col-lg-6">
                <a class="portfolio-item" href="https://noadeudo.ensenada.tecnm.mx">
                    <div class="caption">
                        <div class="caption-content">
                            <div class="h2">No adeudo</div>
                            <p class="mb-0">¿Próximo a graduarse? Genera recibo de que no hay adeudos con la institución</p>
                        </div>
                    </div>
                    <img class="img-fluid" src="{{ asset('img/portfolio-3.jpg') }}" alt="">
                </a>
            </div>
            <div class="col-lg-6">
                <a class="portfolio-item" href="https://www.ensenada.tecnm.mx">
                    <div class="caption">
                        <div class="caption-content">
                            <div class="h2">Página principal</div>
                            <p class="mb-0">Página oficial del Tecnológico</p>
                        </div>
                    </div>
                    <img class="img-fluid" src="{{ asset('img/portfolio-4.jpg') }}" alt="">
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Map -->
<div id="contact" class="map">
    <iframe src="https://www.google.com/maps/d/embed?mid=1Qj8xxWNijZu8P7J4CVR6F1NJh24&amp;hl=es&amp;geocode=&amp;q=TecNM+Campus+Ensenada+Blvd+Tecnol&oacute;gico+150+Col+Ex+Ejido+Chapultepec+Ensenada,+BC&amp;aq=0&amp;oq=ITE;output=embed"></iframe>
    <br />
    <small>
        <a href="https://www.google.com/maps/d/?mid=1Qj8xxWNijZu8P7J4CVR6F1NJh24&amp;hl=es&amp;geocode=&amp;q=TecNM+Campus+Ensenada+Blvd+Tecnol&oacute;gico+150+Col+Ex+Ejido+Chapultepec+Ensenada,+BC&amp;aq=0&amp;source=embed"></a>
    </small>
</div>

<!-- Footer -->
<footer class="footer text-center">
    <div class="container">
        <ul class="list-inline mb-5">
            <li class="list-inline-item">
                <a class="social-link rounded-circle text-white mr-3" href="https://www.facebook.com/TecNMEnsenada/">
                    <i class="icon-social-facebook"></i>
                </a>
            </li>
            <li class="list-inline-item">
                <a class="social-link rounded-circle text-white mr-3" href="https://twitter.com/TecNMEnsenada">
                    <i class="icon-social-twitter"></i>
                </a>
            </li>
            <li class="list-inline-item">
                <a class="social-link rounded-circle text-white" href="https://www.instagram.com/tecnmensenada/">
                    <i class="icon-social-instagram"></i>
                </a>
            </li>
        </ul>
        <p class="text-muted small mb-0">Copyright &copy; 2021 Instituto Tecnológico de Ensenada</p>
    </div>
</footer>

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded js-scroll-trigger" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Bootstrap core JavaScript -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Custom scripts for this template -->
<script src="{{ asset('js/stylish-portfolio.min.js') }}"></script>

</body>

</html>

