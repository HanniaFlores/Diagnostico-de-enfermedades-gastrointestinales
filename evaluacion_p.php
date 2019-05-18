<?php 
 require_once('includes/load.php'); 

 $puntuaciones = values_each_symptom_by_illness_table();
$sintomas =  join_dictinct_symptoms_table();

$sintomasPaciente = $_GET['valSintomas'];

// echo "<pre>";
// var_dump($sintomasPaciente);
// echo "</pre>";

/**
 * Obtaining the punctuation of each
 *symptom in conjunction with your illness
  NOTE  insertar las demás enfermedades
  
 */

$a_enf = [APENDICITIS, COLITIS];
$a_patron = []; //Reacibe los valores de los patrones
$a_div_patron = [];
for ($i=0; $i < sizeof($a_enf) ; $i++) { 
    $clave = $a_enf[$i];
    foreach ($puntuaciones as $key ) {
        $valor = $key[$clave];
        if( $valor < 1){
            array_push($a_patron, $valor);
        }
    }
}

// Un vector que contiene todos los patrones de sintomas por enfermedad 
$a_div_patron = array_chunk($a_patron, SINTOMAS);

//Efectua la operacion
$salida = interseccion_v2($a_div_patron, $sintomasPaciente);

//Array final incluye la salida de la interseccion más el grado de confiabilidad
// por enfermedad

$salida_div = array_chunk($salida, SINTOMAS + 1);


// echo "<pre>";
// var_dump($salida_div);
// echo "</pre>";

// Obtener los grados de confiabilidad de cada enfermedad
$a_grados = [];

for ($m=0; $m < sizeof($salida_div); $m++) { 

    for ($h=0; $h < SINTOMAS + 1 ; $h++) { 
        
        if ( $m == $m and $h == SINTOMAS) {
        
            $temp = $salida_div[$m][$h];
            array_push($a_grados, $temp, $a_enf[$m]);
            
        }

    }
}

// echo "<pre>";
// var_dump($a_grados);
// echo "</pre>";

$resDiagnostico = Diagnostico($a_grados);
$resEnfermedad = umbral($resDiagnostico);
// if($resEnfermedad){
//     $consultas =  join_illness_medication_name_table($resEnfermedad);
//     echo "<table style='border: 1px solid #000;'>";
//     foreach ($consultas as $key) {
//         $nombre =  utf8_encode($key['nombenf']);
//         $imagen = $key['imgenf'];
//         $origen = utf8_encode($key['origenenf']);
//         $trata = utf8_encode( $key['tratamiento']);

//     echo "<tr>";

//     echo "<td>". $nombre ."</td>";
//     echo "<td>". $imagen."</td>";
//     echo "<td>". $origen ."</td>";
//     echo "<td>". $trata ."</td>";

//     echo "</tr>";

//     }
 
//     echo "</table>"; 

// }


// echo "<pre>";
// var_dump($resDiagnostico);
// echo "</pre>";


?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Snipp - Free Bootstrap 4 Template by Colorlib</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <link rel="stylesheet" href="css/open-iconic-bootstrap.min.css">
    <link rel="stylesheet" href="css/animate.css">
    
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="css/magnific-popup.css">

    <link rel="stylesheet" href="css/aos.css">

    <link rel="stylesheet" href="css/ionicons.min.css">

    <link rel="stylesheet" href="css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="css/jquery.timepicker.css">

    
    <link rel="stylesheet" href="css/flaticon.css">
    <link rel="stylesheet" href="css/icomoon.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/main.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">

<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.3.5/css/swiper.min.css'>
  </head>
  <body>
    
    <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
      <div class="container">
        <a class="navbar-brand" href="index.html">MediCare.</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="oi oi-menu"></span> Menu
        </button>

        <div class="collapse navbar-collapse" id="ftco-nav">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item active"><a href="index.php" class="nav-link">Home</a></li>
          <li class="nav-item"><a href="#" class="nav-link">Acerca de</a></li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle"  id="dropdown04" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Diagnósticos</a>
              <div class="dropdown-menu" aria-labelledby="dropdown04">
                <a class="dropdown-item" href="diag-precargado.php">Diagnóstico Precargado</a>
                <a class="dropdown-item" href="diag-general.php">Diagnóstico General</a>
                <a class="dropdown-item" href="diag-especifico.php">Diagnóstico Específico</a>
              </div>
          </li>
          <li class="nav-item"><a href="blog.html" class="nav-link">Case Studies</a></li>
          <li class="nav-item"><a href="contact.html" class="nav-link">Contact</a></li>
          <li class="nav-item cta"><a href="contact.html" class="nav-link"><span>Get in touch</span></a></li>
        </ul>
      </div>
      </div>
    </nav>

    
    <div class="hero-wrap js-fullheight">
      <div class="overlay"></div>
      <div id="particles-js"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center" data-scrollax-parent="true">
          <div class="col-md-6 ftco-animate text-center" data-scrollax=" properties: { translateY: '70%' }">
            <p class="breadcrumbs" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }"><span class="mr-2"><a href="index.html">Home</a></span> <span>Evaluación</span></p>
            <h1 class="mb-3 bread" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }">Evaluación</h1>
          </div>
        </div>
      </div>
    </div>
    
    <section class="ftco-section">
      <div class="container">
        <div class="row no-gutters justify-content-center mb-5 pb-5">
        <div class="col-md-6 text-center heading-section ftco-animate">
            <span class="subheading">Enfermedades Gastrointestinales</span>
            <h2 class="mb-4">Tus resultados fueron</h2>
          </div>
        </div>
        <?php 
        if($resEnfermedad){

            $consultas =  join_illness_medication_name_table($resEnfermedad);
            foreach ($consultas as $key) {
              $nombre =  utf8_encode($key['nombenf']);
              $imagen =  remove_junk($key['imgenf']);
              $origen = utf8_encode($key['origenenf']);
              $trata = utf8_encode( $key['tratamiento']);
        ?>
        <div class="row">
            <div class="blog-slider">
              <div class="blog-slider__wrp swiper-wrapper">
                <div class="blog-slider__item swiper-slide">
                  <div class="blog-slider__img">
                    
                    <img src="images/enfermedades/<?php echo $imagen; ?>" alt="">
                  </div>
                  <div class="blog-slider__content">
                    <span class="blog-slider__code"><?php echo "Fecha: " . date("d") . " del " . date("m") . " de " . date("Y"); ?></span>
                    <div class="blog-slider__title"><?php echo $nombre; ?></div>
                    <div class="blog-slider__text">Descripción:   <?php echo $origen; ?></div>
                    <div class="blog-slider__text">Tratamiento:   <?php echo $trata; ?></div>
                    <a href="#" class="blog-slider__button">VOLVER AL INICIO</a>
                  </div>
                </div>
              
                
              </div>
              <div class="blog-slider__pagination"></div>
            </div>
        </div>

        <?php  
            }

       }
        ?>

      </div>
    </section>
    

    <footer class="ftco-footer ftco-bg-dark ftco-section">
      <div class="container">
        <div class="row mb-5">
          <div class="col-md">
            <div class="ftco-footer-widget mb-4">
              <h2 class="ftco-heading-2">Snipp.</h2>
              <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</p>
            </div>
          </div>
          <div class="col-md">
            <div class="ftco-footer-widget mb-4 ml-5">
              <h2 class="ftco-heading-2">Quick Links</h2>
              <ul class="list-unstyled">
                <li><a href="#" class="py-2 d-block">Home</a></li>
                <li><a href="#" class="py-2 d-block">Case studies</a></li>
                <li><a href="#" class="py-2 d-block">Services</a></li>
                <li><a href="#" class="py-2 d-block">Portfolio</a></li>
                <li><a href="#" class="py-2 d-block">About</a></li>
                <li><a href="#" class="py-2 d-block">Contact</a></li>
              </ul>
            </div>
          </div>
          <div class="col-md">
             <div class="ftco-footer-widget mb-4">
              <h2 class="ftco-heading-2">Contact Information</h2>
              <ul class="list-unstyled">
                <li><a href="#" class="py-2 d-block">198 West 21th Street, Suite 721 New York NY 10016</a></li>
                <li><a href="#" class="py-2 d-block">+ 1235 2355 98</a></li>
                <li><a href="#" class="py-2 d-block">info@yoursite.com</a></li>
                <li><a href="#" class="py-2 d-block">email@email.com</a></li>
              </ul>
            </div>
          </div>
          <div class="col-md">
            <div class="ftco-footer-widget mb-4">
              <ul class="ftco-footer-social list-unstyled float-md-left float-lft">
                <li class="ftco-animate"><a href="#"><span class="icon-twitter"></span></a></li>
                <li class="ftco-animate"><a href="#"><span class="icon-facebook"></span></a></li>
                <li class="ftco-animate"><a href="#"><span class="icon-instagram"></span></a></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 text-center">

            
  Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made with <i class="icon-heart" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
  <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p>
          </div>
        </div>
      </div>
    </footer>
  
  <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/></svg></div>


  <script src="js/jquery.min.js"></script>
  <script src="js/jquery-migrate-3.0.1.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/jquery.easing.1.3.js"></script>
  <script src="js/jquery.waypoints.min.js"></script>
  <script src="js/jquery.stellar.min.js"></script>
  <script src="js/owl.carousel.min.js"></script>
  <script src="js/jquery.magnific-popup.min.js"></script>
  <script src="js/aos.js"></script>
  <script src="js/jquery.animateNumber.min.js"></script>
  <script src="js/bootstrap-datepicker.js"></script>
  <script src="js/jquery.timepicker.min.js"></script>
  <script src="js/particles.min.js"></script>
  <script src="js/particle.js"></script>
  <script src="js/scrollax.min.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&sensor=false"></script>
  <script src="js/google-map.js"></script>
  <script src="js/main.js"></script>

  <script src='https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.3.5/js/swiper.min.js'></script>
  <script>
    var swiper = new Swiper('.blog-slider', {
      spaceBetween: 30,
      effect: 'fade',
      loop: true,
      mousewheel: {
        invert: false,
      },
      // autoHeight: true,
      pagination: {
        el: '.blog-slider__pagination',
        clickable: true,
      }
    });
  </script>
    
  </body>
</html> 