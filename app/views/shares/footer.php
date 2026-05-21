    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Image preview on file input
        document.querySelectorAll('input[type="file"][name="image"]').forEach(function(input) {
            input.addEventListener('change', function() {
                var preview = document.getElementById('imgPreview');
                if (preview && this.files && this.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });
        });
    </script>
</body>
</html>
