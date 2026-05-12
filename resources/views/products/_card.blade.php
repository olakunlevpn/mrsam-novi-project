@php
    /** @var \App\Models\Product $product */
    $cardImage = $product->hero_image
        ? \Illuminate\Support\Facades\Storage::disk('public')->url($product->hero_image)
        : '/assets/images/backgrounds/photorealistic-view-cow-grazing-outdoors.jpg';
    $cardAnimal = $product->animal?->name;
    $cardCategory = $product->productCategory?->name;
@endphp
<div class="col-sm-6 col-md-4 mb-4 d-flex align-items-stretch">
    <a href="{{ route('products.show', $product) }}" class="product__item related-product-item text-decoration-none"
        style="width: 100%; cursor: pointer;">
        <div class="product__item__img" style="aspect-ratio: 4/3; overflow: hidden; background: #fff;">
            <img loading="lazy" src="{{ $cardImage }}" alt="{{ $product->name }}"
                style="width: 100%; height: 100%; object-fit: cover; display: block;">
            @if ($cardAnimal)
                <span class="product__item__animal-badge">{{ \Illuminate\Support\Str::upper($cardAnimal) }}</span>
            @endif
            @if ($cardCategory)
                <span class="product__item__category-tag">{{ $cardCategory }}</span>
            @endif
        </div>
        <div class="product__item__content-wrap">
            <div class="product__item__content">
                <h4 class="product__item__title">
                    <span>{{ $product->name }}</span>
                </h4>
            </div>
            <span class="product__item__cta" aria-hidden="true">
                {{ __('products.detail.view_details') }} <i class="fas fa-arrow-right"></i>
            </span>
        </div>
    </a>
</div>
