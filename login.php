<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Login</title>

    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200, 200i, 300, 300i, 400, 400i, 600, 600i, 700, 700i, 800, 800i, 900, 900i" rel="stylesheet">
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet"> 
</head>
<body>
    <h2>Login</h2>
    <form method="POST" id="formLogin">
        <div class="form-group">
            <input type="text" class="form-control form-control-user"
                id="exampleInputEmail" aria-describedby="emailHelp"
                placeholder="username..." name="username" required>
        </div>
        <div class="form-group">
            <input type="password" class="form-control form-control-user"
                id="exampleInputPassword" placeholder="Password" name="password" required>
        </div>
        <button class="btn btn-primary btn-user btn-block" type="submit" name="login">
            Login
        </button>
    </form>
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="assets/js/sb-admin-2.min.js"></script>
</body>
<script>
    // Pastikan id diawali dengan #
    $("#formLogin").on("submit", function (e) {
        e.preventDefault();

        const formData = $(this).serialize();

        $.ajax({
            url: "login_ajax.php", // Ganti dengan URL endpoint login
            type: "POST",
            data: formData,
            success: function (response) {
                try {
                    const res = typeof response === 'object' ? response : JSON.parse(response);
                    if (res.status === "success") {
                        localStorage.setItem("username", res.username);
                        window.location.href = "dashboard.php";
                    } else {
                        alert(res.message);
                    }
                } catch (e) {
                    alert("Response tidak valid: " + response);
                }
            },
            error: function () {
                alert("Terjadi kesalahan saat proses login.");
            }
        });
    });
</script>

</html>
