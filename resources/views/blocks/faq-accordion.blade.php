@php
    $faqs = \App\Models\Faq::published()
        ->orderBy('order_column')
        ->get();
@endphp
        <section class="faq-page">
            <div class="container">
                <h3 class="faq-page__sec-title">{{ $page->block('faq-accordion', 'eyebrow', 'Need help?') }}</h3>
                <p class="faq-page__sec-text">
                    {{ $page->block('faq-accordion', 'subtitle', 'We are here to guide you through our products and services') }}
                </p>
                <h3 class="faq-page__title">{{ $page->block('faq-accordion', 'title', 'Everything you need to know about our products and services') }}</h3>
                <div class="faq-page__accordion grdeen-accrodion" data-grp-name="grdeen-accrodion">
                    @forelse ($faqs as $i => $faq)
                        <div class="accrodion {{ $i === 0 ? 'active' : '' }}">
                            <div class="accrodion-title">
                                <h4>{{ $faq->question }}
                                    <span class="accrodion-title__icon"></span><!-- /.accrodion-title__icon -->
                                </h4>
                            </div><!-- /.accordian-title -->
                            <div class="accrodion-content">
                                <div class="inner">
                                    {!! $faq->answer !!}
                                </div><!-- /.accordian-content -->
                            </div>
                        </div><!-- /.accordian-item -->
                    @empty
                        <div class="accrodion active">
                            <div class="accrodion-title">
                                <h4>What's wrong with my Livestock farm?
                                    <span class="accrodion-title__icon"></span><!-- /.accrodion-title__icon -->
                                </h4>
                            </div><!-- /.accordian-title -->
                            <div class="accrodion-content">
                                <div class="inner">
                                    <p>We offer a comprehensive range of services tailored to meet the diverse needs of our
                                        clients. From livestock management to
                                        sustainable agricultural practices, we provide end-to-end solutions that ensure
                                        optimal results. Our services include livestock health monitoring, feed
                                        additives, feed supplements, and farm automation.</p>
                                </div><!-- /.accordian-content -->
                            </div>
                        </div><!-- /.accordian-item -->
                        <div class="accrodion">
                            <div class="accrodion-title">
                                <h4>What is your return policy?
                                    <span class="accrodion-title__icon"></span><!-- /.accrodion-title__icon -->
                                </h4>
                            </div><!-- /.accordian-title -->
                            <div class="accrodion-content">
                                <div class="inner">
                                    <p>
                                        We accept returns within 30 days of purchase for unused and undamaged products. The
                                        item must be in its original packaging with all tags and accessories intact. Once
                                        we receive and inspect the returned item, we will process the refund to the original
                                        method of payment within 5-7 business days. Please note that return shipping costs
                                        are the responsibility of the customer unless the return is due to our error or a
                                        defective product.
                                    </p>
                                </div><!-- /.accordian-content -->
                            </div>
                        </div><!-- /.accordian-item -->
                        <div class="accrodion">
                            <div class="accrodion-title">
                                <h4>How do I know when to feed my livestocks?
                                    <span class="accrodion-title__icon"></span><!-- /.accrodion-title__icon -->
                                </h4>
                            </div><!-- /.accordian-title -->
                            <div class="accrodion-content">
                                <div class="inner">
                                    <p>Livestock feeding schedules depend on the type of animal, their age, weight, and
                                        nutritional needs. Generally, adult cattle are fed twice a day, while poultry
                                        requires constant access to feed. Younger animals need more frequent feedings to
                                        support growth. Monitoring body condition and consulting with a veterinarian or
                                        livestock nutritionist ensures optimal feeding practices.</p>
                                </div><!-- /.accordian-content -->
                            </div>
                        </div><!-- /.accordian-item -->
                        <div class="accrodion">
                            <div class="accrodion-title">
                                <h4>How best can I utilize Novi-Agro's products and services?
                                    <span class="accrodion-title__icon"></span><!-- /.accrodion-title__icon -->
                                </h4>
                            </div><!-- /.accordian-title -->
                            <div class="accrodion-content">
                                <div class="inner">
                                    <p>To get the most out of our products and services, we recommend starting with a
                                        consultation to assess your specific needs. Our team can help you choose the right
                                        feed formulations, livestock breeds, or consultancy services for your farm. Regular
                                        communication and feedback ensure that you receive ongoing support and can optimize
                                        your agricultural practices for better results.</p>
                                </div><!-- /.accordian-content -->
                            </div>
                        </div><!-- /.accordian-item -->
                        <div class="accrodion">
                            <div class="accrodion-title">
                                <h4>How do I know the right quantity for my livestock?
                                    <span class="accrodion-title__icon"></span><!-- /.accrodion-title__icon -->
                                </h4>
                            </div><!-- /.accordian-title -->
                            <div class="accrodion-content">
                                <div class="inner">
                                    <p>
                                        Livestock feeding schedules depend on the type of animal, their age, weight, and
                                        nutritional needs. Generally, adult cattle are fed twice a day, while poultry
                                        requires constant access to feed. Younger animals need more frequent feedings to
                                        support growth. Monitoring body condition and consulting with a veterinarian or
                                        livestock nutritionist ensures optimal feeding practices.
                                    </p>
                                </div><!-- /.accordian-content -->
                            </div>
                        </div><!-- /.accordian-item -->
                    @endforelse
                </div>
            </div>
        </section>
