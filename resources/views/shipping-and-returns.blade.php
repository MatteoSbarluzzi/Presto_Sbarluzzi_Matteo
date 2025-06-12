<x-layout>
    {{-- Sezione introduttiva --}}
    <div class="container my-5">
        <h1 class="text-center mb-4 text-dark-blue fw-bold">
            {{ __('ui.shipping_and_returns') }}
        </h1>
        <p class="lead text-center text-dark-blue">
            {{ __('ui.shipping_and_returns_description') }}
        </p>
    </div>

    {{-- Sezione dettagliata --}}
    <section class="container mb-5">
        <div class="row gy-4">
            <div class="col-md-6">
                <div class="p-4 bg-beige text-dark-blue rounded shadow border h-100">
                    <h2 class="h5 fw-bold">{{ __('ui.shipping_title') }}</h2>
                    <p>{{ __('ui.shipping_paragraph') }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-4 bg-beige text-dark-blue rounded shadow border h-100">
                    <h2 class="h5 fw-bold">{{ __('ui.returns_title') }}</h2>
                    <p>{{ __('ui.returns_paragraph') }}</p>
                </div>
            </div>
        </div>
    </section>


    {{-- Sezione FAQ --}}
    <section class="container mb-5 bg-white p-4 rounded shadow border">
        <h2 class="h4 mb-4 text-dark-blue fw-bold">{{ __('ui.faq_title') }}</h2>

        <div class="accordion" id="faqAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header" id="faqHeadingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapseOne" aria-expanded="true" aria-controls="faqCollapseOne">
                        {{ __('ui.faq_q1') }}
                    </button>
                </h2>
                <div id="faqCollapseOne" class="accordion-collapse collapse show" aria-labelledby="faqHeadingOne" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        {{ __('ui.faq_a1') }}
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="faqHeadingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapseTwo" aria-expanded="false" aria-controls="faqCollapseTwo">
                        {{ __('ui.faq_q2') }}
                    </button>
                </h2>
                <div id="faqCollapseTwo" class="accordion-collapse collapse" aria-labelledby="faqHeadingTwo" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        {{ __('ui.faq_a2') }}
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="faqHeadingThree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapseThree" aria-expanded="false" aria-controls="faqCollapseThree">
                        {{ __('ui.faq_q3') }}
                    </button>
                </h2>
                <div id="faqCollapseThree" class="accordion-collapse collapse" aria-labelledby="faqHeadingThree" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        {{ __('ui.faq_a3') }}
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="faqHeadingFour">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapseFour" aria-expanded="false" aria-controls="faqCollapseFour">
                        {{ __('ui.faq_q4') }}
                    </button>
                </h2>
                <div id="faqCollapseFour" class="accordion-collapse collapse" aria-labelledby="faqHeadingFour" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        {{ __('ui.faq_a4') }}
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layout>
