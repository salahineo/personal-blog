<script>
  // Get Show / Hide Password Icon
  let hidePassIco = document.querySelector("form .form-group.password i");
  let inputPassword = document.querySelector("form .form-group.password input[type='password']");
  // On Click
  hidePassIco.onclick = function () {
    // Check
    if (this.getAttribute("class") === "fas fa-eye") {
      // Change Its Status
      this.setAttribute("class", "fas fa-eye-slash");
      // Change Input Type
      inputPassword.setAttribute("type", "text");
    } else {
      // Change Its Status
      this.setAttribute("class", "fas fa-eye");
      // Change Input Type
      inputPassword.setAttribute("type", "password");
    }
  };
</script>
