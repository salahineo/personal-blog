<script>
  // Confirm Link & Ico
  let deleteBtns = document.querySelectorAll(".delete-btn");

  // Loop Through All Links
  for (let i = 0; i < deleteBtns.length; i++) {
    // Confirm On Link Click
    deleteBtns[i].onclick = function () {
      return confirm("هل أنت متأكد ؟");
    };
  }
</script>
