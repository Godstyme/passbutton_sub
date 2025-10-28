<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Welcome to Passbutton_Sub</title>
  <style>
    :root{
      --bg1:#0f1724;
      --bg2:#0b3b5a;
      --accent1:#7ce7c1;
      --accent2:#6ea8fe;
      --text: #ffffff;
    }

    html,body{
      height:100%;
      margin:0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, var(--bg1) 0%, var(--bg2) 100%);
      overflow:hidden;
    }

    /* animated gradient overlay */
    .gradient {
      position:fixed;
      inset:0;
      background: radial-gradient(600px 400px at 10% 20%, rgba(110,168,254,0.12), transparent 10%),
                  radial-gradient(500px 300px at 90% 80%, rgba(124,231,193,0.10), transparent 10%);
      pointer-events:none;
      animation: gradientShift 12s ease-in-out infinite;
      z-index:0;
    }
    @keyframes gradientShift{
      0%{ transform: translateY(0) rotate(0deg); opacity:1; }
      50%{ transform: translateY(-20px) rotate(3deg); opacity:0.95; }
      100%{ transform: translateY(0) rotate(0deg); opacity:1; }
    }

    /* container */
    .wrap{
      min-height:100%;
      display:flex;
      align-items:center;
      justify-content:center;
      padding:2rem;
      box-sizing:border-box;
      position:relative;
      z-index:2;
    }

    /* glass card */
    .card{
      width:100%;
      max-width:760px;
      padding:3rem;
      border-radius:18px;
      background: linear-gradient(180deg, rgba(255,255,255,0.03), rgba(255,255,255,0.02));
      box-shadow: 0 10px 30px rgba(2,6,23,0.6);
      backdrop-filter: blur(8px) saturate(120%);
      border: 1px solid rgba(255,255,255,0.04);
      text-align:center;
      color:var(--text);
      position:relative;
    }

    /* floating orbs (decorative) */
    .orb{
      position:absolute;
      border-radius:50%;
      filter: blur(10px);
      opacity:0.6;
      mix-blend-mode: screen;
      pointer-events:none;
      animation: float 6s ease-in-out infinite;
    }
    .orb.orb-1{ width:220px; height:220px; left:-80px; top:-60px; background: linear-gradient(180deg, rgba(110,168,254,0.22), rgba(124,231,193,0.08)); animation-duration:8s; }
    .orb.orb-2{ width:160px; height:160px; right:-60px; bottom:-40px; background: linear-gradient(180deg, rgba(124,231,193,0.20), rgba(110,168,254,0.05)); animation-duration:10s; transform-origin:center; }

    @keyframes float{
      0%{ transform: translateY(0) translateX(0) scale(1); }
      50%{ transform: translateY(-18px) translateX(6px) scale(1.03); }
      100%{ transform: translateY(0) translateX(0) scale(1); }
    }

    /* heading style + animations */
    h1{
      margin:0 0 0.75rem 0;
      font-size:clamp(1.6rem, 4.5vw, 2.6rem);
      display:inline-block;
      padding:0.6rem 1.1rem;
      border-radius:12px;
      background: linear-gradient(180deg, rgba(255,255,255,0.03), transparent);
      animation: entrance 900ms cubic-bezier(.2,.9,.2,1);
      cursor:pointer;
      transition: transform 220ms ease, box-shadow 220ms ease;
      box-shadow: 0 6px 18px rgba(2,6,23,0.45);
    }

    @keyframes entrance{
      0%{ opacity:0; transform: translateY(18px) scale(.98); filter: blur(6px); }
      60%{ opacity:1; transform: translateY(-6px) scale(1.01); filter: blur(0); }
      100%{ transform: translateY(0) scale(1); }
    }

    /* hover micro-interaction */
    h1:hover{
      transform: scale(1.03) translateY(-2px);
      box-shadow: 0 18px 50px rgba(2,6,23,0.6);
    }

    /* subtitle */
    p.lead{
      margin:0;
      margin-top:0.75rem;
      color: rgba(255,255,255,0.85);
      font-size:clamp(0.95rem, 2.8vw, 1.05rem);
    }

    /* responsive adjustments */
    @media (max-width:540px){
      .card{ padding:2rem; border-radius:12px; }
      .orb.orb-1{ display:none; }
    }

    /* canvas for confetti */
    #confetti-canvas{
      position:fixed;
      inset:0;
      pointer-events:none;
      z-index:5;
    }
  </style>
</head>
<body>
  <div class="gradient" aria-hidden="true"></div>

  <div class="wrap">
    <div class="card">
      <div class="orb orb-1"></div>
      <div class="orb orb-2"></div>

      <h1 id="main-heading">Welcome to Passbutton_Sub</h1>
      <p class="lead">A simple, responsive and animated Laravel welcome page.</p>
    </div>
  </div>

  <canvas id="confetti-canvas"></canvas>

  <script>
    // Lightweight confetti animation
    const canvas = document.getElementById('confetti-canvas');
    const ctx = canvas.getContext('2d');
    let W = canvas.width = window.innerWidth;
    let H = canvas.height = window.innerHeight;
    let particles = [];

    window.addEventListener('resize', () => {
      W = canvas.width = window.innerWidth;
      H = canvas.height = window.innerHeight;
    });

    function createBurst(x, y, count = 30) {
      const colors = ['#6ea8fe','#7ce7c1','#ffd166','#ff6b6b','#c795ff'];
      for (let i = 0; i < count; i++) {
        const angle = (Math.PI * 2) * (i / count);
        const speed = 2 + Math.random() * 5;
        particles.push({
          x, y,
          vx: Math.cos(angle) * speed,
          vy: Math.sin(angle) * speed - 2,
          size: 6 + Math.random() * 6,
          color: colors[Math.floor(Math.random()*colors.length)],
          life: 80 + Math.floor(Math.random()*40)
        });
      }
    }

    function animate() {
      ctx.clearRect(0,0,W,H);
      for (let i = particles.length-1; i >= 0; i--) {
        const p = particles[i];
        p.x += p.vx;
        p.y += p.vy;
        p.vy += 0.12;
        p.life--;
        const alpha = Math.max(0, p.life / 100);
        ctx.globalAlpha = alpha;
        ctx.fillStyle = p.color;
        ctx.beginPath();
        ctx.arc(p.x, p.y, p.size * 0.4, 0, Math.PI*2);
        ctx.fill();
        if (p.life <= 0) particles.splice(i,1);
      }
      ctx.globalAlpha = 1;
      requestAnimationFrame(animate);
    }
    animate();

    document.getElementById('main-heading').addEventListener('click', (e) => {
      const rect = e.target.getBoundingClientRect();
      createBurst(rect.left + rect.width/2, rect.top + rect.height/2);
    });
  </script>
</body>
</html>
