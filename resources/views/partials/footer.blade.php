<div class="footer_bottom">
    <footer id="footer" class="footer mb-0">
    <div class="container footer-top">
        <div class="row gy-4">
            <div class="col-lg-4 col-md-6 footer-about">
                <a href="index.html" class="d-flex align-items-center">
                    <span class="sitename">Smart Global Hub</span>
                </a>
                <div class="footer-contact pt-3">
                    <p>Alfutaim office Tower Day to Day building</p>
                    <p>1st floor - Office 102 Smart Hub HQ</p>
                    <p class="mt-3"><strong>Phone:</strong> <span>+971 50 440 6565</span></p>
                    <p><strong>Email:</strong> <span>info@example.com</span></p>
                </div>
            </div>

            <div class="col-lg-2 col-md-3 footer-links">
                <h4>Useful Links</h4>
                <ul>
                    <li>
                        <a href="{{ route('/') }}" class="{{ Request::is('/') ? 'active' : '' }}">Home</a>
                    </li>
                    <li>
                        <a href="{{ route('about') }}" class="{{ Request::is('about') ? 'active' : '' }}">About</a>
                    </li>
                    <li>
                        <a href="{{ route('services') }}" class="{{ Request::is('services') ? 'active' : '' }}">Services</a>
                    </li>
                    <li>
                        <a href="{{ route('contact') }}" class="{{ Request::is('contact') ? 'active' : '' }}">Contact</a>
                    </li>
                </ul>
            </div>

            <div class="col-lg-2 col-md-3 footer-links">
                <h4>Our Services</h4>
                <ul>
                    <li><i class="bi bi-chevron-right"></i> <a href="{{ route('services') }}">Web Design</a></li>
                    <li><i class="bi bi-chevron-right"></i> <a href="{{ route('services') }}">Web Development</a></li>
                    <li><i class="bi bi-chevron-right"></i> <a href="{{ route('services') }}">Product Management</a></li>
                    <li><i class="bi bi-chevron-right"></i> <a href="{{ route('services') }}">Marketing</a></li>
                </ul>
            </div>

            <div class="col-lg-4 col-md-12">
                <h4>Follow Us</h4>
                <p>Driven by innovation, we pioneer progress and serve as trusted partners in every client's digital journey. Based in Dubai, our dedication spearheads digital transformation locally and globally.</p>
                <div class="social-links d-flex">
                    <a href=""><i class="bi bi-twitter-x"></i></a>
                    <a href=""><i class="bi bi-facebook"></i></a>
                    <a href=""><i class="bi bi-instagram"></i></a>
                    <a href=""><i class="bi bi-linkedin"></i></a>
                </div>
            </div>

        </div>
    </div>
{{--        <script>window.$zoho=window.$zoho || {};$zoho.salesiq=$zoho.salesiq||{ready:function(){}}</script><script id="zsiqscript" src="https://salesiq.zohopublic.com/widget?wc=siqbf4afaf8d969a0496ac1a623ca554492f31d681d172fb41f0c8b48a8af9113f6" defer></script>--}}
        <script async type='module' src='https://interfaces.zapier.com/assets/web-components/zapier-interfaces/zapier-interfaces.esm.js'></script>
        <zapier-interfaces-chatbot-embed is-popup='true' chatbot-id='cm6lvmkf8005610bitfu7e55w'></zapier-interfaces-chatbot-embed>
    </footer>
</div>
