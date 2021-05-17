<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js');
        });
        // Initialize deferredPrompt for use later to show browser install prompt.
        let deferredPrompt;

        window.addEventListener('beforeinstallprompt', (e) => {
            // Prevent the mini-infobar from appearing on mobile
            e.preventDefault();
            // Stash the event so it can be triggered later.
            deferredPrompt = e;
            // Update UI notify the user they can install the PWA
            showInstallPromotion();
            // Optionally, send analytics event that PWA install promo was shown.
            console.log(`'beforeinstallprompt' event was fired.`);
        });
    }
 </script>
 <script src="{{asset('js/app.js')}}"></script>
 <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
 <script src="{{asset('dist/js/adminlte.js')}}"></script>


