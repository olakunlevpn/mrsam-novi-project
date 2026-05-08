            <div class="container contact-one__container">
                <div class="contact-one__wrapper">
                    @if (session('contact_status') === 'sent')
                        <div class="alert alert-success" role="status">
                            {{ $page->block('contact-form', 'success_message', 'Thanks — your message has been received. We will get back to you shortly.') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form class="form-one contact-one__form contact-form-validated" action="{{ $page->block('contact-form', 'action_url', route('contact.submit')) }}" method="POST">
                        @csrf
                        <input type="text" name="_honey" style="display:none" tabindex="-1" autocomplete="off">
                        <input type="hidden" name="subject" value="{{ $page->block('contact-form', 'subject', 'New Contact Form Submission - Novi Agro') }}">
                        <div class="form-one__group">
                            <div class="form-one__control ">
                                <input type="text" name="name" placeholder="Your Name" aria-label="Your Name" value="{{ old('name') }}" required>
                            </div><!-- /.form-one__control  -->
                            <div class="form-one__control">
                                <input type="email" name="email" placeholder="Email Address" aria-label="Email Address" value="{{ old('email') }}" required>
                            </div><!-- /.form-one__control -->
                            <div class="form-one__control">
                                <input type="tel" name="phone" placeholder="Phone" aria-label="Phone Number" value="{{ old('phone') }}">
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
                                <textarea name="message" placeholder="Your Message here" aria-label="Your Message" required>{{ old('message') }}</textarea><!-- /# -->
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
