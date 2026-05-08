            <div class="container contact-one__container">
                <div class="contact-one__wrapper">
                    <form class="form-one contact-one__form contact-form-validated" action="{{ $page->block('contact-form', 'action_url', 'https://formsubmit.co/info@novi-agro.com') }}" method="POST">
                        <input type="text" name="_honey" style="display:none" tabindex="-1" autocomplete="off">
                        <input type="hidden" name="_subject" value="{{ $page->block('contact-form', 'subject', 'New Contact Form Submission - Novi Agro') }}">
                        <div class="form-one__group">
                            <div class="form-one__control ">
                                <input type="text" name="name" placeholder="Your Name" aria-label="Your Name" required>
                            </div><!-- /.form-one__control  -->
                            <div class="form-one__control">
                                <input type="email" name="email" placeholder="Email Address" aria-label="Email Address" required>
                            </div><!-- /.form-one__control -->
                            <div class="form-one__control">
                                <input type="tel" name="tel" placeholder="Phone" aria-label="Phone Number">
                            </div><!-- /.form-one__control -->
                            <div class="form-one__control">
                                <div class="form-one__control__select">
                                    <label class="sr-only" for="language-select">Choose Services</label>
                                    <!-- /#language-select.sr-only -->
                                    <select class="selectpicker" id="language-select" name="service">
                                        <option value="">Choose Services</option>
                                        <option value="Consultation">Consultation</option>
                                        <option value="Training">Training</option>
                                    </select>
                                </div><!-- /.main-menu__language -->
                            </div><!-- /.form-one__control -->
                            <div class="form-one__control form-one__control--full">
                                <textarea name="message" placeholder="Your Message here" aria-label="Your Message"></textarea><!-- /# -->
                            </div><!-- /.form-one__control -->
                            <div class="form-one__control form-one__control--full text-center">
                                <button type="submit" class="grdeen-btn"><span>{{ $page->block('contact-form', 'submit_label', 'Send a message') }}</span></button>
                            </div><!-- /.form-one__control -->
                        </div><!-- /.form-one__group -->
                    </form>
                    <div class="result"></div><!-- /.result -->
                </div>
            </div>
        </section><!-- /.contact-one -->
