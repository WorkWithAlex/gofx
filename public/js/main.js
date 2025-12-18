/* public/js/main.js
   Particles background and simple countdown (30 days from now)
   Adapted from uploaded index.html design. */
(function(){
  const canvas = document.getElementById('bg');
  if(!canvas) return;
  const ctx = canvas.getContext('2d');
  let DPR = Math.max(1, window.devicePixelRatio || 1);

  function resize(){
    DPR = Math.max(1, window.devicePixelRatio || 1);
    canvas.width = window.innerWidth * DPR;
    canvas.height = window.innerHeight * DPR;
    ctx.setTransform(DPR, 0, 0, DPR, 0, 0);
  }
  window.addEventListener('resize', resize);
  resize();

  // Settings tuned to look good on most screens
  const settings = {
    particleCount: Math.floor(Math.min(120, Math.max(40, (window.innerWidth * window.innerHeight) / 10000))),
    maxRadius: 3.6,
    linkDistance: 140,
    speed: 0.35
  };

  class Particle {
    constructor(){
      this.reset();
    }
    reset(){
      this.x = Math.random() * window.innerWidth;
      this.y = Math.random() * window.innerHeight;
      const s = settings.speed * (0.6 + Math.random() * 1.4);
      const a = Math.random() * Math.PI * 2;
      this.vx = Math.cos(a) * s;
      this.vy = Math.sin(a) * s;
      this.r = Math.random() * settings.maxRadius + 0.8;
      this.opacity = 0.3 + Math.random() * 0.7;
      this.isCoin = (Math.random() < 0.06);
      this.coinChar = this.isCoin ? (Math.random() < 0.5 ? '₿' : 'Au') : null;
    }
  }

  let particles = [];
  for(let i=0;i<settings.particleCount;i++) particles.push(new Particle());

  function drawLinks(){
    for(let i=0;i<particles.length;i++){
      for(let j=i+1;j<particles.length;j++){
        const p = particles[i], q = particles[j];
        const dx = p.x - q.x, dy = p.y - q.y;
        const d2 = dx*dx + dy*dy;
        if(d2 < settings.linkDistance * settings.linkDistance){
          const d = Math.sqrt(d2);
          ctx.beginPath();
          ctx.moveTo(p.x, p.y);
          ctx.lineTo(q.x, q.y);
          ctx.strokeStyle = `rgba(150,170,255,${0.12 * (1 - d / settings.linkDistance)})`;
          ctx.lineWidth = 0.6;
          ctx.stroke();
        }
      }
    }
  }

  function drawGradient(){
    const g = ctx.createLinearGradient(0, 0, window.innerWidth, window.innerHeight);
    g.addColorStop(0, 'rgba(6,7,16,0.6)');
    g.addColorStop(1, 'rgba(6,7,16,0.6)');
    ctx.fillStyle = g;
    ctx.fillRect(0, 0, window.innerWidth, window.innerHeight);
  }

  function frame(){
    ctx.clearRect(0, 0, canvas.width / DPR, canvas.height / DPR);
    for(let p of particles){
      p.x += p.vx;
      p.y += p.vy;
      if(p.x < 0) p.x = window.innerWidth;
      if(p.x > window.innerWidth) p.x = 0;
      if(p.y < 0) p.y = window.innerHeight;
      if(p.y > window.innerHeight) p.y = 0;
    }
    ctx.save();
    ctx.globalCompositeOperation = 'lighter';
    drawLinks();
    for(let p of particles){
      if(p.isCoin){
        ctx.font = (12 + p.r * 4) + 'px Inter, system-ui';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillStyle = p.coinChar === '₿' ? '#f7931a' : '#ffd166';
        ctx.fillText(p.coinChar, p.x, p.y);
      } else {
        ctx.beginPath();
        ctx.arc(p.x, p.y, p.r, 0, Math.PI*2);
        ctx.fillStyle = `rgba(255,255,255,${p.opacity * 0.8})`;
        ctx.fill();
      }
    }
    ctx.restore();
    drawGradient();
    requestAnimationFrame(frame);
  }
  requestAnimationFrame(frame);

  // Countdown logic (if you include an element with id="countdown" in pages)
  try {
    const countdownEl = document.getElementById('countdown');
    if(countdownEl){
      const targetDate = new Date();
      targetDate.setDate(targetDate.getDate() + 30); // 30 days from now
      function updateCountdown(){
        const now = new Date();
        const diff = targetDate - now;
        if(diff <= 0){ countdownEl.textContent = '00 : 00 : 00 : 00'; return; }
        const d = Math.floor(diff / 86400000);
        const h = Math.floor(diff / 3600000) % 24;
        const m = Math.floor(diff / 60000) % 60;
        const s = Math.floor(diff / 1000) % 60;
        countdownEl.textContent = `${String(d).padStart(2,'0')} : ${String(h).padStart(2,'0')} : ${String(m).padStart(2,'0')} : ${String(s).padStart(2,'0')}`;
      }
      setInterval(updateCountdown, 1000);
      updateCountdown();
    }
  } catch (e) {
    console.warn('Countdown setup failed', e);
  }

})();

/* ===== CENTER GLASS CARD: neon pulse toggle + micro-parallax ===== */
(function(){
  const glass = document.querySelector('.glass-neon');
  const photoWrap = document.querySelector('.hero-card-photo-wrap');

  // small device safety
  if(!glass || window.innerWidth < 640) return;

  // start pulse after a slight delay
  // NOTE: changed to add class "pulse" (matches CSS .glass-neon.pulse::before)
  setTimeout(()=> glass.classList.add('pulse'), 500);

  // micro-parallax based on mouse movement (desktop only)
  if(photoWrap && window.matchMedia('(hover: hover) and (pointer: fine)').matches){
    photoWrap.addEventListener('mousemove', (e) => {
      const r = photoWrap.getBoundingClientRect();
      const cx = r.left + r.width / 2;
      const cy = r.top + r.height / 2;
      const dx = (e.clientX - cx) / r.width;
      const dy = (e.clientY - cy) / r.height;
      const tx = dx * 8; // horizontal tilt
      const ty = dy * 6; // vertical tilt
      photoWrap.style.transform = `translate3d(${tx}px, ${ty}px, 0)`;
      photoWrap.style.transition = 'transform 120ms linear';
    });
    photoWrap.addEventListener('mouseleave', () => {
      photoWrap.style.transform = '';
      photoWrap.style.transition = 'transform 420ms cubic-bezier(.2,.9,.2,1)';
    });
  }
})();
