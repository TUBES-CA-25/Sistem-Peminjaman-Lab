<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'auto';
            const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            const themeToApply = savedTheme === 'auto' ? systemTheme : savedTheme;
            document.documentElement.setAttribute('data-theme', themeToApply);
        })();

        document.addEventListener('DOMContentLoaded', function() {
            const themeButtons = document.querySelectorAll('[data-theme-value]');
            const activeIcon = document.querySelector('.theme-icon-active');

            const updateIcon = (theme) => {
                if (!activeIcon) return;
                activeIcon.className = 'theme-icon-active bi ' + 
                    (theme === 'light' ? 'bi-sun-fill' : 
                     theme === 'dark' ? 'bi-moon-stars-fill' : 'bi-circle-half');
            };

            const applyTheme = (theme) => {
                const themeToApply = theme === 'auto' ? 
                    (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light') : 
                    theme;
                
                document.documentElement.setAttribute('data-theme', themeToApply);
                localStorage.setItem('theme', theme);
                updateIcon(theme);
            };

            themeButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const theme = btn.getAttribute('data-theme-value');
                    applyTheme(theme);
                });
            });

            // Set initial icon
            updateIcon(localStorage.getItem('theme') || 'auto');

            // Listen for system changes if set to auto
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
                if (localStorage.getItem('theme') === 'auto') {
                    applyTheme('auto');
                }
            });
        });
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internal Portal - ICLABS</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Admin CSS (shared styling) -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/admin-style.css?v=<?= time() ?>">
    <!-- Internal specific CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/internal-booking.css?v=<?= time() ?>">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
