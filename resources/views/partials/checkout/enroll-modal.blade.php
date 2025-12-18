@php
    $passed = $course ?? ($meta['slug'] ?? 'forex-mastery');
    $slugMap = [
      'forex-mastery' => 'forex-mastery',
      'price-action-market-structure' => 'price-action',
      'intraday-swing-trading' => 'intraday-swing',
      // 'advanced-trading-psychology' => 'advanced-psychology',
      'smart-money-concepts' => 'smart-money-concepts',
    ];
    $courseSlug = $slugMap[$passed] ?? $passed;
    $modalId = 'enrollModal_' . $courseSlug;
    $openBtnAttr = 'data-open-enroll="'.$courseSlug.'"';
@endphp

<!-- Enroll modal partial (reusable) -->
<!-- Trigger buttons on the page should have attribute: data-open-enroll="{{ $courseSlug }}" -->
<!-- Example: <button data-open-enroll="{{ $courseSlug }}">Pay Now</button> -->
<div id="{{ $modalId }}" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 px-4">
  <div class="bg-[#070812] rounded-lg p-6 w-full max-w-md">
    <div class="flex justify-between items-center">
      <h3 class="text-lg font-semibold">Complete your enrollment</h3>
      <button type="button" class="text-slate-400 enroll-close-btn" data-close-enroll="{{ $courseSlug }}">✕</button>
    </div>

    <form id="enrollForm_{{ $courseSlug }}" class="mt-4 space-y-3">
      @csrf
      <input type="hidden" name="course" value="{{ $courseSlug }}">

      <div>
        <label class="text-sm text-slate-300">Full name</label>
        <input name="name" required class="w-full px-3 py-2 rounded bg-black/30" />
      </div>

      <div>
        <label class="text-sm text-slate-300">Email</label>
        <input name="email" type="email" required class="w-full px-3 py-2 rounded bg-black/30" />
      </div>

      <div>
        <label class="text-sm text-slate-300">Phone</label>
        <input name="phone" required class="w-full px-3 py-2 rounded bg-black/30" />
      </div>
      
      <input type="hidden" name="currency" value="INR">

      <div class="flex gap-3 mt-4">
        <button type="submit" class="flex-1 px-4 py-2 rounded" style="background:linear-gradient(90deg,var(--accent2),var(--accent1));color:#000">
          Proceed to Pay
        </button>
        <button type="button" class="flex-1 px-4 py-2 rounded border border-white/10 enroll-cancel-btn" data-close-enroll="{{ $courseSlug }}">Cancel</button>
      </div>
    </form>
  </div>
</div>

<script>
  (function(){
    const formId = 'enrollForm_{{ $courseSlug }}';
    const form = document.getElementById(formId);

    if (!form) {
      console.error('Enroll form not found:', formId);
      return;
    }

    form.addEventListener('submit', function (e) {
      e.preventDefault();

      const payload = {
        name: form.querySelector('[name="name"]').value,
        email: form.querySelector('[name="email"]').value,
        phone: form.querySelector('[name="phone"]').value,
        course: form.querySelector('[name="course"]').value,
      };

      if (typeof startRazorpayCheckout !== 'function') {
        console.error('startRazorpayCheckout is not defined');
        alert('Payment system not ready. Please refresh.');
        return;
      }

      startRazorpayCheckout(payload);
    });
    
    // safe DOM ready
    function ready(fn){
      if (document.readyState !== 'loading'){
        fn();
      } else {
        document.addEventListener('DOMContentLoaded', fn);
      }
    }

    ready(function(){
      const slug = @json($courseSlug);
      const modalId = @json($modalId);
      const modal = document.getElementById(modalId);
      if (!modal) return;

      // Open buttons: select any element with matching data-open-enroll attribute
      const openButtons = Array.from(document.querySelectorAll('[data-open-enroll="{{ $courseSlug }}"]'));
      openButtons.forEach(btn => {
        btn.addEventListener('click', function(e){
          e.preventDefault();
          modal.classList.remove('hidden');
          modal.classList.add('flex');
        });
      });

      // Close buttons inside modal (close icons / cancel)
      Array.from(document.querySelectorAll('[data-close-enroll="{{ $courseSlug }}"]')).forEach(btn => {
        btn.addEventListener('click', function(e){
          e.preventDefault();
          modal.classList.add('hidden');
          modal.classList.remove('flex');
        });
      });

      // Close when clicking outside modal content area
      modal.addEventListener('click', function(e){
        if (e.target === modal){
          modal.classList.add('hidden');
          modal.classList.remove('flex');
        }
      });

      // Optional: client-side form validation feedback can be added here
    });
    
  })();
</script>
