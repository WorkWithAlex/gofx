<!-- <section class="py-20 bg-white/5 border-y border-white/10"> -->
<section class="py-20 border-white/10 ">
    <div class="max-w-6xl mx-auto px-6">
        <h2 class="text-3xl font-bold mb-10">Popular Courses</h2>

        <div class="grid md:grid-cols-3 gap-10">

            <div class="glass rounded-xl p-6 space-y-4 glass-neon-blue">
                <h3 class="text-xl font-semibold">Forex Mastery</h3>
                <p class="pb-4 text-slate-300">A structured introduction to the forex market with real setups.</p>
                <a href="{{ route('courses.show', 'forex-mastery') }}" class="text-[var(--accent1)]">View Course →</a>
            </div>

            <div class="glass rounded-xl p-6 space-y-4 glass-neon-blue">
                <h3 class="text-xl font-semibold">Price Action & SMC</h3>
                <p class="pb-4 text-slate-300">Learn institutional trading concepts the right way.</p>
                <a href="{{ route('courses.show', 'price-action') }}" class="text-[var(--accent1)]">View Course →</a>
            </div>

            <div class="glass rounded-xl p-6 space-y-4 glass-neon-blue">
                <h3 class="text-xl font-semibold">Intraday & Swing</h3>
                <p class="pb-4 text-slate-300">Precise execution strategies for volatile markets.</p>
                <a href="{{ route('courses.show', 'intraday-swing') }}" class="text-[var(--accent1)]">View Course →</a>
            </div>

        </div>
    </div>
</section>
