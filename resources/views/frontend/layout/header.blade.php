<div class="search">
    <div class="search__form">
        <form action="{{ route('searchproduct') }}" class="search-bar__form" method="GET">
            <button class="go-btn search__button" type="submit"><i class="icon anm anm-search-l"></i></button>
            <input class="search__input" type="search" name="q" value="" placeholder="Search entire store..." aria-label="Search" autocomplete="off">
        </form>
        <button type="button" class="search-trigger close-btn"><i class="icon anm anm-times-l"></i></button>
    </div>
</div>
<div class="top-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-10 col-sm-8 col-md-5 col-lg-4">
                <!-- <div class="currency-picker">
                    <span class="selected-currency">USD</span>
                    <ul id="currencies">
                        <li data-currency="INR" class="">INR</li>
                        <li data-currency="GBP" class="">GBP</li>
                        <li data-currency="CAD" class="">CAD</li>
                        <li data-currency="USD" class="selected">USD</li>
                        <li data-currency="AUD" class="">AUD</li>
                        <li data-currency="EUR" class="">EUR</li>
                        <li data-currency="JPY" class="">JPY</li>
                    </ul>
                </div>
                <div class="language-dropdown">
                    <span class="language-dd">English</span>
                    <ul id="language">
                        <li class="">German</li>
                        <li class="">French</li>
                    </ul>
                </div>
                <p class="phone-no"><a href="tel:"><i class="anm anm-phone-s"></i> </a></p> -->
                <p class="mail_id"><a href="mailto:{{ $infobox_s1['val2'] }}"><i class="icon anm anm-envelope-l"></i> {{ $infobox_s1['val2'] }}</a></p>
            </div>
            <div class="col-sm-4 col-md-4 col-lg-4 d-none d-lg-none d-md-block d-lg-block">
                <div class="text-center"><p class="top-header_middle-text"> {{ $infobox_s1['val4'] }}</p></div>
            </div>
            <div class="col-2 col-sm-4 col-md-3 col-lg-4 text-right">
                <span class="user-menu d-block d-lg-none"><i class="anm anm-user-al" aria-hidden="true"></i></span>
                <ul class="customer-links list-inline">
                  {{-- {{ $msg }} --}}
                  @if (Auth::check())
                    <li><a href="{{ route('dashboard') }}">Hi, {{ auth()->user()->name }}</a></li>
                    <li><a href="{{ route('dashboard') }}">My Account</a></li>
                  @else
                    <li><a href="{{ route('login') }}">Login</a></li>
                    <li><a href="{{ route('register') }}">Create Account</a></li>
                  @endif
                    <li><a href="{{ route('wishlist.index') }}">Wishlist</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="header-wrap animated d-flex">
    <div class="container-fluid">        
        <div class="row align-items-center">
            <div class="logo col-md-2 col-lg-2 d-none d-lg-block">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('uploads/styler.jpg') }}" alt="Styler - Online clothing store" title="Styler - Online clothing store" />
                </a>
            </div>
            <div class="col-2 col-sm-3 col-md-3 col-lg-8">
                <div class="d-block d-lg-none">
                    <button type="button" class="btn--link site-header__menu js-mobile-nav-toggle mobile-nav--open">
                        <i class="icon anm anm-times-l"></i>
                        <i class="anm anm-bars-r"></i>
                    </button>
                </div>
                <nav class="grid__item" id="AccessibleNav" role="navigation"><!-- for mobile -->
                    @php echo getmenu(1); @endphp
                </nav>
            </div>
            <div class="col-6 col-sm-6 col-md-6 col-lg-2 d-block d-lg-none mobile-logo">
                <div class="logo">
                    <a href="index.html">
                        <img src="{{ asset('fassets/images/logo.svg') }}" alt="Belle Multipurpose Html Template" title="Belle Multipurpose Html Template" />
                    </a>
                </div>
            </div>
            <div class="col-4 col-sm-3 col-md-3 col-lg-2">
                <div class="site-cart">
                  <a href="#;" class="site-header__cart" title="Cart">
                      <i class="icon anm anm-bag-l"></i>
                      <span id="CartCount" class="site-header__cart-count" data-cart-render="item_count">{{ Cart::content()->count(); }}</span>
                  </a>
                  <div id="header-cart" class="block block-cart">
                      <ul class="mini-products-list">
                        @php 
                        if(Cart::content()->count() > 0) {
                          foreach(Cart::content() as $row) { 
                            if($row->options['type'] == 1) { $variation_data = ""; 
                            } else { 
                                $variation_data = getvariationdata($row->options['idx'], 0);
                            }
                            echo '
                            <li class="item pro_'.$row->rowId.'">
                                <a class="product-image" href="'.route('product', $row->options['slug']).'">
                                    <img src="'.asset($row->options['img']).'" alt="'.$row->name.'" title="'.$row->name.'" />
                                </a>
                                <div class="product-details">
                                    <a href="javascript:;" class="remove rmove_itm" data="'.$row->rowId.'"><i class="anm anm-times-l" aria-hidden="true"></i></a>
                                    <a class="pName" href="'.route('product', $row->options['slug']).'">'.$row->name.'</a>
                                    <div class="variant-cart">'.$variation_data.'</div>
                                    <div class="priceRow">
                                        <div class="product-price">
                                            <span class="money pro_qty">Qty: '.$row->qty.'</span> |
                                            <span class="money pro_pri">Price: '.$currency.$row->price.'</span> 
                                        </div>
                                      </div>
                                </div>
                            </li>
                            ';
                          }
                        } else { echo '<div class="nopro">Cart is empty.</div>'; }
                        @endphp
                      </ul>
                      @php if(Cart::content()->count() == 0) { $classx = "hideme"; } else { $classx = ''; }@endphp
                      <div class="total {{ $classx }}">
                          <div class="total-in">
                              <span class="label">Cart Subtotal:</span><span class="product-price"><span class="money">{{ $currency }}{{ Cart::subtotal() }}</span></span>
                          </div>
                            <div class="buttonSet text-center">
                              <a href="{{ route('cart') }}" class="btn btn-secondary btn--small">View Cart</a>
                              <a href="{{ route('checkout') }}" class="btn btn-secondary btn--small">Checkout</a>
                          </div>
                      </div>
                  </div>
                </div>
                <div class="site-header__search">
                    <button type="button" class="search-trigger"><i class="icon anm anm-search-l"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mobile-nav-wrapper" role="navigation">
    <div class="closemobileMenu"><i class="icon anm anm-times-l pull-right"></i> Close Menu</div>
    @php echo getmenu(2); @endphp
</div>