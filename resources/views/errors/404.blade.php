@extends('frontend.layout.master')

@section('body_content')

<div id="page-content" class="error-page">        
    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12">	
                <div class="empty-page-content text-center">
                    <h1>404 Page Not Found</h1>
                    <p>The page you requested does not exist.</p>
                    <p><a href="{{ route('home').'/shop' }}" class="btn btn--has-icon-after">Continue shopping <i class="fa fa-caret-right" aria-hidden="true"></i></a></p>
                  </div>
            </div>
        </div>
    </div>
</div>

@endsection