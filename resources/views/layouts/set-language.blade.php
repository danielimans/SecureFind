<script>
    // Load and apply saved language on every page
    document.addEventListener('DOMContentLoaded', function() {
        const savedLanguage = localStorage.getItem('appLanguage') || '{{ auth()->user()?->language_preference ?? 'en' }}';
        
        // Set HTML lang attribute
        document.documentElement.lang = savedLanguage;
        
        // Optional: Set app direction for RTL languages
        if (savedLanguage === 'ar' || savedLanguage === 'he') {
            document.documentElement.dir = 'rtl';
        } else {
            document.documentElement.dir = 'ltr';
        }
        
        // Dispatch custom event so other pages can listen
        window.dispatchEvent(new CustomEvent('languageChanged', { 
            detail: { language: savedLanguage } 
        }));
    });
</script>