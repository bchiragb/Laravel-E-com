<div class="main-wrapper main-wrapper-1">
  <div class="navbar-bg"></div>
  <nav class="navbar navbar-expand-lg main-navbar">
    <form class="form-inline mr-auto">
      <ul class="navbar-nav mr-3">
        <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
      </ul>
    </form>
    <ul class="navbar-nav navbar-right">
      <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
        <div class="d-sm-none d-lg-inline-block">Hi, {{ Auth::user()->name }}</div></a>
        <div class="dropdown-menu dropdown-menu-right">
          <a href="{{ route('admin.profile') }}" class="dropdown-item has-icon">
            <i class="far fa-user"></i> Profile
          </a>
          <div class="dropdown-divider"></div>
          <a href="{{ route('admin.logout') }}" class="dropdown-item has-icon text-danger">
            <i class="fas fa-sign-out-alt"></i> Logout
          </a>
        </div>
      </li>
    </ul>
  </nav>
  <div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
      <div class="sidebar-brand">
        <a target="_blank" href="{{ route('home') }}">Styler</a>
      </div>
      <div class="sidebar-brand sidebar-brand-sm">
        <a target="_blank" href="{{ route('home') }}">ST</a>
      </div>
      <ul class="sidebar-menu">
        <li class="{{ setActive(['admin.dashboard']) }}"><a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
        <li class="{{ setActive(['admin.order']) }}"><a class="nav-link" href="{{ route('admin.order') }}"><i class="fas fa-flag-checkered"></i> <span>Order</span></a></li>
        <li class="menu-header">Product</li>
        <li class="{{ setActive(['admin.product.index']) }}"><a class="nav-link" href="{{ route('admin.product.index') }}"><i class="fas fa-box"></i> <span>Product</span></a></li>
        <li class="dropdown {{ setActive(['admin.attribute.index', 'admin.product-category.index', 'admin.brand.index', 'admin.review.index']) }}">
          <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-th-large"></i> <span>Product Details</span></a>
          <ul class="dropdown-menu">
            <li class="{{ setActive(['admin.attribute.index']) }}"><a class="nav-link" href="{{ route('admin.attribute.index') }}"><i class="fas fa-tags"></i> <span>Attribute</span></a></li>
            <li class="{{ setActive(['admin.product-category.index']) }}"><a class="nav-link" href="{{ route('admin.product-category.index') }}"><i class="fas fa-tshirt"></i> <span>Category</span></a></li>
            <li class="{{ setActive(['admin.brand.index']) }}"><a class="nav-link" href="{{ route('admin.brand.index') }}"><i class="fas fa-star"></i> <span>Brand</span></a></li>
            <li class="{{ setActive(['admin.review.index']) }}"><a class="nav-link" href="{{ route('admin.review.index') }}"><i class="fas fa-comments"></i> <span>Review</span></a></li>
          </ul>
        </li>
          <li class="{{ setActive(['admin.coupon.index']) }}"><a class="nav-link" href="{{ route('admin.coupon.index') }}"><i class="fas fa-gift"></i> <span>Coupon</span></a></li>
          <li class="{{ setActive(['admin.shipping.index']) }}"><a class="nav-link" href="{{ route('admin.shipping.index') }}"><i class="fas fa-truck"></i> <span>Shipping</span></a></li>
        <li class="menu-header">Manage Site</li>
          <li class="{{ setActive(['admin.seo.index']) }}"><a class="nav-link" href="{{ route('admin.seo.index') }}"><i class="fab fa-envira"></i> <span>SEO</span></a></li>      
          <li class="{{ setActive(['admin.slider.index']) }}"><a class="nav-link" href="{{ route('admin.slider.index') }}"><i class="fas fa-sliders-h"></i> <span>Slider</span></a></li>      
          <li class="dropdown {{ setActive(['admin.homesetting', 'admin.social.index', 'admin.page.index', 'admin.faq.index', 'admin.footer.index', 'admin.menu.index']) }}">
          <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-store"></i> <span>Store Settings</span></a>
          <ul class="dropdown-menu">
            <li class="{{ setActive(['admin.homesetting']) }}"><a class="nav-link" href="{{ route('admin.homesetting') }}"><i class="fas fa-cogs"></i> Site Setting</a></li>
            <li class="{{ setActive(['admin.social.index']) }}"><a class="nav-link" href="{{ route('admin.social.index') }}"><i class="fas fa-hashtag"></i> Social link</a></li>
            <li class="{{ setActive(['admin.page.index']) }}"><a class="nav-link" href="{{ route('admin.page.index') }}"><i class="fas fa-file"></i> Page</a></li>
            <li class="{{ setActive(['admin.faq.index']) }}"><a class="nav-link" href="{{ route('admin.faq.index') }}"><i class="fas fa-quote-left"></i> FAQ</a></li>
            <li class="{{ setActive(['admin.menu.index']) }}"><a class="nav-link" href="{{ route('admin.menu.index') }}"><i class="fas fa-bars"></i> Menu</a></li>
            <li class="{{ setActive(['admin.footer.index']) }}"><a class="nav-link" href="{{ route('admin.footer.index') }}"><i class="fas fa-th"></i> Footer</a></li>
          </ul>
        </li>
      </ul>
    </aside>
  </div>