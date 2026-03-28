@if(session('success'))
    <div class="alert alert-success" id="flash-message">
        <span>✅ {{ session('success') }}</span>
        <button class="btn-close-alert" onclick="this.parentElement.style.display='none'">&times;</button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger" id="flash-message">
        <span>⚠️ {{ session('error') }}</span>
        <button class="btn-close-alert" onclick="this.parentElement.style.display='none'">&times;</button>
    </div>
@endif

<script>
    // Menghilangkan notifikasi otomatis setelah 4 detik
    setTimeout(function() {
        const msg = document.getElementById('flash-message');
        if(msg) {
            msg.style.transition = "opacity 0.5s ease";
            msg.style.opacity = "0";
            setTimeout(() => msg.remove(), 500);
        }
    }, 4000);
</script>