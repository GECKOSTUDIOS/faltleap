<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
  integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous" />

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Reverse Enginator</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
    integrity="sha384-tViUnnbYAV00FLIhhi3v/dWt3Jxw4gZQcNoSCxCIFNJVCx7/D55/wXsrNIRANwdD" crossorigin="anonymous">
</head>

<body>
  <main>
    <header>
      <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
          <a class="navbar-brand" href="/"><span style="color:#ff6600">Reverse</span> Enginator</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <!-- <li class="nav-item"> -->
              <!-- 	<a class="nav-link active" aria-current="page" href="/">Dashboard</a> -->
              <!-- </li> -->
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="/manage">Manage</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="/users">Users</a>
              </li>
            </ul>
            <a class="btn btn-outline-success" href="/logout" type="logout">Logout</a>
          </div>
        </div>
      </nav>
    </header>
    <div class="container mt-5">
      {{flash}}
      {{content}}
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
      crossorigin="anonymous"></script>
  </main>
</body>

</html>

<script>
  class HelloWorld extends HTMLElement {
    constructor() {
      super();
      this.attachShadow({
        mode: "open"
      });
      this.shadowRoot.innerHTML = `<p>Hello, Web Components!</p>`;
    }
  }
  customElements.define("hello-world", HelloWorld);

  const socket = new WebSocket("ws://10.0.80.247:8080");
  socket.addEventListener("open", () => {
    console.log("WebSocket connection opened");
  });
  socket.addEventListener("message", (message) => {
    console.log("Message from Server");
  });
</script>
