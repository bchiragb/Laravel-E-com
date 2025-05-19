@extends('frontend.layout.master')

@section('body_content')

<div id="page-content">
    <div class="page section-header text-center">
        <div class="page-title">
            <div class="wrapper"><h1 class="page-width">Contact Us</h1></div>
        </div>
    </div>      
    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-12 col-md-8 col-lg-8 mb-4">
                <h2 class="form-group">Drop Us A Line</h2>
                <div class="formFeilds contact-form form-vertical">
                    <form id="contact_form" method="post" class="contact-form">
                        @csrf
                        <div class="row">
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <input type="text" id="ContactFormName" name="name" placeholder="Name" value="" required="">
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group">
                            <input type="email" id="ContactFormEmail" name="email" placeholder="Email" value="" required="">                        	
                            </div>
                        </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                            <input required="" type="tel" id="ContactFormPhone" name="phone" pattern="[0-9\-]*" placeholder="Phone Number" value="">
                            </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                            <input required="" type="text" id="ContactSubject" name="subject" placeholder="Subject" value="">
                            </div>
                            </div>
                        </div>
                        <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                            <textarea required="" rows="10" id="ContactFormMessage" name="message" placeholder="Your Message"></textarea>
                            </div>
                        </div>  
                        </div>
                        <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <input type="submit" class="btn" value="Send Message" id="submitBtn">
                        </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                <h2 class="form-group">Feel free to contact</h2>
                <ul class="addressFooter">
                    <li><i class="icon anm anm-map-marker-al"></i><p>{{ $infobox_s1['val3'] }}</p></li>
                    <li class="phone"><a href="tel:{{ $infobox_s1['val1'] }}"><i class="icon anm anm-phone-s"></i><p>{{ $infobox_s1['val1'] }}</p></a></li>
                    <li class="email"><a href="mailto:{{ $infobox_s1['val2'] }}"><i class="icon anm anm-envelope-l"></i><p>{{ $infobox_s1['val2'] }}</p></a></li>
                </ul>
                <hr />
                <ul class="list--inline site-footer__social-icons social-icons">
                    @foreach ($social_icon as $soc)
                        <li><a class="social-icons__link" href="{{ $soc->val3 }}" target="_blank" title="social link of {{ $soc->val1 }}"><i class="iconq {{ $soc->val2 }}"></i><span class="icon__fallback-text">{{ $soc->val1 }}</span></a></li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>   
</div>

@endsection


@push('scripts')
    <script>
        $(document).ready(function(){ 
            $('#contact_form').on('submit', function (e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $('#submitBtn').attr('disabled', true).text('Submitting...');
                $.ajax({
                    url: '{{ route("contactmail") }}',  // Use the appropriate route
                    method: 'POST',
                    data: formData,
                    success: function(data) {
                        if(data.status == "success"){
                            flasher.success(data.message);
                        } else {
                            flasher.warning(data.message);
                        }
                        $('#contact_form')[0].reset();
                        $('#submitBtn').attr('disabled', false).text('Submit');
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            });        
        });
    </script>
@endpush()