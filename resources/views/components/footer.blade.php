<footer class="bg-black text-white text-center">
  <div class="container p-4">

    {{-- Sezione descrizione --}}
    <section class="mb-4">
      {{-- Descrizione generale del sito, testuale e tradotta --}}
      <p>{{ __('ui.description_footer') }}</p>
    </section>

    {{-- Sezione: colonne principali --}}
    <div class="container">
      <div class="row">

        {{-- Colonna contatti --}}
        <div class="col-md-3 mb-3">
          {{-- Informazioni di contatto aziendali --}}
          <h5>{{ __('ui.contact') }}</h5>
          <p>{{ __('ui.location') }}: Firenze (FI)</p>
          <p>{{ __('ui.address') }}: Via della Liberazione, 11</p>
          <p>{{ __('ui.email') }}: info@presto.it</p>
          <p>{{ __('ui.phone') }}: +39 123 456 789</p>
          <p>{{ __('ui.fax') }}: 06 1234 5678</p>
          <p>{{ __('ui.vat_number') }}: 12345678901</p>
        </div>

        {{-- Colonna fatturazione --}}
        <div class="col-md-3 mb-3 text-center">
          {{-- Collegamenti a pagine informative relative ad ordini e spedizioni --}}
          <h5>{{ __('ui.billing_and_orders') }}</h5>
          <p>
            <a href="{{ route('shipping') }}" class="text-white text-decoration-none">
              {{ __('ui.shipping_and_returns') }}
            </a>
          </p>
          <p>
            <a href="{{ route('reviews') }}" class="text-white text-decoration-none">
              {{ __('ui.customer_reviews') }}
            </a>
          </p>

          {{-- Metodi di pagamento supportati --}}
          <p class="text-white">{{ __('ui.payment_methods') }}</p>
          <div class="mt-4 text-center">
            {{-- Icone visive dei metodi di pagamento, con titolo localizzato --}}
            <img src="https://img.icons8.com/color/48/000000/visa.png" alt="Visa" class="me-2" title="{{ __('ui.visa') }}" />
            <img src="https://img.icons8.com/color/48/000000/mastercard.png" alt="MasterCard" class="me-2" title="{{ __('ui.mastercard') }}" />
            <img src="https://img.icons8.com/color/48/000000/amex.png" alt="American Express" class="me-2" title="{{ __('ui.amex') }}" />
            <img src="https://img.icons8.com/color/48/000000/paypal.png" alt="PayPal" class="me-2" title="{{ __('ui.paypal') }}" />
            <i class="bi bi-bank fs-3 me-2 text-white" title="{{ __('ui.bank_transfer') }}"></i>
            <img src="https://img.icons8.com/color/48/000000/google-pay.png" alt="Google Pay" class="me-2" title="{{ __('ui.google_pay') }}" />
            <img src="https://img.icons8.com/ios-filled/50/ffffff/apple-pay.png" alt="Apple Pay" class="me-2" title="{{ __('ui.apple_pay') }}" />
            <img src="https://img.icons8.com/color/48/000000/bitcoin--v1.png" alt="Bitcoin" class="me-2" title="Bitcoin" />
          </div>
        </div>

        {{-- Colonna social --}}
        <div class="col-md-3 mb-3 d-flex flex-column align-items-center">
          {{-- Collegamenti ai social con icone bootstrap e apertura in nuova finestra --}}
          <h5>{{ __('ui.follow_us') }}</h5>
          <ul class="list-unstyled">
            <li class="mb-2">
              <a href="https://facebook.com" target="_blank" class="text-white text-decoration-none d-flex align-items-center">
                <i class="bi bi-facebook fs-5 me-2"></i> Facebook
              </a>
            </li>
            <li class="mb-2">
              <a href="https://x.com" target="_blank" class="text-white text-decoration-none d-flex align-items-center">
                <i class="bi bi-twitter-x fs-5 me-2"></i> X
              </a>
            </li>
            <li class="mb-2">
              <a href="https://instagram.com" target="_blank" class="text-white text-decoration-none d-flex align-items-center">
                <i class="bi bi-instagram fs-5 me-2"></i> Instagram
              </a>
            </li>
            <li class="mb-2">
              <a href="https://tiktok.com" target="_blank" class="text-white text-decoration-none d-flex align-items-center">
                <i class="bi bi-tiktok fs-5 me-2"></i> TikTok
              </a>
            </li>
            <li class="mb-2">
              <a href="https://linkedin.com" target="_blank" class="text-white text-decoration-none d-flex align-items-center">
                <i class="bi bi-linkedin fs-5 me-2"></i> LinkedIn
              </a>
            </li>
          </ul>
        </div>

        {{-- Colonna newsletter + revisore --}}
        <div class="col-md-3 mb-3">
          {{-- Form per iscrizione newsletter --}}
          <h5>{{ __('ui.newsletter_text') }}</h5>
          <form method="POST" action="{{ route('newsletter.subscribe') }}">
            @csrf
            <div class="text-center">
              <div class="mx-auto mb-3" style="max-width: 400px;">
                <input type="email" name="email" class="form-control" placeholder="{{ __('ui.email_placeholder') }}" required />
              </div>
              <button type="submit" class="btn-footer-custom">
                {{ __('ui.subscribe') }}
              </button>
            </div>
          </form>

          <hr class="my-4 bg-white">

          {{-- Sezione per diventare revisore --}}
          <div class="text-center">
            <h5>{{ __('ui.work_with_us') }}</h5>
            <p class="small">{{ __('ui.click_button_below') }}</p>
            <a href="{{ route('become.revisor') }}" class="btn-footer-custom">
              {{ __('ui.want_to_become_revisor') }}
            </a>
          </div>
        </div>

      </div>
    </div>
  </div>

  {{-- Copyright --}}
  <div class="text-center p-3 bg-dark text-white">
    {{-- Riferimento al sito con link alla homepage --}}
    © 2025 Copyright:
    <a class="text-white fw-bold text-decoration-none" href="{{ route('homepage') }}">Presto.it</a>
  </div>
</footer>
