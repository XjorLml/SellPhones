function togglePasswordVisibility(targetId, otherId) {
    var passwordField = document.getElementById(targetId);
    var otherPasswordField = document.getElementById(otherId);

    if (passwordField.type === "password") {
        passwordField.type = "text";
        otherPasswordField.type = "text";
    } else {
        passwordField.type = "password";
        otherPasswordField.type = "password";
    }
}
