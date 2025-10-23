<style type="text/css">
    body,
    html {
        background-image: url("https://i.imgur.com/xhiRfL6.jpg");
        height: 100%;
    }

    #profile-img {
        height: 180px;
    }

    .h-80 {
        height: 80% !important;
    }
</style>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
    crossorigin="anonymous"></script>
<!------ Include the above in your HEAD tag ---------->

<div class="container h-80">
    <div class="row align-items-center h-100">
        <div class="col-3 mx-auto">
            <div class="text-center">
                <img id="profile-img" class="rounded-circle profile-img-card" src="https://i.imgur.com/6b6psnA.png" />
                <p id="profile-name" class="profile-name-card"></p>
                {{flash}}
                <form class="form-signin" method="post">
                    <input type="text" name="username" id="inputUsername" class="form-control form-group"
                        placeholder="username" required autofocus />
                    <input type="password" name="password" id="inputPassword" class="form-control form-group"
                        placeholder="password" required autofocus />
                    <button class="btn btn-lg btn-primary btn-block btn-signin" type="submit">
                        enter
                    </button>
                </form>
                <!-- /form -->
            </div>
        </div>
    </div>
</div>