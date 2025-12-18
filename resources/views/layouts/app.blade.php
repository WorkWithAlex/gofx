<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>@yield('title', 'GOFX — Gold & Bitcoin Forex')</title>
  <meta name="description" content="GOFX — Premium Forex & Trading education, tools and community led by Tehseen Shaikh." />

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Inter font -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">

  <!-- Tailwind Play CDN -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">

  <style>body{background:var(--bg:#060710);} /* safety fallback */</style>
  @stack('head')
</head>
<body class="antialiased text-slate-100 font-sans">

  <!-- Canvas background -->
  <canvas id="bg" class="fixed inset-0 w-full h-full z-0"></canvas>

  <div id="app" class="relative z-10 min-h-screen">

    <!-- NAV -->
    <nav class="relative z-50 bg-transparent">
      <div class="max-w-6xl mx-auto px-6">
        <div class="flex items-center justify-between py-4">
          <a href="{{ route('home') }}" class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl grid place-items-center font-extrabold text-black"
                 style="background:linear-gradient(135deg,var(--accent1),var(--accent2));">
              GO
            </div>
            <div class="leading-tight">
              <div class="text-white font-bold">GOFX</div>
              <div class="text-xs text-slate-300">Gold & Bitcoin Forex</div>
            </div>
          </a>

          <!-- Desktop menu -->
          <div class="hidden md:flex items-center gap-6">

            <!-- Home -->
            <a href="{{ url('/') }}" class="text-slate-200 hover:text-white">Home</a>
            
            <!-- About / Contact -->
            <a href="{{ url('/about') }}" class="text-slate-200 hover:text-white">About</a>

            <!-- Courses (dropdown) -->
            <div class="relative">
              <button data-dropdown="courses" aria-expanded="false" class="text-slate-200 hover:text-white flex items-center gap-2 focus:outline-none">
                Courses <svg class="w-3 h-3" viewBox="0 0 20 20" fill="none"><path d="M5 7l5 5 5-5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
              </button>

              <div id="dropdown-courses" class="dropdown-menu hidden absolute left-0 mt-3 w-56 bg-black/70 backdrop-blur-md rounded-md border border-white/5 shadow-lg py-2 z-60">
                <a href="{{ url('/courses') }}" class="block px-4 py-2 text-sm text-slate-200 hover:bg-white/3">All Courses</a>
                <a href="{{ url('/courses/forex-mastery') }}" class="block px-4 py-2 text-sm text-slate-200 hover:bg-white/3">Forex Mastery</a>
                <a href="{{ url('/courses/price-action') }}" class="block px-4 py-2 text-sm text-slate-200 hover:bg-white/3">Price Action / Market Structure</a>
                <a href="{{ url('/courses/intraday-swing') }}" class="block px-4 py-2 text-sm text-slate-200 hover:bg-white/3">Intraday & Swing Trading</a>
                <a href="{{ url('/courses/advanced-psychology') }}" class="block px-4 py-2 text-sm text-slate-200 hover:bg-white/3">Advanced Trading Psychology</a>
              </div>
            </div>

            <!-- Learn (dropdown) -->
            <div class="relative">
              <button data-dropdown="learn" aria-expanded="false"
                      class="text-slate-200 hover:text-white flex items-center gap-2 focus:outline-none">
                Learn <svg class="w-3 h-3" viewBox="0 0 20 20" fill="none"><path d="M5 7l5 5 5-5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
              </button>

              <div id="dropdown-learn" class="dropdown-menu hidden absolute left-0 mt-3 w-56 bg-black/70 backdrop-blur-md rounded-md border border-white/5 shadow-lg py-2">
                <a href="{{ route('articles.index') }}" class="block px-4 py-2 text-sm text-slate-200 hover:bg-white/3">Articles</a>
                <a href="{{ route('guides.index') }}" class="block px-4 py-2 text-sm text-slate-200 hover:bg-white/3">Trading Guides</a>
                <a href="{{ route('strategies.index') }}" class="block px-4 py-2 text-sm text-slate-200 hover:bg-white/3">Strategies</a>
                <a href="{{ route('tools.index') }}" class="block px-4 py-2 text-sm text-slate-200 hover:bg-white/3">Tools & Calculators</a>
                <a href="{{ route('library.index') }}" class="block px-4 py-2 text-sm text-slate-200 hover:bg-white/3">Library</a>
              </div>
            </div>

            <!-- Community (dropdown) -->
            <div class="relative">
              <button data-dropdown="community" aria-expanded="false" class="text-slate-200 hover:text-white flex items-center gap-2 focus:outline-none">
                Community <svg class="w-3 h-3" viewBox="0 0 20 20" fill="none"><path d="M5 7l5 5 5-5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
              </button>

              <div id="dropdown-community" class="dropdown-menu hidden absolute left-0 mt-3 w-56 bg-black/70 backdrop-blur-md rounded-md border border-white/5 shadow-lg py-2">
                <a href="{{ route('community.whatsapp') }}" class="block px-4 py-2 text-sm text-slate-200 hover:bg-white/3">WhatsApp Trading Room</a>
                <a href="{{ route('community.prime') }}" class="block px-4 py-2 text-sm text-slate-200 hover:bg-white/3">Prime Membership</a>
                <a href="{{ route('community.success') }}" class="block px-4 py-2 text-sm text-slate-200 hover:bg-white/3">Student Success Wall</a>
              </div>
            </div>

            <a href="{{ url('/contact') }}" class="text-slate-200 hover:text-white">Contact</a>

            <!-- CTA -->
            <a href="{{ url('/enroll') }}" class="ml-2 inline-block px-4 py-2 rounded-md font-semibold"
               style="background:linear-gradient(90deg,var(--accent2),var(--accent1)); color:#000;">
              Enroll Now
            </a>
          </div>

          <!-- Mobile menu trigger -->
          <div class="md:hidden">
            <button id="mobileMenuBtn" class="p-2 rounded-md bg-white/5">☰</button>
          </div>
        </div>
      </div>

      <!-- Mobile menu (collapsible) -->
      <div id="mobileMenu" class="hidden md:hidden bg-black/60">
        <div class="px-6 py-4 flex flex-col gap-3">
          <a href="{{ url('/') }}" class="text-slate-200">Home</a>
          
          <a href="{{ url('/about') }}" class="text-slate-200">About</a>

          <!-- Mobile nested dropdowns use same data-dropdown attribute -->
          <div>
            <button data-dropdown="courses" aria-expanded="false"
                    class="w-full text-left text-slate-200 flex items-center justify-between py-2">Courses
              <span class="text-slate-400">▸</span>
            </button>
            <div id="dropdown-courses-mobile" class="dropdown-menu hidden pl-4">
              <a href="{{ url('/courses/forex-mastery') }}" class="block py-2 text-slate-200">Forex Mastery</a>
              <a href="{{ url('/courses/price-action') }}" class="block py-2 text-slate-200">Price Action / Market Structure</a>
              <a href="{{ url('/courses/intraday-swing') }}" class="block py-2 text-slate-200">Intraday & Swing Trading</a>
              <a href="{{ url('/courses/advanced-psychology') }}" class="block py-2 text-slate-200">Advanced Trading Psychology</a>
              <a href="{{ url('/courses') }}" class="block py-2 text-slate-200">All Courses</a>
            </div>
          </div>

          <div>
            <button data-dropdown="learn" aria-expanded="false"
                    class="w-full text-left text-slate-200 flex items-center justify-between py-2">Learn
              <span class="text-slate-400">▸</span>
            </button>
            <div id="dropdown-learn-mobile" class="dropdown-menu hidden pl-4">
              <a href="{{ route('articles.index') }}" class="block py-2 text-slate-200">Articles</a>
              <a href="{{ route('guides.index') }}" class="block py-2 text-slate-200">Trading Guides</a>
              <a href="{{ route('strategies.index') }}" class="block py-2 text-slate-200">Strategies</a>
              <a href="{{ route('tools.index') }}" class="block py-2 text-slate-200">Tools & Calculators</a>
              <a href="{{ route('library.index') }}" class="block py-2 text-slate-200">Library</a>
            </div>
          </div>

          <div>
            <button data-dropdown="community" aria-expanded="false"
                    class="w-full text-left text-slate-200 flex items-center justify-between py-2">Community
              <span class="text-slate-400">▸</span>
            </button>
            <div id="dropdown-community-mobile" class="dropdown-menu hidden pl-4">
              <a href="{{ route('community.whatsapp') }}" class="block py-2 text-slate-200">WhatsApp Trading Room</a>
              <a href="{{ route('community.prime') }}" class="block py-2 text-slate-200">Prime Membership</a>
              <a href="{{ route('community.success') }}" class="block py-2 text-slate-200">Student Success Wall</a>
            </div>
          </div>

          <a href="{{ url('/contact') }}" class="text-slate-200">Contact</a>

          <a href="{{ url('/enroll') }}" class="mt-2 inline-block px-4 py-2 rounded-md font-semibold"
             style="background:linear-gradient(90deg,var(--accent2),var(--accent1)); color:#000;">
            Enroll Now
          </a>
        </div>
      </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="relative z-20">
      @yield('content')
    </main>

  <!-- FOOTER -->
  <footer class="relative z-20 mt-24">

    <!-- Top structured footer -->
    <div class="border-t border-white/5 bg-[#05060B]/70 backdrop-blur-xl shadow-[0_-20px_40px_rgba(0,0,0,0.2)]">
      <div class="max-w-7xl mx-auto px-6 py-14 grid grid-cols-1 md:grid-cols-4 gap-12">

        <!-- Brand -->
        <div class="space-y-4">
          <a href="{{ url('/') }}" class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl grid place-items-center font-extrabold text-black"
                style="background:linear-gradient(135deg,var(--accent1),var(--accent2));">
              GO
            </div>
            <div>
              <div class="text-white font-bold text-lg">GOFX</div>
              <div class="text-xs text-slate-400">Gold & Bitcoin Forex</div>
            </div>
          </a>

          <p class="text-slate-400 text-sm max-w-xs leading-relaxed">
            Premium trading education, real market insights, and community-driven growth led by 
            <span class="text-slate-300">Tehseen Shaikh</span>.
          </p>

          <div class="pt-2 space-y-1">
            <a href="https://instagram.com/tradewithtahseen" class="text-slate-400 hover:text-white text-sm block">
              @tradewithtahseen
            </a>
            <a href="mailto:tradewithtahseen@gmail.com" class="text-slate-400 hover:text-white text-sm block">
              tradewithtahseen@gmail.com
            </a>
          </div>
        </div>

        <!-- Courses -->
        <div>
          <h4 class="text-white font-semibold mb-4">Courses</h4>
          <ul class="space-y-3 text-slate-400 text-sm">
            <li><a href="{{ url('/courses/forex-mastery') }}" class="hover:text-white transition">Forex Mastery</a></li>
            <li><a href="{{ url('/courses/price-action') }}" class="hover:text-white transition">Price Action / Market Structure</a></li>
            <li><a href="{{ url('/courses/intraday-swing') }}" class="hover:text-white transition">Intraday & Swing Trading</a></li>
            <li><a href="{{ url('/courses/advanced-psychology') }}" class="hover:text-white transition">Advanced Psychology</a></li>
            <li><a href="{{ url('/courses') }}" class="hover:text-white font-medium transition">View All</a></li>
          </ul>
        </div>

        <!-- Learn -->
        <div>
          <h4 class="text-white font-semibold mb-4">Learn</h4>
          <ul class="space-y-3 text-slate-400 text-sm">
            <li><a href="{{ route('articles.index') }}" class="hover:text-white transition">Articles</a></li>
            <li><a href="{{ route('guides.index') }}" class="hover:text-white transition">Trading Guides</a></li>
            <li><a href="{{ route('strategies.index') }}" class="hover:text-white transition">Strategies</a></li>
            <li><a href="{{ route('tools.index') }}" class="hover:text-white transition">Tools & Calculators</a></li>
            <li><a href="{{ route('library.index') }}" class="hover:text-white font-medium transition">Library</a></li>
          </ul>
        </div>

        <!-- Community -->
        <div>
          <h4 class="text-white font-semibold mb-4">Community</h4>
          <ul class="space-y-3 text-slate-400 text-sm">
            <li><a href="{{ route('community.whatsapp') }}" class="hover:text-white transition">WhatsApp Trading Room</a></li>
            <li><a href="{{ route('community.prime') }}" class="hover:text-white transition">Prime Membership</a></li>
            <li><a href="{{ route('community.success') }}" class="hover:text-white transition">Student Success Wall</a></li>
          </ul>
        </div>

      </div>
    </div>

    <!-- Bottom minimal footer -->
    <div class="border-t border-white/10 bg-black/40">
      <div class="max-w-6xl mx-auto px-6 py-6 text-sm text-slate-400 flex flex-col md:flex-row justify-between items-center gap-4">
        <div>© {{ date('Y') }} GOFX</div>
        <div class="flex items-center gap-4">
          <a href="{{url('/terms-and-conditions')}}" class="hover:text-white transition">Terms & Conditions</a> 
          <span class="text-slate-600">|</span>
          <a href="{{url('/privacy-policy')}}" class="hover:text-white transition">Privacy Policy</a> 
          <span class="text-slate-600">|</span>
          <a href="{{url('/refund-and-cancellation-policy')}}" class="hover:text-white transition">Refund Policy</a>
        </div>
      </div>
    </div>

  </footer>



  </div>

  <!-- small helper: mobile toggle + dropdown click handlers -->
  <script>
    (function(){
      // Mobile menu toggle
      const mobileBtn = document.getElementById('mobileMenuBtn');
      const mobileMenu = document.getElementById('mobileMenu');
      if(mobileBtn){
        mobileBtn.addEventListener('click', ()=> {
          mobileMenu.classList.toggle('hidden');
        });
      }

      // Dropdown logic (click / tap to open)
      const dropdownButtons = Array.from(document.querySelectorAll('[data-dropdown]'));
      const openDropdowns = new Map();

      function closeAllDropdowns(){
        document.querySelectorAll('.dropdown-menu').forEach(el => {
          el.classList.add('hidden');
        });
        dropdownButtons.forEach(btn => btn.setAttribute('aria-expanded','false'));
        openDropdowns.clear();
      }

      // Toggle handler
      dropdownButtons.forEach(btn => {
        const key = btn.getAttribute('data-dropdown');
        btn.addEventListener('click', (e) => {
          e.preventDefault();
          e.stopPropagation();
          // target menu: desktop and mobile IDs differ
          const desktopMenu = document.getElementById('dropdown-' + key);
          const mobileMenu = document.getElementById('dropdown-' + key + '-mobile');
          const isOpen = btn.getAttribute('aria-expanded') === 'true';

          // close others first
          closeAllDropdowns();

          if(!isOpen){
            btn.setAttribute('aria-expanded','true');
            if(desktopMenu && window.innerWidth >= 768) desktopMenu.classList.remove('hidden');
            if(mobileMenu && window.innerWidth < 768) mobileMenu.classList.remove('hidden');
            openDropdowns.set(key, true);
          } else {
            btn.setAttribute('aria-expanded','false');
            if(desktopMenu) desktopMenu.classList.add('hidden');
            if(mobileMenu) mobileMenu.classList.add('hidden');
            openDropdowns.delete(key);
          }
        });
      });

      // Close on outside click
      document.addEventListener('click', function(e){
        // If click happened inside a dropdown button/menu, ignore (handled above)
        // Otherwise close all
        closeAllDropdowns();
      });

      // Prevent closing when clicking inside a dropdown menu
      document.querySelectorAll('.dropdown-menu').forEach(menu => {
        menu.addEventListener('click', (e) => { e.stopPropagation(); });
      });

      // Close on ESC
      document.addEventListener('keydown', (e) => {
        if(e.key === 'Escape' || e.key === 'Esc'){
          closeAllDropdowns();
        }
      });

      // Keep dropdown state correct on resize (close all)
      window.addEventListener('resize', () => { closeAllDropdowns(); });
    })();
  </script>

  <!-- main visual/particles script -->
  <script src="{{ asset('js/main.js') }}"></script>
  @stack('scripts')
</body>
</html>
