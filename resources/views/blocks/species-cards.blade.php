        <!-- Species Section -->
        <section class="species-section">
            <div class="container">
                <!-- Section Heading -->
                <div class="mb-5">
                    <h2 class="fw-bold species-section__heading">
                        SPECIES
                    </h2>
                    <p class="mt-3" style="color: #6c757d; font-size: 16px; max-width: 600px;">
                        We provide premium feed additives and nutritional solutions tailored for each animal
                        category.
                    </p>
                </div>

                <!-- Species Cards -->
                <div class="row g-3">

                    <!-- Poultry -->
                    <div class="col-sm-6 col-lg-4">
                        <a href="{{ route('animals.poultry') }}" class="text-decoration-none">
                            <div class="species-card">
                                <img loading="lazy" src="/assets/images/backgrounds/hens-factory-chicken-cages.jpg" alt="Poultry">
                                <div class="species-card__overlay"></div>
                                <div class="species-card__label species-card__label--dark">
                                    <span class="species-card__label-title">Poultry</span>
                                    <span><svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            width="16" height="16" fill="currentColor" class="bi bi-arrow-right"
                                            viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8" />
                                        </svg></span>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Cattle -->
                    <div class="col-sm-6 col-lg-4">
                        <a href="{{ route('animals.cattle') }}" class="text-decoration-none">
                            <div class="species-card">
                                <img loading="lazy" src="/assets/images/backgrounds/photorealistic-view-cow-grazing-outdoors.jpg"
                                    alt="Cattle" style="object-position: center top;">
                                <div class="species-card__overlay"></div>
                                <div class="species-card__label species-card__label--green">
                                    <span class="species-card__label-title">Cattle</span>
                                    <span><svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            width="16" height="16" fill="currentColor" class="bi bi-arrow-right"
                                            viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8" />
                                        </svg></span>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Pigs -->
                    <div class="col-sm-6 col-lg-4">
                        <a href="{{ route('animals.pigs') }}" class="text-decoration-none">
                            <div class="species-card">
                                <img loading="lazy" src="/assets/images/backgrounds/selective-closeup-shot-pink-pigs-barn.jpg"
                                    alt="Pigs">
                                <div class="species-card__overlay"></div>
                                <div class="species-card__label species-card__label--dark">
                                    <span class="species-card__label-title">Pigs</span>
                                    <span><svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            width="16" height="16" fill="currentColor" class="bi bi-arrow-right"
                                            viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8" />
                                        </svg></span>
                                </div>
                            </div>
                        </a>
                    </div>

                </div>
            </div>
        </section>
        <!-- Species Section End -->
