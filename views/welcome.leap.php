<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Falt Leap Framework</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

  <style>
    :root {
      --brand: #ff6600;
      --bg-dark: #0d1117;
      --bg-card: #161b22;
      --text-primary: #e6edf3;
      --text-secondary: #8b949e;
      --border-color: #30363d;
    }

    html, body {
      height: 100%;
      margin: 0;
      background: var(--bg-dark);
      color: var(--text-primary);
      font-family: "Segoe UI", system-ui, -apple-system, sans-serif;
    }

    /* Animations */
    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(30px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    @keyframes float {
      0%, 100% { transform: translateY(0); }
      50%      { transform: translateY(-12px); }
    }

    .fade-in {
      opacity: 0;
      animation: fadeInUp 0.8s ease forwards;
    }

    .fade-in-delay-1 { animation-delay: 0.15s; }
    .fade-in-delay-2 { animation-delay: 0.3s; }
    .fade-in-delay-3 { animation-delay: 0.45s; }
    .fade-in-delay-4 { animation-delay: 0.6s; }

    /* Hero */
    .hero {
      min-height: 80vh;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      padding: 3rem 1rem;
    }

    .hero-logo {
      height: 120px;
      animation: float 4s ease-in-out infinite;
      filter: drop-shadow(0 0 24px rgba(255, 102, 0, 0.3));
    }

    .hero h1 {
      font-size: 3rem;
      font-weight: 700;
      margin-top: 1.5rem;
    }

    .hero h1 span {
      color: var(--brand);
    }

    .hero .tagline {
      font-size: 1.25rem;
      color: var(--text-secondary);
      max-width: 600px;
      margin: 1rem auto 0;
      font-style: italic;
      min-height: 3.5em;
      position: relative;
      overflow: hidden;
    }

    .tagline-word {
      display: inline-block;
      white-space: nowrap;
    }

    .tagline-char {
      display: inline-block;
      transition: none;
    }

    .tagline-char.falling {
      position: relative;
      animation: charFall 0.5s ease-in forwards;
    }

    @keyframes charFall {
      0%   { transform: translateY(0); opacity: 1; }
      100% { transform: translateY(60px); opacity: 0; }
    }

    .tagline-cursor {
      display: inline-block;
      width: 2px;
      height: 1.2em;
      background: var(--brand);
      margin-left: 2px;
      vertical-align: text-bottom;
      animation: cursorBlink 0.6s step-end infinite;
    }

    @keyframes cursorBlink {
      0%, 100% { opacity: 1; }
      50%      { opacity: 0; }
    }

    /* Stats row */
    .stats-row {
      display: flex;
      justify-content: center;
      gap: 2.5rem;
      margin: 2.5rem 0;
      flex-wrap: wrap;
    }

    .stat-item {
      text-align: center;
    }

    .stat-value {
      font-size: 1.75rem;
      font-weight: 700;
      color: var(--brand);
    }

    .stat-label {
      font-size: 0.85rem;
      color: var(--text-secondary);
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }

    /* CTA buttons */
    .btn-brand {
      background: var(--brand);
      color: #fff;
      border: none;
      padding: 0.65rem 1.75rem;
      border-radius: 0.5rem;
      font-weight: 600;
      text-decoration: none;
      transition: background 0.2s, transform 0.2s;
    }

    .btn-brand:hover {
      background: #e65c00;
      color: #fff;
      transform: translateY(-1px);
    }

    .btn-outline-brand {
      color: var(--brand);
      border: 2px solid var(--brand);
      background: transparent;
      padding: 0.6rem 1.75rem;
      border-radius: 0.5rem;
      font-weight: 600;
      text-decoration: none;
      transition: background 0.2s, color 0.2s, transform 0.2s;
    }

    .btn-outline-brand:hover {
      background: var(--brand);
      color: #fff;
      transform: translateY(-1px);
    }

    /* Feature cards */
    .features-section {
      padding: 4rem 1rem;
    }

    .feature-card {
      background: var(--bg-card);
      border: 1px solid var(--border-color);
      border-radius: 0.75rem;
      padding: 2rem;
      height: 100%;
      transition: border-color 0.3s, transform 0.3s;
    }

    .feature-card:hover {
      border-color: var(--brand);
      transform: translateY(-4px);
    }

    .feature-icon {
      font-size: 2rem;
      color: var(--brand);
      margin-bottom: 1rem;
    }

    .feature-card h3 {
      font-size: 1.25rem;
      font-weight: 600;
      margin-bottom: 0.75rem;
    }

    .feature-card p {
      color: var(--text-secondary);
      margin: 0;
      font-size: 0.95rem;
      line-height: 1.6;
    }

    /* Code showcase */
    .code-section {
      padding: 4rem 1rem;
    }

    .code-section h2 {
      text-align: center;
      font-weight: 700;
      margin-bottom: 0.5rem;
    }

    .code-section h2 span {
      color: var(--brand);
    }

    .code-subtitle {
      text-align: center;
      color: var(--text-secondary);
      margin-bottom: 2rem;
    }

    .code-block {
      background: var(--bg-card);
      border: 1px solid var(--border-color);
      border-radius: 0.75rem;
      padding: 1.5rem 2rem;
      overflow-x: auto;
      font-family: "Fira Code", "Cascadia Code", "JetBrains Mono", "Consolas", monospace;
      font-size: 0.9rem;
      line-height: 1.7;
    }

    .code-block .comment  { color: #8b949e; }
    .code-block .keyword  { color: #ff7b72; }
    .code-block .string   { color: #a5d6ff; }
    .code-block .variable { color: #ffa657; }
    .code-block .function { color: #d2a8ff; }
    .code-block .class    { color: #7ee787; }

    /* Footer */
    .welcome-footer {
      border-top: 1px solid var(--border-color);
      padding: 2.5rem 1rem;
      text-align: center;
      color: var(--text-secondary);
      font-size: 0.9rem;
    }

    @media (max-width: 576px) {
      .hero h1 { font-size: 2rem; }
      .hero .tagline { font-size: 1rem; }
      .stats-row { gap: 1.5rem; }
      .stat-value { font-size: 1.4rem; }
    }
  </style>
</head>

<body>
  <!-- Hero Section -->
  <section class="hero">
    <div>
      <img src="/img/faltleap.png" alt="FaltLeap Logo" class="hero-logo fade-in">

      <h1 class="fade-in fade-in-delay-1">
        <span>Falt Leap</span> Framework
      </h1>

      <p class="tagline fade-in fade-in-delay-2" id="tagline"><span class="tagline-cursor"></span></p>
      <script>
      (function() {
        const taglines = <?php echo json_encode($this->rawData->taglines); ?>;
        const el = document.getElementById('tagline');
        let current = -1;
        let timeout = null;

        function pick() {
          let idx;
          do { idx = Math.floor(Math.random() * taglines.length); } while (idx === current && taglines.length > 1);
          current = idx;
          return taglines[idx];
        }

        function rainDown(callback) {
          var chars = el.querySelectorAll('.tagline-char');
          if (chars.length === 0) { callback(); return; }

          chars.forEach(function(c) {
            var delay = Math.random() * 300;
            setTimeout(function() {
              c.classList.add('falling');
            }, delay);
          });

          setTimeout(function() {
            callback();
          }, 800);
        }

        function typeIn(text, callback) {
          el.innerHTML = '';
          var cursor = document.createElement('span');
          cursor.className = 'tagline-cursor';
          el.appendChild(cursor);

          var i = 0;
          var currentWord = null;
          var speed = Math.max(15, Math.min(35, 1400 / text.length));

          function next() {
            if (i < text.length) {
              if (text[i] === ' ') {
                currentWord = null;
                el.insertBefore(document.createTextNode(' '), cursor);
              } else {
                if (!currentWord) {
                  currentWord = document.createElement('span');
                  currentWord.className = 'tagline-word';
                  el.insertBefore(currentWord, cursor);
                }
                var s = document.createElement('span');
                s.className = 'tagline-char';
                s.textContent = text[i];
                currentWord.appendChild(s);
              }
              i++;
              timeout = setTimeout(next, speed);
            } else {
              callback();
            }
          }
          next();
        }

        function cycle() {
          rainDown(function() {
            typeIn(pick(), function() {
              timeout = setTimeout(cycle, 3000);
            });
          });
        }

        typeIn(pick(), function() {
          timeout = setTimeout(cycle, 3000);
        });
      })();
      </script>

      <div class="stats-row fade-in fade-in-delay-3">
        <div class="stat-item">
          <div class="stat-value">20</div>
          <div class="stat-label">Files</div>
        </div>
        <div class="stat-item">
          <div class="stat-value">~3,200</div>
          <div class="stat-label">Lines</div>
        </div>
        <div class="stat-item">
          <div class="stat-value">0</div>
          <div class="stat-label">Dependencies</div>
        </div>
      </div>

      <div class="d-flex justify-content-center gap-3 flex-wrap fade-in fade-in-delay-4">
        <a href="/dashboard" class="btn-brand">
          <i class="bi bi-speedometer2 me-1"></i> Go to Dashboard
        </a>
        <a href="https://github.com/pfriesch/fern" class="btn-outline-brand" target="_blank" rel="noopener">
          <i class="bi bi-github me-1"></i> View on GitHub
        </a>
      </div>
    </div>
  </section>

  <!-- Feature Cards -->
  <section class="features-section">
    <div class="container">
      <div class="row g-4">
        <div class="col-md-4 fade-in fade-in-delay-1">
          <div class="feature-card">
            <div class="feature-icon"><i class="bi bi-box-seam"></i></div>
            <h3>Zero Dependencies</h3>
            <p>No Composer. No vendor folder. No supply-chain anxiety. Every line of code is yours to read, understand, and trust.</p>
          </div>
        </div>
        <div class="col-md-4 fade-in fade-in-delay-2">
          <div class="feature-card">
            <div class="feature-icon"><i class="bi bi-database"></i></div>
            <h3>PostgreSQL-First</h3>
            <p>Built exclusively for PostgreSQL. Schema introspection, Active Record ORM, and auto-generated models straight from your database.</p>
          </div>
        </div>
        <div class="col-md-4 fade-in fade-in-delay-3">
          <div class="feature-card">
            <div class="feature-icon"><i class="bi bi-code-slash"></i></div>
            <h3>Modern PHP 8+</h3>
            <p>Strict types, named arguments, match expressions &mdash; clean, modern PHP without legacy baggage or abstraction soup.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Code Showcase -->
  <section class="code-section">
    <div class="container" style="max-width: 760px;">
      <h2>See how <span>simple</span> it is</h2>
      <p class="code-subtitle">Routes map to controllers. Controllers talk to models. That's it.</p>

      <div class="code-block">
<pre style="margin:0; color: var(--text-primary);"><span class="comment">// conf/router.config.php</span>
<span class="variable">$routes</span> = [
    <span class="string">"/users"</span>          =&gt; [<span class="string">"UsersController@index"</span>, <span class="string">"auth"</span>],
    <span class="string">"/users/edit/{id}"</span> =&gt; [
        <span class="string">"GET"</span>  =&gt; [<span class="string">"UsersController@edit"</span>,   <span class="string">"auth"</span>],
        <span class="string">"POST"</span> =&gt; [<span class="string">"UsersController@update"</span>, <span class="string">"auth"</span>],
    ],
];

<span class="comment">// app/UsersController.php</span>
<span class="keyword">class</span> <span class="class">UsersController</span> <span class="keyword">extends</span> <span class="class">LeapController</span>
{
    <span class="keyword">public function</span> <span class="function">edit</span>(<span class="variable">$id</span>)
    {
        <span class="variable">$user</span> = <span class="class">Users</span>::<span class="function">Query</span>()-&gt;<span class="function">where</span>(<span class="string">"id = :id"</span>, [<span class="string">":id"</span> =&gt; <span class="variable">$id</span>])-&gt;<span class="function">first</span>();
        <span class="variable">$this</span>-&gt;view-&gt;data = <span class="variable">$user</span>;
        <span class="variable">$this</span>-&gt;view-&gt;<span class="function">render</span>(<span class="string">'users/edit'</span>);
    }
}</pre>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="welcome-footer">
    <p>Built with conviction. Powered by PostgreSQL. Zero dependencies, zero regrets.</p>
  </footer>
</body>
</html>
