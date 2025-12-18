<section class="py-12">
  <div class="max-w-5xl mx-auto px-6">
    @if(session('success'))
      <div class="mb-6 rounded-lg bg-green-800/80 text-white p-4">
        {{ session('success') }}
      </div>
    @endif

    @if($errors->any())
      <div class="mb-6 rounded-lg bg-red-900/80 text-white p-4">
        <ul class="list-disc pl-5">
          @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="grid md:grid-cols-2 gap-8">
      {{-- Contact Form Section --}}
      <div class="bg-slate-800/40 rounded-2xl p-8">
        <h2 class="text-2xl font-bold mb-4 text-white">Get in touch</h2>
        <p class="text-slate-300 mb-6">Have a question about courses, signals, or anything else? Drop us a message and we'll respond within 24–48 hours.</p>

        <form method="POST" action="{{ route('contact.submit') }}" novalidate>
          @csrf

          {{-- Honeypot (bots) --}}
          <input type="text" name="website" style="display:none" tabindex="-1" autocomplete="off">

          <label class="block mb-3">
            <span class="text-slate-200">Name</span>
            <input name="name" value="{{ old('name') }}" required
                   class="mt-1 block w-full rounded-md border-0 px-3 py-2 bg-slate-900 text-white">
          </label>

          <label class="block mb-3">
            <span class="text-slate-200">Email</span>
            <input name="email" value="{{ old('email') }}" type="email" required
                   class="mt-1 block w-full rounded-md border-0 px-3 py-2 bg-slate-900 text-white">
          </label>

          <label class="block mb-3">
            <span class="text-slate-200">Phone (optional)</span>
            <input name="phone" value="{{ old('phone') }}" type="tel" placeholder="+9198xxxxxxxx"
                   class="mt-1 block w-full rounded-md border-0 px-3 py-2 bg-slate-900 text-white">
          </label>

          <label class="block mb-4">
            <span class="text-slate-200">Message</span>
            <textarea name="message" rows="5" required
                      class="mt-1 block w-full rounded-md border-0 px-3 py-2 bg-slate-900 text-white">{{ old('message') }}</textarea>
          </label>

          <div class="flex gap-3 items-center">
            <button type="submit" class="inline-flex items-center justify-center rounded-lg px-4 py-2 font-bold bg-gradient-to-r from-[#f7931a] to-[#ffd166] text-black">
              Send Message
            </button>

            <small class="text-slate-400">We may respond at support@gofx.in or +91 9226884183. </small>
          </div>
        </form>
      </div>

      {{-- Contact details --}}
      <div class="bg-slate-800/30 rounded-2xl p-8">
        <h3 class="text-xl font-semibold text-white mb-3">Contact Details</h3>

        <p class="text-slate-300 mb-2"><strong>Email:</strong> <a href="mailto:support@gofx.in" class="underline">support@gofx.in</a></p>
        <p class="text-slate-300 mb-2"><strong>Phone:</strong> <a href="tel:+919226884183" class="underline">+91 9226884183</a></p>
        <p class="text-slate-300 mb-4"><strong>Address:</strong> Kailash Nagar, Yeshwant College Road, Nanded, Maharashtra – 431601.</p>

        <h4 class="text-white font-medium mb-2">Follow / Social</h4>
        <p class="text-slate-300 mb-4">@gofx_in</p>

        <h4 class="text-white font-medium mb-2">Frequently Asked</h4>
        <ul class="text-slate-300 list-disc pl-5 space-y-2">
          <li>Course access & timings</li>
          <li>Signal subscription queries</li>
          <li>Billing & refunds — see our Terms & Cancellation policy</li>
        </ul>
      </div>
    </div>
  </div>
</section>