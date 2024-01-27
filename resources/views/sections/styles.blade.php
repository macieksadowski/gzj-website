<!-- ======= Styles Section ======= -->
<section id="styles" class="styles">
    <div class="container">

        <div class="section-title">
            <h2>Stylizacje</h2>
            <p>Różnorodne stylizacje koncertowe</p>
        </div>

        <div class="row justify-content-center">
            @isset($styles)
                @foreach ($styles as $key => $style)
                    <div class="col-6 col-lg-4 d-flex align-items-stretch styles">
                        <div class="box styles__container" data-bs-toggle="modal" data-bs-target="#simple-modal"
                            data-bigimage="{{ asset('/img/styles/' . $style['name'] . '-lg.jpg') }}"
                            data-title="{{ $style['name'] }}">
                            <img src="{{ asset('/img/styles/' . $style['name'] . '-sm.jpg') }}" class="img-fluid"
                                alt="{{ $style['name'] }}"/>
                            <h4>{{ $style['name'] }}</h4>
                        </div>
                    </div>
                @endforeach
            @endisset
        </div>
        <div class="modal fade modal-image" role="dialog" tabindex="-1" id="simple-modal">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body"><img class="img-fluid" id="modal-image" src="" alt=""></div>
                    <div class="modal-footer">
                        <h4 class="modal-title" id="modal-title"></h4>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>


                </div>
            </div>
        </div>
</section><!-- End Styles Section -->
