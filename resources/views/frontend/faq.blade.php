@extends('frontend.layout.master')

@section('body_content')

@php
    function getcatnm($no){
        $cat_arr = array(
            "0" => "No selected",
            "1" => "General",
            "2" => "Product & Order",
            "3" => "Payment & Billing",
            "4" => "Shipping & Delivery",
            "5" => "Returns, Replacement & Refunds",
            "6" => "Technical Support"
        );
        return $cat_arr[$no];
    }
@endphp

<div id="page-content">
    <div class="page section-header text-center">
        <div class="page-title">
            <div class="wrapper"><h1 class="page-width">FAQ</h1></div>
          </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 main-col">
                <div id="accordionExample">
                    @php
                        $previousCategory = null;  
                        foreach ($faqs as $faq) {
                            if ($faq->category != $previousCategory) {
                                echo '<h2 class="title h2">' . getcatnm($faq->category) . '</h2>';
                                $previousCategory = $faq->category;  
                            }
                            echo '
                                 <div class="faq-body">
                                    <h4 class="panel-title" data-toggle="collapse" data-target="#collapse'.$faq->id.'" aria-expanded="false" aria-controls="collapse'.$faq->id.'"> '.$faq->question.'</h4>
                                    <div id="collapse'.$faq->id.'" class="collapse panel-content" data-parent="#accordionExample">'.strip_tags($faq->answer).'</div>
                                </div>
                            ';
                        }
                    @endphp
                </div>
            </div>
        </div>
    </div>
</div>

@endsection