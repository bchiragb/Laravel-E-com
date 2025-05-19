@foreach($products as $product)
    <div class="product 123">
        <h2>{{ $product->title }}</h2>
        <p>${{ $product->price }}</p>
    </div>
@endforeach