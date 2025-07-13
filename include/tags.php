
<!-- Favicon -->
   
    <link rel="apple-touch-icon" sizes="180x180" href="/img/favicon/faveicon_180X180.webp">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon/faveicon_32X32.webp">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon/faveicon_16X16.webp">
    <link rel="manifest" href="/site.webmanifest">
  

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500;600;700&family=Open+Sans:wght@400;500&display=swap" rel="stylesheet">  

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">



    <style>

        
.play-btn {
    width: 70px;
    height: 70px;
    background:  radial-gradient( rgb(52 142 56) 60%, rgba(255, 255, 255, 1) 62%);
   
    border-radius: 50%;
    position: relative;
    left:30px;
    /* display: block; */
    /* margin: 100px auto; */
    box-shadow: 0px 0px 25px 3px rgb(52 142 56);
    position: absolute;
    left: 40%;
    top: 40%;
  }
  
  /* triangle */
  .play-btn::after {
    content: "";
    position: absolute;
    left: 50%;
    top: 50%;
    -webkit-transform: translateX(-40%) translateY(-50%);
    transform: translateX(-40%) translateY(-50%);
    transform-origin: center center;
    width: 0;
    height: 0;
    border-top: 15px solid transparent;
    border-bottom: 15px solid transparent;
    border-left: 25px solid #fff;
    z-index: 100;
    -webkit-transition: all 400ms cubic-bezier(0.55, 0.055, 0.675, 0.19);
    transition: all 400ms cubic-bezier(0.55, 0.055, 0.675, 0.19);
  }
  
  /* pulse wave */
  .play-btn:before {
    content: "";
    position: absolute;
    width: 150%;
    height: 150%;
    -webkit-animation-delay: 0s;
    animation-delay: 0s;
    -webkit-animation: pulsate1 2s;
    animation: pulsate1 2s;
    -webkit-animation-direction: forwards;
    animation-direction: forwards;
    -webkit-animation-iteration-count: infinite;
    animation-iteration-count: infinite;
    -webkit-animation-timing-function: steps;
    animation-timing-function: steps;
    opacity: 1;
    border-radius: 50%;
    border: 5px solid rgba(255, 255, 255, .75);
    top: -30%;
    left: -30%;
    background: rgba(198, 16, 0, 0);
  }
  
  @-webkit-keyframes pulsate1 {
    0% {
      -webkit-transform: scale(0.6);
      transform: scale(0.6);
      opacity: 1;
      box-shadow: inset 0px 0px 25px 3px rgba(255, 255, 255, 0.75), 0px 0px 25px 10px rgba(255, 255, 255, 0.75);
    }
    100% {
      -webkit-transform: scale(1);
      transform: scale(1);
      opacity: 0;
      box-shadow: none;
  
    }
  }
  
  @keyframes pulsate1 {
    0% {
      -webkit-transform: scale(0.6);
      transform: scale(0.6);
      opacity: 1;
      box-shadow: inset 0px 0px 25px 3px rgba(255, 255, 255, 0.75), 0px 0px 25px 10px rgba(255, 255, 255, 0.75);
    }
    100% {
      -webkit-transform: scale(1, 1);
      transform: scale(1);
      opacity: 0;
      box-shadow: none;
  
    }
  }
  </style>
  
  
  <!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-N22J775Q');</script>
<!-- End Google Tag Manager -->
