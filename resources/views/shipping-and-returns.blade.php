<x-layout>
    {{-- Sezione introduttiva --}}
    <div class="container-fluid py-5 mt-5 bg-sky-blue">
        <h1 class="text-center mb-4 pt-5 text-beige fw-bold">
            {{ __('ui.shipping_and_returns') }}
        </h1>
        <p class="lead text-center text-beige">
            {{ __('ui.shipping_and_returns_description') }}
        </p>
    </div>

    {{-- Sezione dettagliata --}}
<section class="container-fluid py-5 bg-sky-blue">
    <div class="row gy-4">
        {{-- Colonna SINISTRA: Spedizione + Reso --}}
        <div class="col-md-6 d-flex flex-column justify-content-between">
            {{-- Blocco Spedizione --}}
            <div class="p-4 bg-orange text-black rounded shadow border mb-4">
                <h2 class="h5 fw-bold">{{ __('ui.shipping_title') }}</h2>
                <p>{{ __('ui.shipping_paragraph') }}</p>
            </div>

            {{-- Blocco Reso --}}
            <div class="p-4 bg-light-blue text-dark-blue rounded shadow border">
                <h2 class="h5 fw-bold">{{ __('ui.returns_title') }}</h2>
                <p>{{ __('ui.returns_paragraph') }}</p>
            </div>
        </div>

        {{-- Colonna DESTRA: Immagine --}}
        <div class="col-md-6 d-flex align-items-center justify-content-center">
            <img src="{{ asset('storage/images/shippingreturns.png') }}" alt="Shipping and Returns" class="img-fluid rounded shadow">
        </div>
    </div>
</section>



    {{-- Sezione FAQ --}}

{{-- Sezione FAQ --}}
<section class="container-fluid bg-beige py-5">
    <div class="container">
        <h2 class="text-dark-blue fw-bold mb-4">{{ __('ui.faq_title') }}</h2>
        <div class="accordion accordion-flush" id="faqAccordion">

            {{-- FAQ 1 --}}
            <div class="accordion-item border rounded mb-3">
                <h2 class="accordion-header" id="faqHeadingOne">
                    <button class="accordion-button bg-light text-dark-blue fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapseOne" aria-expanded="true" aria-controls="faqCollapseOne">
                        {{ __('ui.faq_q1') }}
                    </button>
                </h2>
                <div id="faqCollapseOne" class="accordion-collapse collapse show" aria-labelledby="faqHeadingOne" data-bs-parent="#faqAccordion">
                    <div class="accordion-body bg-white text-dark-blue">
                        {{ __('ui.faq_a1') }}
                    </div>
                </div>
            </div>

            {{-- FAQ 2 --}}
            <div class="accordion-item border rounded mb-3">
                <h2 class="accordion-header" id="faqHeadingTwo">
                    <button class="accordion-button collapsed bg-light text-dark-blue fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapseTwo" aria-expanded="false" aria-controls="faqCollapseTwo">
                        {{ __('ui.faq_q2') }}
                    </button>
                </h2>
                <div id="faqCollapseTwo" class="accordion-collapse collapse" aria-labelledby="faqHeadingTwo" data-bs-parent="#faqAccordion">
                    <div class="accordion-body bg-white text-dark-blue">
                        {{ __('ui.faq_a2') }}
                    </div>
                </div>
            </div>

            {{-- FAQ 3 --}}
            <div class="accordion-item border rounded mb-3">
                <h2 class="accordion-header" id="faqHeadingThree">
                    <button class="accordion-button collapsed bg-light text-dark-blue fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapseThree" aria-expanded="false" aria-controls="faqCollapseThree">
                        {{ __('ui.faq_q3') }}
                    </button>
                </h2>
                <div id="faqCollapseThree" class="accordion-collapse collapse" aria-labelledby="faqHeadingThree" data-bs-parent="#faqAccordion">
                    <div class="accordion-body bg-white text-dark-blue">
                        {{ __('ui.faq_a3') }}
                    </div>
                </div>
            </div>

            {{-- FAQ 4 --}}
            <div class="accordion-item border rounded mb-3">
                <h2 class="accordion-header" id="faqHeadingFour">
                    <button class="accordion-button collapsed bg-light text-dark-blue fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapseFour" aria-expanded="false" aria-controls="faqCollapseFour">
                        {{ __('ui.faq_q4') }}
                    </button>
                </h2>
                <div id="faqCollapseFour" class="accordion-collapse collapse" aria-labelledby="faqHeadingFour" data-bs-parent="#faqAccordion">
                    <div class="accordion-body bg-white text-dark-blue">
                        {{ __('ui.faq_a4') }}
                    </div>
                </div>
            </div>

            {{-- FAQ 5 --}}
            <div class="accordion-item border rounded mb-3">
                <h2 class="accordion-header" id="faqHeadingFive">
                    <button class="accordion-button collapsed bg-light text-dark-blue fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapseFive" aria-expanded="false" aria-controls="faqCollapseFive">
                        {{ __('ui.faq_q5') }}
                    </button>
                </h2>
                <div id="faqCollapseFive" class="accordion-collapse collapse" aria-labelledby="faqHeadingFive" data-bs-parent="#faqAccordion">
                    <div class="accordion-body bg-white text-dark-blue">
                        {{ __('ui.faq_a5') }}
                    </div>
                </div>
            </div>

            {{-- FAQ 6 --}}
            <div class="accordion-item border rounded mb-3">
                <h2 class="accordion-header" id="faqHeadingSix">
                    <button class="accordion-button collapsed bg-light text-dark-blue fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapseSix" aria-expanded="false" aria-controls="faqCollapseSix">
                        {{ __('ui.faq_q6') }}
                    </button>
                </h2>
                <div id="faqCollapseSix" class="accordion-collapse collapse" aria-labelledby="faqHeadingSix" data-bs-parent="#faqAccordion">
                    <div class="accordion-body bg-white text-dark-blue">
                        {{ __('ui.faq_a6') }}
                    </div>
                </div>
            </div>

            {{-- FAQ 7 --}}
            <div class="accordion-item border rounded mb-3">
                <h2 class="accordion-header" id="faqHeadingSeven">
                    <button class="accordion-button collapsed bg-light text-dark-blue fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapseSeven" aria-expanded="false" aria-controls="faqCollapseSeven">
                        {{ __('ui.faq_q7') }}
                    </button>
                </h2>
                <div id="faqCollapseSeven" class="accordion-collapse collapse" aria-labelledby="faqHeadingSeven" data-bs-parent="#faqAccordion">
                    <div class="accordion-body bg-white text-dark-blue">
                        {{ __('ui.faq_a7') }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

</x-layout>
