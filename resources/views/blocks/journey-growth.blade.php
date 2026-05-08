        <!-- Journey and Growth Sections -->
        <section class="journey-growth pt-5 pb-2"
            style="background: linear-gradient(135deg, #d0f5d7 0%, #f5f0f0 60%, #f0d2d2 100%); padding: 80px 0;">
            <div class="container pt-4">
                <!-- Our Journey -->
                <div class="mb-5 pb-5">
                    <h2 class="fw-bold mb-4" style="color: #0d5f2a; position: relative; display: inline-block;">
                        {{ $page->block('journey-growth', 'title', 'Our Journey') }}
                        <span
                            style="position: absolute; bottom: -8px; left: 0; width: 100%; height: 3px; background-color: #1c8008; border-radius: 3px;"></span>
                    </h2>
                    <p class="fs-5 mt-3" style="max-width: 950px; color: #4a5568 !important; line-height: 1.7;">
                        {{ $page->block('journey-growth', 'paragraph', "Novi-Agro began its journey in 2022 with an initial workforce of 15 employees. The company has since recorded steady expansion, increasing its staff strength to 30 in 2023, 55 in 2024, and 70 in 2025. By 2026, the organization's team has reached 100 personnel, reflecting its continuous growth trajectory.") }}
                    </p>

                    <div class="row mt-5 pt-2 text-center g-4 justify-content-center">
                        <div class="col-sm-6 col-lg-4 col-xl d-flex">
                            <div class="card border-0 w-100"
                                style="box-shadow: 0 4px 20px rgba(0,0,0,0.06); border-radius: 14px; background-color: #02311b;">
                                <div
                                    class="card-body py-4 d-flex flex-column justify-content-center align-items-center">
                                    <span class="badge rounded-pill text-white px-3 py-2 mb-3"
                                        style="background-color: #0d5f2a; font-size: 13px;">{{ $page->block('journey-growth', 'card_1_year', '2022') }}</span>
                                    <h2 class="fw-bold mb-1" style="font-size: 42px; color: #b4be20;">{{ $page->block('journey-growth', 'card_1_value', '15') }}</h2>
                                    <p class="text-muted mb-0 fw-medium"
                                        style="font-size: 14px; color: #e1e5eb !important;">{{ $page->block('journey-growth', 'card_label', 'Staff Members') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4 col-xl d-flex">
                            <div class="card border-0 w-100"
                                style="box-shadow: 0 4px 20px rgba(0,0,0,0.06); border-radius: 14px; background-color: #02311b;">
                                <div
                                    class="card-body py-4 d-flex flex-column justify-content-center align-items-center">
                                    <span class="badge rounded-pill text-white px-3 py-2 mb-3"
                                        style="background-color: #0d5f2a; font-size: 13px;">{{ $page->block('journey-growth', 'card_2_year', '2023') }}</span>
                                    <h2 class="fw-bold mb-1" style="font-size: 42px; color: #b4be20;">{{ $page->block('journey-growth', 'card_2_value', '30') }}</h2>
                                    <p class="text-muted mb-0 fw-medium"
                                        style="font-size: 14px; color: #e1e5eb !important;">{{ $page->block('journey-growth', 'card_label', 'Staff Members') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4 col-xl d-flex">
                            <div class="card border-0 w-100"
                                style="box-shadow: 0 4px 20px rgba(0,0,0,0.06); border-radius: 14px; background-color: #02311b;">
                                <div
                                    class="card-body py-4 d-flex flex-column justify-content-center align-items-center">
                                    <span class="badge rounded-pill text-white px-3 py-2 mb-3"
                                        style="background-color: #0d5f2a; font-size: 13px;">{{ $page->block('journey-growth', 'card_3_year', '2024') }}</span>
                                    <h2 class="fw-bold mb-1" style="font-size: 42px; color: #b4be20;">{{ $page->block('journey-growth', 'card_3_value', '55') }}</h2>
                                    <p class="text-muted mb-0 fw-medium"
                                        style="font-size: 14px; color: #e1e5eb !important;">{{ $page->block('journey-growth', 'card_label', 'Staff Members') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4 col-xl d-flex">
                            <div class="card border-0 w-100"
                                style="box-shadow: 0 4px 20px rgba(0,0,0,0.06); border-radius: 14px; background-color: #02311b;">
                                <div
                                    class="card-body py-4 d-flex flex-column justify-content-center align-items-center">
                                    <span class="badge rounded-pill text-white px-3 py-2 mb-3"
                                        style="background-color: #0d5f2a; font-size: 13px;">{{ $page->block('journey-growth', 'card_4_year', '2025') }}</span>
                                    <h2 class="fw-bold mb-1" style="font-size: 42px; color: #b4be20;">{{ $page->block('journey-growth', 'card_4_value', '70') }}</h2>
                                    <p class="text-muted mb-0 fw-medium"
                                        style="font-size: 14px; color: #e1e5eb !important;">{{ $page->block('journey-growth', 'card_label', 'Staff Members') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4 col-xl d-flex">
                            <div class="card border-0 w-100"
                                style="box-shadow: 0 4px 20px rgba(0,0,0,0.06); border-radius: 14px; background-color: #02311b;">
                                <div
                                    class="card-body py-4 d-flex flex-column justify-content-center align-items-center">
                                    <span class="badge rounded-pill text-white px-3 py-2 mb-3"
                                        style="background-color: #0d5f2a; font-size: 13px;">{{ $page->block('journey-growth', 'card_5_year', '2026') }}</span>
                                    <h2 class="fw-bold mb-1" style="font-size: 42px; color: #b4be20;">{{ $page->block('journey-growth', 'card_5_value', '100') }}</h2>
                                    <p class="text-muted mb-0 fw-medium"
                                        style="font-size: 14px; color: #e1e5eb !important;">{{ $page->block('journey-growth', 'card_label', 'Staff Members') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
