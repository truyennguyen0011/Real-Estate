<footer class="footer footer-black footer-big">
    <div class="container">

        <div class="content">
            <div class="row">
                <div class="col-md-4">
                    <h5><img height="36px" src="{{ asset(config('app.logo_image')) }}" alt="{{ config('app.name') }}"></h5>
                    <p>
                        <strong>Địa chỉ: </strong>
                        <span class="text-white">{{ config('app.company_address') }}</span>
                    </p>
                    <p>
                        <strong>Hotline: </strong>
                        <a href="tel:{{ config('app.company_hotline') }}">{{ config('app.company_hotline') }}</a>
                    </p>
                    <p>
                        <strong>Email: </strong>
                        <a href="mailto:{{ config('app.company_email') }}">
                            {{ config('app.company_email') }}
                        </a>
                    </p>
                </div>

                <div class="col-md-4">
                    <h5>Dịch vụ của chúng tôi</h5>
                    <div class="social-feed">
                        @if (config('app.my_services_1') != '')
                            <div class="feed-line">
                                <p class="text-white">
                                    {{ config('app.my_services_1') }}
                                </p>
                            </div>
                        @endif
                        @if (config('app.my_services_2') != '')
                            <div class="feed-line">
                                <p class="text-white">
                                    {{ config('app.my_services_2') }}
                                </p>
                            </div>
                        @endif
                        @if (config('app.my_services_3') != '')
                            <div class="feed-line">
                                <p class="text-white">
                                    {{ config('app.my_services_3') }}
                                </p>
                            </div>
                        @endif
                        @if (config('app.my_services_4') != '')
                            <div class="feed-line">
                                <p class="text-white">
                                    {{ config('app.my_services_4') }}
                                </p>
                            </div>
                        @endif
                        @if (config('app.my_services_5') != '')
                            <div class="feed-line">
                                <p class="text-white">
                                    {{ config('app.my_services_5') }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-md-4">
                    <h5>Kết nối với chúng tôi</h5>
                    <div class="gallery-feed">
                        {!! config('app.fb_page') !!}


                        <div id="map" style="max-width:360px;max-height:200px; margin-top: 18px">
                            {!! config('app.map_embed') !!}
                        </div>
                    </div>

                </div>
            </div>
        </div>


        <hr />

        <div class="copyright pull-center">
            Copyright &copy;
            <script>
                document.write(new Date().getFullYear())
            </script>
            <strong>{{ config('app.name') . ' - ' . config('app.description') }}</strong>
        </div>
    </div>
</footer>
