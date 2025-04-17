<div class="product-card">
    @if ($product->image)
        <img src='{{ asset("{$product->image}") }}' class="product-image" alt="{{ $product->name }}">
    @endif
    <div class="product-info">
        <h2 class="product-title">{{ $product->name }}</h2>
        <p class="product-description">{{ Str::limit($product->description, 100) }}</p>
        <div class="price-container">
            <span class="price-usd">${{ number_format($product->price, 2) }}</span>
            <span class="price-eur">€{{ number_format($product->price * $exchangeRate, 2) }}</span>
        </div>
        <a href="{{ route('products.show', $product) }}" class="btn btn-primary">View Details</a>
    </div>
</div>