<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Unternehmensanmeldung</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
    crossorigin="anonymous"></script>

  <style>
    html, body {
      height: 100%;
      background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
      font-family: "Inter", "Segoe UI", Arial, sans-serif;
    }

    .login-container {
      min-height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .login-card {
      background: #ffffff;
      padding: 2rem 2.5rem;
      border-radius: 1rem;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
      width: 100%;
      max-width: 380px;
      text-align: center;
    }

    #profile-img {
      height: 100px;
      margin-bottom: 1rem;
    }

    .form-control {
      margin-bottom: 1rem;
      border-radius: 0.5rem;
      border: 1px solid #ced4da;
    }

    .btn-primary {
      background-color: #004080;
      border: none;
      border-radius: 0.5rem;
      font-weight: 500;
    }

    .btn-primary:hover {
      background-color: #003366;
    }

    footer {
      background-color: #ffffff;
      border-top: 1px solid #e5e5e5;
      padding: 1.5rem 0;
      text-align: center;
      color: #555;
      font-size: 0.9rem;
      position: absolute;
      bottom: 0;
      width: 100%;
    }

    footer img {
      height: 50px;
      margin-bottom: 0.5rem;
    }

    footer p {
      margin: 0;
    }
  </style>
</head>

<body>
  <div class="login-container">
    <div class="login-card">
       <h5 class="mb-3">Willkommen</h5>
      {{flash}}
      <form class="form-signin" method="post">
         <input type="text" name="username" id="inputUsername" class="form-control" placeholder="Benutzername" required autofocus>
         <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Passwort" required>
         <button class="btn btn-primary w-100 mt-2" type="submit">Anmelden</button>
      </form>
    </div>
  </div>

  <footer>
    <img src="img/faltleap.png" alt="Logo" />
     <p class="mt-2">Null Abhängigkeiten &amp; null Kompromisse</p>
  </footer>
</body>
</html>

