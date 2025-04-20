<?php
function loginme($email, $password)
{
    require 'database.php';

    // Prepared statement to prevent SQL injection
    $stmt = mysqli_prepare($connection, "SELECT * FROM accountstbl WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email); // "s" indicates a string parameter
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Password Verification (Important: Use password_verify with hashed passwords)
        if ($password == $row["password"]) { // Replace with password_verify!
            $_SESSION["login"] = true;
            $_SESSION["name"] = $row["fullname"];
            $_SESSION["email"] = $row["email"];
            $_SESSION["accessrole"] = $row["accessrole"];

            header("Location: index.php");
            exit();
        } else {
            echo "<script>alert('Incorrect username or password!');</script>";
            return;
        }
    } else {
        echo "<script>alert('This user is not registered.');</script>";
        return;
    }
    mysqli_stmt_close($stmt); // close the statement.
}
?>