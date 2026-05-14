@php
    $animal = $animal ?? null;
    $animalImage = $animal?->hero_image_url
        ?? '/assets/images/backgrounds/cows-green-field-sunny-day.jpg';
    $animalName = $animal?->name ?: 'Cattle';
@endphp
        <section class="page-header">
            <div class="page-header__bg"
                style="background-image: url({{ $page->blockAsset('page-header-cattle', 'background_image', $animalImage) }}); background-position: center; background-size: cover;"></div>
            <div class="page-header__shape wow fadeInUp" data-wow-delay="200ms"></div>
            <div class="page-header__overlay"></div>
            <!-- /.page-header__bg -->
            <div class="container">
                <h2 class="page-header__title">{{ $page->block('page-header-cattle', 'title', $animalName) }}</h2>
                <!-- /.thm-breadcrumb list-unstyled -->
            </div><!-- /.container -->
        </section><!-- /.page-header -->
