  <!-- Start Footer -->
  <footer class="bg-dark text-light">
    <div class="container">
      <div class="row d-flex flex-column flex-md-row align-items-center justify-content-center">
        <div class="copyright col-md-6 mb-2 mb-md-0 text-center">
          &copy; <span id="footer-copyright-year"></span> | <a href="https://salahineo.com" target="_blank" rel="noopener" class="text-primary font-weight-bold">Mohamed Salah</a>
        </div>
      </div>
    </div>
  </footer>
  <!-- End Footer -->

  <!-- Start JS -->
  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script>
    // New date Object
    let currentDate = new Date();
    // Get Copyright Year Span
    document.getElementById("footer-copyright-year").innerHTML = String(currentDate.getFullYear());
  </script>
  <!-- End JS -->
</body>
</html>
