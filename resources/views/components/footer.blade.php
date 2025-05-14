<!-- Footer -->
<footer class="bg-body-tertiary text-center">
  <!-- Grid container -->
  <div class="container p-4">

    <!-- Sezione: Diventa Revisore (centrata) -->
    <div class="row justify-content-center mb-4">
      <div class="col-md-6 text-center">
        <h5>{{ __('ui.want_to_become_revisor') }}</h5>
        <p>{{ __('ui.click_button_below') }}</p>
        <a href="{{ route('become.revisor') }}" class="btn btn-success">{{ __('ui.become_revisor') }}</a>
      </div>
    </div>
    <!-- Fine Sezione: Diventa Revisore -->

    <!-- Section: Social media -->
    <section class="mb-4">
      <a data-mdb-ripple-init class="btn btn-outline btn-floating m-1" href="#!" role="button">
        <i class="fab fa-facebook-f"></i>
      </a>
      <a data-mdb-ripple-init class="btn btn-outline btn-floating m-1" href="#!" role="button">
        <i class="fab fa-twitter"></i>
      </a>
      <a data-mdb-ripple-init class="btn btn-outline btn-floating m-1" href="#!" role="button">
        <i class="fab fa-google"></i>
      </a>
      <a data-mdb-ripple-init class="btn btn-outline btn-floating m-1" href="#!" role="button">
        <i class="fab fa-instagram"></i>
      </a>
      <a data-mdb-ripple-init class="btn btn-outline btn-floating m-1" href="#!" role="button">
        <i class="fab fa-linkedin-in"></i>
      </a>
      <a data-mdb-ripple-init class="btn btn-outline btn-floating m-1" href="#!" role="button">
        <i class="fab fa-github"></i>
      </a>
    </section>

    <!-- Section: Form -->
    <section class="">
      <form action="">
        <div class="row d-flex justify-content-center">
          <div class="col-auto">
            <p class="pt-2">
              <strong>{{ __('ui.newsletter_text') }}</strong>
            </p>
          </div>

          <div class="col-md-5 col-12">
            <div data-mdb-input-init class="form-outline mb-4">
              <input type="email" id="form5Example24" class="form-control" />
              <label class="form-label" for="form5Example24">{{ __('ui.email_address') }}</label>
            </div>
          </div>

          <div class="col-auto">
            <button data-mdb-ripple-init type="submit" class="btn btn-outline mb-4">
              {{ __('ui.subscribe') }}
            </button>
          </div>
        </div>
      </form>
    </section>

    <!-- Section: Text -->
    <section class="mb-4">
      <p>
        {{ __('ui.lorem_footer') }}
      </p>
    </section>

    <!-- Section: Links -->
    <section class="">
      <div class="row">
        @for ($i = 0; $i < 4; $i++)
          <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
            <h5 class="text-uppercase">Links</h5>
            <ul class="list-unstyled mb-0">
              <li><a class="text-body" href="#!">Link 1</a></li>
              <li><a class="text-body" href="#!">Link 2</a></li>
              <li><a class="text-body" href="#!">Link 3</a></li>
              <li><a class="text-body" href="#!">Link 4</a></li>
            </ul>
          </div>
        @endfor
      </div>
    </section>
  </div>

  <!-- Copyright -->
  <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
    © 2025 Copyright:
    <a class="text-reset fw-bold" href="https://mdbootstrap.com/">Presto.it</a>
  </div>
</footer>
