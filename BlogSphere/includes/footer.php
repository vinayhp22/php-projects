</main>
  <footer class="bg-light py-4">
    <div class="container text-center">
      <p class="mb-2">&copy; <?php echo date("Y"); ?> BlogSphere. All rights reserved.</p>
      <div class="social-sharing">
        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']); ?>" target="_blank" class="btn btn-outline-primary mx-1">Share on Facebook</a>
        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']); ?>" target="_blank" class="btn btn-outline-info mx-1">Share on Twitter</a>
      </div>
    </div>
  </footer>
  <!-- Bootstrap JS and dependencies -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="/BlogSphere/assets/js/script.js"></script>
</body>
</html>
