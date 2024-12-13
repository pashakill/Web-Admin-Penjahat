<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include('../db.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $users = $result->fetch_assoc();
    } else {
        die("Username tidak di temukan.");
    }
} else {
    die("Username dibutuhkan.");
}
?>

<h1 class="h3 mb-2 text-gray-800">Edit User</h1>
<form id="editUserForm">
    <input type="hidden" name="id" id="id" value="<?php echo $users['id']; ?>">

    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" class="form-control" id="username" name="username" value="<?php echo $users['username']; ?>" required>
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="text" class="form-control" id="password" name="password" value="<?php echo $users['password']; ?>" required>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </div>
</form>

<script>
    $('#editUserForm').on('submit', function (e) {
    e.preventDefault();
    
    const data = {
        id: $('#id').val(),
        username: $('#username').val(),
        password: $('#password').val(),
    };

    $.ajax({
        url: '/web/user/edit_user.php', // Ensure this URL is correct for your application
        type: 'POST',
        data: data,
        success: function (response) {
            const res = JSON.parse(response);
            if (res.status === 'success') {
                alert(res.message);
                // Reload or do something to reflect the changes
                loadUser();
            } else {
                alert(res.message);
            }
        },
        error: function () {
            alert("Terjadi kesalahan saat mengedit user.");
        }
    });
});

// Cancel action
$('#cancel').on('click', function () {
    window.location.href = '/path/to/cancel';  // Or handle cancel action
});
</script>