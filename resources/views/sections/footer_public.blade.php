 <!-- ======= Footer ======= -->
 <footer id="footer">
     <div class="footer-top">
         <div class="container">

             <div class="section-title">
                 <h2>Kontakt</h2>
                 <p>Skontaktuj się z nami</p>
             </div>

             <div class="row">

                 <div class="col-lg-4 col-sm-12 contact">
                     <a href="tel:{{ $phone }}">
                         <div class="row contact__element">
                             <div class="col-4 contact__icon">
                                 <img src="{{ asset('/img/ico-phone.png') }}" alt="Telefon" />
                             </div>
                             <div class="col-8 contact__info">
                                 <h4>Telefon</h4>
                                 <p>Bartosz Matuszczak</p>
                                 <p> +48 123 456 789</p>
                             </div>
                         </div>
                     </a>
                     <a href="mailto:{{ $mail }}">
                         <div class="row contact__element">
                             <div class="col-4 contact__icon">
                                 <img src="{{ asset('/img/ico-mail.png') }}" alt="E-mail" />
                             </div>
                             <div class="col-8 contact__info">
                                 <h4>E-mail</h4>
                                 <p>glownyzaworjazzu@gmail.com</p>
                             </div>
                         </div>
                     </a>
                 </div>

                 <div class="col-lg-4 col-sm-12 links">
                     <div class="row footer-top__buttons">
                         <div class="col-lg-12 col-sm-4">
                            <a href="{{ $menuItems['Oferta'] }}" target="blank" class="link-button">Oferta koncertowa</a>
                         </div>
                         <div class="col-lg-12 col-sm-4">
                            <a href="{{ $menuItems['Do pobrania']['Rider'] }}" target="blank" class="link-button">Rider techniczny</a>
                         </div>
                         <div class="col-lg-12 col-sm-4">
                            <a href="{{ $menuItems['Do pobrania']['Presspack'] }}" target="blank" class="link-button">Presspack</a>
                         </div>
                     </div>
                 </div>

                 <div class="col-lg-4 col-sm-12 links">
                     <div class="row justify-content-center">
                         <div class="col-4 links__logo">
                             <img src="{{ asset('img/logo-footer.png') }}" alt="GZJ Logo">
                         </div>
                     </div>
                     <div class="row justify-content-center socials">
                         <div class="col-2 socials__link">
                             <a href="{{ $socialLinks['FB'] }}" target=" _blank"
                                 class="link-button link-button_social"><i class="bi bi-facebook"></i></a>
                         </div>
                         <div class="col-2 socials__link">
                             <a href="{{ $socialLinks['IG'] }}" target=" _blank"
                                 class="link-button link-button_social"><i class="bi bi-instagram"></i></a>
                         </div>
                         <div class="col-2 socials__link">
                             <a href="{{ $socialLinks['YT'] }}" target=" _blank"
                                 class="link-button link-button_social"><i class="bi bi-youtube"></i></a>
                         </div>
                         <div class="col-2 socials__link">
                             <a href="{{ $socialLinks['TK'] }}" target=" _blank"
                                 class="link-button link-button_social"><i class="bi bi-tiktok"></i></a>
                         </div>
                     </div>
                 </div>


             </div>
         </div>
     </div>

     <div class="container-fluid footer-bottom">
         <div class="copyright">
             Główny Zawór Jazzu &copy;&nbsp;{{ date('Y') }}&nbsp;Maciej&nbsp;Sadowski
         </div>
         <div class="credits">
             <!-- All the links in the footer should remain intact. -->
             <!-- You can delete the links only if you purchased the pro version. -->
             <!-- Licensing information: https://bootstrapmade.com/license/ -->
             <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/gp-free-multipurpose-html-bootstrap-template/ -->
             Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
         </div>
     </div>
 </footer><!-- End Footer -->
