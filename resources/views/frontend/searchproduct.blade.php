@extends('frontend.layout.master')

@section('body_content')

<div id="page-content">
    <div class="page section-header text-center">
        <div class="page-title">
            <div class="wrapper"><h1 class="page-width">Search</h1></div>
        </div>
    </div>      
    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-12 col-md-8 col-lg-8 main-col">
                <div class="alert alert-success text-uppercase" role="alert">
                    <i class="icon fa fa-search icon-large"></i> &nbsp;Product Search: {{ $query }}
                </div>
                <div class="cart style2">
                    {{-- @if($products && $products->isNotEmpty()) --}}
                    @if($products)
                        <table class="table-hover">
                            <thead class="cart__row cart__header">
                                <tr>
                                    <th colspan="2" class="text-center">Product Title</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $pro)
                                    <tr class="cart__row border-bottom line1 cart-flex border-top">
                                        <td class="cart__image-wrapper cart-flex-item">
                                            <a href="{{ route('product', [$pro['slug']]) }}"><img class="cart__image" src="{{ asset($pro['img1']) }}" alt="{{ $pro['title'] }}"></a>
                                        </td>
                                        <td class="cart__meta small--text-left cart-flex-item">
                                            <div class="list-view-item__title">
                                                <a href="{{ route('product', [$pro['slug']]) }}">{{ $pro['title'] }}</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table> 
                    @else
                        <div class="newsletter-section">
                            <div class="containerx">
                                <div class="row">
                                    No product found
                                </div>
                            </div>    
                        </div>
                    @endif
                    
                </div>                   
            </div>           
        </div>
    </div>
</div>
<style>.row { justify-content: center; } .list-view-item__title { padding-left: 40px; } .newsletter-section { padding: 15px 0; } </style>

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