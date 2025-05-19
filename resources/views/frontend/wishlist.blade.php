@extends('frontend.layout.master')

@section('body_content')

<div id="page-content">
    <div class="page section-header text-center">
        <div class="page-title">
            <div class="wrapper"><h1 class="page-width">Wishlist</h1></div>
          </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-12 col-md-3 col-lg-3 cart__footer">
                <div class="cart-note">
                    <div class="solid-border">
                        {{-- <h5><label for="CartSpecialInstructions" class="cart-note__label small--text-center">Latest Product</label></h5> --}}
                        <div class="sidebar_widget static-banner">
                            <img src="http://estore.test/fassets/images/side-banner-2.jpg" alt="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-9 col-lg-9 main-col">
                @if(!Auth::check())
                <div class="alert alert-info text-uppercase" role="alert">
                    <i class="icon anm anm-heart-l icon-large"></i> &nbsp;Login to save your product in wishlist
                </div>
                @endif
                <div class="cart style2">
                    @if(count($productx) > 0)
                        <table>
                            <thead class="cart__row cart__header">
                                <tr>
                                    <th class="text-left">Image</th>
                                    <th colspan="2" class="text-left">Product</th>
                                    <th class="action text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($productx as $product)
                                    <tr class="cart__row border-bottom line1 cart-flex border-top wish_{!! $product['idx'] !!}">
                                        <td class="cart__image-wrapper cart-flex-item">
                                            <a href="{{ route('product', [$product['slug']]) }}"><img class="cart__image" src="{{ asset($product['img']) }}" alt="{{ $product['title'] }}"></a>
                                        </td>
                                        <td colspan="2" class="cart__meta small--text-left cart-flex-item">
                                            <div class="list-view-item__title"><a href="{{ route('product', [$product['slug']]) }}">{{ $product['title'] }}</a></div>
                                        </td>
                                        <td class="text-right small--hide"><a href="javascript:void(0)" class="btn btn--secondary cart__remove" title="Remove item" data-val="{{ $product['idx'] }}"><i class="icon icon anm anm-times-l"></i></a></td>
                                    </tr>   
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-left"></td>
                                    <td colspan="3" class="text-right">
                                        <button type="button" name="clear" class="btn btn-secondary btn--small small--hide clear_wish">Clear Wishlist</button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table> 
                    @else
                        <div class="alert alert-info text-uppercase" role="alert">
                            No product added in wishlist
                        </div>
                    @endif
                </div>                   
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    <script src="{{ asset('fassets/js/vendor/photoswipe.min.js') }}"></script>
    <script src="{{ asset('fassets/js/vendor/photoswipe-ui-default.min.js') }}"></script>
    <script>
        $(document).ready(function(){ 
            $('.cart__remove').on('click', function(){
                var idx = $(this).attr('data-val');
                var url = "{{ route('wishlist.update', ':idx') }}".replace(':idx', idx);
                $.ajax({
                    method: "PUT",  
                    url: url,
                    data: { _token: "{{ csrf_token() }}", idx:idx },
                    success: function(data){
                        if(data.status == "success"){
                            flasher.success(data.message);
                            $('.wish_'+idx).remove();
                        }                            
                    },
                    error: function(data){ console.log(data); }
                });
            });
            //
            $('.clear_wish').on('click', function(){
                var idx = 0;
                var url = "{{ route('wishlist.update', ':idx') }}".replace(':idx', idx);
                $.ajax({
                    method: "PUT",  
                    url: url,
                    data: { _token: "{{ csrf_token() }}", idx:idx },
                    success: function(data){
                        if(data.status == "success"){
                            flasher.success(data.message);
                            setTimeout(function() {
                                var url = "{{ route('wishlist.index') }}"; 
                                window.location.href = url;
                            }, 2500);
                        }                            
                    },
                    error: function(data){ console.log(data); }
                });
            });
        });
    </script>
@endpush    