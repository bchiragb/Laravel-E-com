<?php

use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\backend\AttributeController;
use App\Http\Controllers\Backend\BrandController;
use App\Http\Controllers\backend\CouponController;
use App\Http\Controllers\backend\FaqController;
use App\Http\Controllers\Backend\MenuController;
use App\Http\Controllers\Backend\OrderViewController;
use App\Http\Controllers\backend\PageController;
use App\Http\Controllers\backend\ProductCateoryController;
use App\Http\Controllers\backend\ProductController;
use App\Http\Controllers\backend\ProductImgsController;
use App\Http\Controllers\backend\ProductReviewController;
use App\Http\Controllers\backend\ProductVariantController;
use App\Http\Controllers\Backend\SeoController;
use App\Http\Controllers\backend\ShippingController;
use App\Http\Controllers\backend\SiteSettingController;
use App\Http\Controllers\backend\SliderController;
use App\Http\Controllers\backend\SocialController;
use App\Http\Controllers\Backend\UserDashboardController as BackendUserDashboardController;
use App\Http\Controllers\backend\VariantController;
use App\Http\Controllers\frontend\CartController;
use App\Http\Controllers\frontend\CheckoutController;
use App\Http\Controllers\Frontend\CodController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\frontend\ProductViewController;
use App\Http\Controllers\Frontend\UserDashboardController;
use App\Http\Controllers\frontend\WishlistController;
use App\Http\Controllers\frontend\PaypalController;
use App\Http\Controllers\Frontend\RazorpayController;
use App\Http\Controllers\frontend\StripeController;
use App\Http\Controllers\Frontend\UsrOrderController;
use App\Http\Controllers\ProductCategoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
//
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/post-login', [AuthController::class, 'post_login'])->name('post_login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
//
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/post-register', [AuthController::class, 'post_register'])->name('post_register');
//
Route::post('/forgotpassword', [AuthController::class, 'forgotpassword'])->name('forgotpassword');
Route::get('/password/reset/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/savepassword', [AuthController::class, 'savepassword'])->name('savepassword');
//
Route::get('/product/{slug}', [ProductViewController::class, 'showproduct'])->name('product');
Route::get('/product', [ProductViewController::class, 'getproduct'])->name('getproduct');
Route::get('/onlyvariant', [ProductViewController::class, 'onlyvariant'])->name('onlyvariant');
//
Route::get('/category/{slug}', [ProductViewController::class, 'showcategory'])->name('category');
Route::get('/change-list-view', [ProductViewController::class, 'chageListView'])->name('change-list-view');
Route::get('/shop', [ProductViewController::class, 'shopnow'])->name('shop');
//Route::get('category/list', [ProductViewController::class, 'showcategory'])->name('category.list');
Route::post('/category/sort', [ProductViewController::class, 'showcategory'])->name('category.sort');

//Route::get('{slug}', [ProductViewController::class, 'squery'])->name('squery'); // for product, category
//
Route::post('/addtocart', [CartController::class, 'addtocart'])->name('addtocart');
Route::post('/add_to_cart', [CartController::class, 'add_to_cart'])->name('add_to_cart'); //buynow
//
Route::get('/cart', [CartController::class, 'cart'])->name('cart');
Route::post('/cartdestroy', [CartController::class, 'cartdestroy'])->name('cartdestroy');
Route::post('/removeitem', [CartController::class, 'removeitem'])->name('removeitem');
Route::post('/plusitem', [CartController::class, 'plusitem'])->name('plusitem');
Route::post('/minusitem', [CartController::class, 'minusitem'])->name('minusitem');
Route::post('/chkcoupon', [CartController::class, 'chkcoupon'])->name('chkcoupon');
Route::post('/rmovecoupon', [CartController::class, 'rmovecoupon'])->name('rmovecoupon');
Route::post('/shiprate', [CartController::class, 'shiprate'])->name('shiprate');
//
Route::get('/checkout', [CheckoutController::class, 'checkout'])->name('checkout');
Route::post('/checkout2', [CheckoutController::class, 'checkout2'])->name('checkout2');
Route::get('/checkout2', [CheckoutController::class, 'checkout2'])->name('checkout2');
//
Route::post('/reviewsave', [ProductReviewController::class, 'reviewsave'])->name('reviewsave');
//
Route::resource('wishlist', WishlistController::class)->names('wishlist');
//
Route::post('/getstate', [ShippingController::class, 'getstate'])->name('getstate');
//
Route::get('/payment/sucess/{id}', [CodController::class, 'success_show'])->name('payment.success');
Route::get('/payment/cancel/{id}', [CodController::class, 'cancel_show'])->name('payment.cancel');
//
//Route::get('/cod/payment/{id}', [CodController::class, 'success'])->name('cod.success');
Route::get('/cod/payment/{id}', [CodController::class, 'success'])->name('cod.success');
//
Route::post('/subscribe', [AuthController::class, 'subscribe'])->name('subscribe');
//
//Route::post('/contact', [AuthController::class, 'subscribe'])->name('subscribe');
Route::get('/contact-us', [PageController::class, 'contactpage'])->name('contactus');
Route::post('/contactmail', [PageController::class, 'contactmail'])->name('contactmail');
Route::post('/productinqmail', [PageController::class, 'productinqmail'])->name('productinqmail');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');
//
Route::get('/search', [PageController::class, 'searchproduct'])->name('searchproduct'); 


//single order route
Route::get('/shipping', [UsrOrderController::class, 'allship'])->name('shipping');

//user middle ware .. my account, order, address7        
Route::middleware(['user_mid'])->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    //paypal - http://estore.test/paypal/payment
    Route::get('/paypal/payment/{id}', [PaypalController::class, 'payment'])->name('paypal.payment');
    Route::get('/paypal/success', [PaypalController::class, 'success'])->name('paypal.success');
    Route::get('/paypal/cancel', [PaypalController::class, 'cancel'])->name('paypal.cancel');
    //stripe - http://estore.test/
    Route::get('/stripe/payment/{id}', [StripeController::class, 'payment'])->name('stripe.payment');
    Route::get('/stripe/success', [StripeController::class, 'success'])->name('stripe.success');
    Route::get('/stripe/cancel', [StripeController::class, 'cancel'])->name('stripe.cancel');
    //razorpay - http://estore.test/
    Route::get('/razorpay/payment/{id}', [RazorpayController::class, 'payment'])->name('razorpay.payment');
    Route::get('/razorpay/success', [RazorpayController::class, 'success'])->name('razorpay.success');
    Route::get('/razorpay/cancel', [RazorpayController::class, 'cancel'])->name('razorpay.cancel');
    Route::post('/razorpay/charge', [RazorpayController::class, 'charge'])->name('razorpay.charge');
    
    //
    Route::get('/order', [UsrOrderController::class, 'allorder'])->name('order');
    Route::get('/order/view/{id}', [UsrOrderController::class, 'showorder'])->name('show.order');
    Route::post('/order/cancel/{id}', [UsrOrderController::class, 'cancelorder'])->name('cancel.order');
    //Route::get('/order/{id}', [UsrOrderController::class, 'cancelorder'])->name('cancel.order');
    
    //single shipping route
    Route::get('/address', [UsrOrderController::class, 'alladdress'])->name('address');
    Route::get('/add_address', [UsrOrderController::class, 'add_address'])->name('add_address');
    Route::post('/save_address', [UsrOrderController::class, 'save_address'])->name('save_address');
    Route::get('/adderss/show/{id}', [UsrOrderController::class, 'showadderss'])->name('show.address');
    Route::put('/address/{id}', [UsrOrderController::class, 'editaddress'])->name('edit.address');
    Route::delete('address/{id}', [UsrOrderController::class, 'deleteaddress'])->name('delete.address');
    Route::post('/getaddress', [UsrOrderController::class, 'getaddress'])->name('getaddress');
    //
    Route::get('/change-profile', [UsrOrderController::class, 'editmyprofile'])->name('change-profile');
    Route::post('/saveprofile', [UsrOrderController::class, 'saveprofile'])->name('saveprofile');
    Route::get('/change-password', [UsrOrderController::class, 'changepassword'])->name('change-password');
    Route::post('/savepass', [UsrOrderController::class, 'savepass'])->name('savepass');
});




//admin --------------------------------------------------------------------------------------------
Route::get('/admin', function () { return redirect('/admin/login'); });

Route::name('admin.')->prefix('admin')->group(function () {
    Route::get('/login', [BackendUserDashboardController::class, 'index'])->name('login');
    Route::post('/post-login-admin', [BackendUserDashboardController::class, 'post_login_admin'])->name('post_login_admin');
    Route::get('/logout', [BackendUserDashboardController::class, 'logout'])->name('logout');
});

Route::middleware(['admin_mid'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [BackendUserDashboardController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/profile', [BackendUserDashboardController::class, 'profile'])->name('admin.profile');
    Route::post('/changepassword', [BackendUserDashboardController::class, 'changepassword'])->name('admin.changepassword');
    Route::post('/aeditprofile', [BackendUserDashboardController::class, 'aeditprofile'])->name('admin.aeditprofile');
    //
    Route::resource('brand', BrandController::class)->names('admin.brand');
    Route::post('/brand/chg_sts', [BrandController::class, 'chg_sts'])->name('admin.brand.chg_sts');
    //
    Route::get('/homesetting', [SiteSettingController::class, 'homesetting'])->name('admin.homesetting');
    Route::post('/save_home_setting', [SiteSettingController::class, 'save_home_setting'])->name('admin.save_home_setting');
    Route::post('/save_logo_setting', [SiteSettingController::class, 'save_logo_setting'])->name('admin.save_logo_setting');
    Route::post('/save_infobox', [SiteSettingController::class, 'save_infobox'])->name('admin.save_infobox');
    Route::post('/save_home_setting2', [SiteSettingController::class, 'save_home_setting2'])->name('admin.save_home_setting2');
    Route::post('/save_paypal', [SiteSettingController::class, 'save_paypal'])->name('admin.save_paypal');
    Route::post('/save_stripe', [SiteSettingController::class, 'save_stripe'])->name('admin.save_stripe');
    Route::post('/save_razorpay', [SiteSettingController::class, 'save_razorpay'])->name('admin.save_razorpay');
    Route::post('/save_paycod', [SiteSettingController::class, 'save_paycod'])->name('admin.save_paycod');
    Route::post('/save_mailset', [SiteSettingController::class, 'save_mailset'])->name('admin.save_mailset');
    Route::post('/save_popup', [SiteSettingController::class, 'save_popup'])->name('admin.save_popup');
    Route::post('/sendtestmail', [SiteSettingController::class, 'sendtestmail'])->name('admin.sendtestmail');
    //
    Route::get('/productsetting', [SiteSettingController::class, 'productsetting'])->name('admin.productsetting');
    Route::post('/save_product_setting', [SiteSettingController::class, 'save_product_setting'])->name('admin.save_product_setting');
    //
    Route::resource('slider', SliderController::class)->names('admin.slider');
    Route::post('/slider/chg_sts', [SliderController::class, 'chg_sts'])->name('admin.slider.chg_sts');
    //
    Route::resource('social', SocialController::class)->names('admin.social');
    Route::post('/social/chg_sts', [SocialController::class, 'chg_sts'])->name('admin.social.chg_sts');
    //
    Route::resource('page', PageController::class)->names('admin.page');
    Route::post('/page/chg_sts', [PageController::class, 'chg_sts'])->name('admin.page.chg_sts');
    //
    Route::resource('faq', FaqController::class)->names('admin.faq');
    Route::post('/faq/chg_sts', [FaqController::class, 'chg_sts'])->name('admin.faq.chg_sts');
    //
    Route::get('/product-category/shows', [ProductCateoryController::class, 'shows'])->name('admin.product-category.shows');
    Route::resource('product-category', ProductCateoryController::class)->names('admin.product-category');
    Route::post('/product-category/chg_sts', [ProductCateoryController::class, 'chg_sts'])->name('admin.product-category.chg_sts');
    //
    Route::resource('attribute', AttributeController::class)->names('admin.attribute');
    Route::post('/attribute/chg_sts', [AttributeController::class, 'chg_sts'])->name('admin.attribute.chg_sts');
    //for seprate menu
    Route::get('/variant/{attr_id}', [VariantController::class, 'index'])->name('admin.variant.index');
    Route::get('/variant/create/{attr_id}', [VariantController::class, 'create'])->name('admin.variant.create');
    Route::post('/variant', [VariantController::class, 'store'])->name('admin.variant.store');
    Route::get('/variant/{attr_id}/edit', [VariantController::class, 'edit'])->name('admin.variant.edit');
    Route::put('/variant/{attr_id}', [VariantController::class, 'update'])->name('admin.variant.update');
    Route::delete('variant/{attr_id}', [VariantController::class, 'destroy'])->name('admin.variant.destroy');
    Route::post('/variant/chg_sts', [VariantController::class, 'chg_sts'])->name('admin.variant.chg_sts');
    //
    Route::resource('product', ProductController::class)->names('admin.product');
    Route::post('/product/chg_sts', [ProductController::class, 'chg_sts'])->name('admin.product.chg_sts');
    //
    Route::resource('product-imgs', ProductImgsController::class)->names('admin.product-imgs');
    //
    Route::resource('product-variant', ProductVariantController::class)->names('admin.product-variant');
    Route::post('/product-variant/chg_sts', [ProductVariantController::class, 'chg_sts'])->name('admin.product-variant.chg_sts');
    //
    Route::resource('coupon', CouponController::class)->names('admin.coupon');
    Route::post('/coupon/chg_sts', [CouponController::class, 'chg_sts'])->name('admin.coupon.chg_sts');
    //
    Route::resource('review', ProductReviewController::class)->names('admin.review');
    Route::get('/review/create/{attr_id}', [VariantController::class, 'create'])->name('admin.review.create2');
    Route::post('/review/chg_sts', [ProductReviewController::class, 'chg_sts'])->name('admin.review.chg_sts');
    //
    Route::resource('shipping', ShippingController::class)->names('admin.shipping');
    Route::post('/shipping/chg_sts', [ShippingController::class, 'chg_sts'])->name('admin.shipping.chg_sts');
    Route::post('/getstate', [ShippingController::class, 'getstate'])->name('admin.shipping.getstate');
    //
    Route::get('/order', [OrderViewController::class, 'allorder'])->name('admin.order');
    Route::get('/order/{id}', [OrderViewController::class, 'ordershow'])->name('admin.order.show');
    Route::post('/order/note', [OrderViewController::class, 'savenote'])->name('admin.order.note');
    Route::post('/order/sts', [OrderViewController::class, 'savests'])->name('admin.order.sts'); 
    Route::post('/order/shipping', [OrderViewController::class, 'saveshipping'])->name('admin.order.shipping');
    Route::delete('order/{id}', [OrderViewController::class, 'deleteorder'])->name('admin.order.destroy');
    //
    Route::get('/seo', [SeoController::class, 'index'])->name('admin.seo.index');
    Route::get('/seo/data', [SeoController::class, 'getData'])->name('seo.data');
    Route::get('/seo/create', [SeoController::class, 'create'])->name('admin.seo.create');
    Route::post('/seo/store', [SeoController::class, 'store'])->name('admin.seo.store');
    Route::get('/seo/{id}/edit', [SeoController::class, 'edit'])->name('admin.seo.edit');
    Route::put('/seo/{id}', [SeoController::class, 'update'])->name('admin.seo.update');
    Route::delete('seo/{id}', [SeoController::class, 'destroy'])->name('admin.seo.destroy');
    //
    Route::get('/footer', [MenuController::class, 'footerindex'])->name('admin.footer.index');
    Route::post('/savemenu', [MenuController::class, 'savemenu'])->name('admin.footer.savemenu');
    Route::post('/savemenu1', [MenuController::class, 'savemenu1'])->name('admin.footer.savemenu1');
    Route::post('/savemenu2', [MenuController::class, 'savemenu2'])->name('admin.footer.savemenu2');
    Route::post('/savemenu3', [MenuController::class, 'savemenu3'])->name('admin.footer.savemenu3');
    Route::get('/menu', [MenuController::class, 'menuindex'])->name('admin.menu.index');
    Route::post('/savehmenu', [MenuController::class, 'savehmenu'])->name('admin.menu.savehmenu');

});

