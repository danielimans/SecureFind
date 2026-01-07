document.addEventListener('DOMContentLoaded', () => {

    /* =========================
    APPEARANCE FUNCTIONS (DEFINE FIRST)
    ========================= */
    
    /**
     * Apply theme to the application
     * @param {string} theme - Theme value: 'light', 'dark', or 'auto'
     */
    function applyTheme(theme) {
        const htmlElement = document.documentElement;
        
        if (theme === 'auto') {
            htmlElement.classList.remove('light-theme', 'dark-theme');
            localStorage.removeItem('appTheme');
            
            if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                htmlElement.classList.add('dark-theme');
            } else {
                htmlElement.classList.add('light-theme');
            }
        } else if (theme === 'dark') {
            htmlElement.classList.remove('light-theme');
            htmlElement.classList.add('dark-theme');
            localStorage.setItem('appTheme', 'dark');
        } else if (theme === 'light') {
            htmlElement.classList.remove('dark-theme');
            htmlElement.classList.add('light-theme');
            localStorage.setItem('appTheme', 'light');
        }
        
        updateThemeColors(theme);
    }
    
    /**
     * Update theme colors based on selection
     * @param {string} theme - Theme value
     */
    function updateThemeColors(theme) {
        const root = document.documentElement;
        
        if (theme === 'dark' || (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            root.style.setProperty('--bg-primary', '#1a1a1a');
            root.style.setProperty('--bg-secondary', '#2d2d2d');
            root.style.setProperty('--text-primary', '#ffffff');
            root.style.setProperty('--text-secondary', '#a0a0a0');
        } else {
            root.style.setProperty('--bg-primary', '#ffffff');
            root.style.setProperty('--bg-secondary', '#f5f5f5');
            root.style.setProperty('--text-primary', '#000000');
            root.style.setProperty('--text-secondary', '#666666');
        }
    }
    
    /**
     * Apply language to the application
     * @param {string} language - Language code: 'en' or 'my'
     */
    function applyLanguage(language) {
        document.documentElement.lang = language;
        localStorage.setItem('appLanguage', language);
        applyTranslations(language);
        
        if (language === 'ar' || language === 'he') {
            document.documentElement.dir = 'rtl';
        } else {
            document.documentElement.dir = 'ltr';
        }
    }
    
    /**
     * Apply translations based on selected language
     * @param {string} language - Language code
     */
    function applyTranslations(language) {
        const translations = {
            'en': {
                'page-title': 'Settings',
                'page-desc': 'Configure your application preferences and security options',
                'account-security': 'Account Security',
                'change-password': 'Change Password',
                'change-password-desc': 'Update your password to keep your account secure',
                'notifications': 'Notifications',
                'email-notifications': 'Email Notifications',
                'email-notifications-desc': 'Receive email updates about your reports and activities',
                'report-updates': 'Report Updates',
                'report-updates-desc': 'Get notified when your reports have updates',
                'lost-found': 'Lost & Found Matches',
                'lost-found-desc': 'Receive notifications when potential matches are found',
                'appearance': 'Appearance',
                'theme': 'Theme',
                'theme-desc': 'Choose your preferred theme',
                'language': 'Language',
                'language-desc': 'Select your preferred language',
                'change-password-btn': 'Change Password'
            },
            'my': {
                'page-title': 'Tetapan',
                'page-desc': 'Konfigurasi pilihan aplikasi dan keamanan anda',
                'account-security': 'Keamanan Akaun',
                'change-password': 'Tukar Kata Laluan',
                'change-password-desc': 'Kemas kini kata laluan anda untuk keamanan akaun',
                'notifications': 'Pemberitahuan',
                'email-notifications': 'Pemberitahuan E-mel',
                'email-notifications-desc': 'Terima kemas kini e-mel tentang laporan dan aktiviti anda',
                'report-updates': 'Kemas Kini Laporan',
                'report-updates-desc': 'Dapatkan pemberitahuan apabila laporan anda mempunyai kemas kini',
                'lost-found': 'Padanan Hilang & Ditemui',
                'lost-found-desc': 'Terima pemberitahuan apabila padanan berpotensi ditemui',
                'appearance': 'Rupa Bentuk',
                'theme': 'Tema',
                'theme-desc': 'Pilih tema pilihan anda',
                'language': 'Bahasa',
                'language-desc': 'Pilih bahasa pilihan anda',
                'change-password-btn': 'Tukar Kata Laluan'
            }
        };
        
        document.querySelectorAll('[data-i18n]').forEach(element => {
            const key = element.getAttribute('data-i18n');
            if (translations[language] && translations[language][key]) {
                element.textContent = translations[language][key];
            }
        });
    }
    
    /**
     * Get human-readable language name
     * @param {string} code - Language code
     * @returns {string} Language name
     */
    function getLanguageName(code) {
        const languages = {
            'en': 'English',
            'my': 'Malay'
        };
        return languages[code] || code;
    }
    
    /**
     * Capitalize first letter of a string
     * @param {string} str - Input string
     * @returns {string} Capitalized string
     */
    function capitalizeFirstLetter(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    /**
     * Show toast notifications
     * @param {string} message - Toast message
     * @param {string} type - Toast type (success/error)
     */
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            <span>${message}</span>
        `;
        document.body.appendChild(toast);

        setTimeout(() => toast.classList.add('show'), 10);
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    /* =========================
    TOGGLE SWITCHES
    ========================= */
    const toggles = document.querySelectorAll('.toggle-switch input');
    toggles.forEach(toggle => {
        toggle.addEventListener('change', (e) => {
            const isChecked = e.target.checked;
            const label = e.target.closest('.settings-item').querySelector('.settings-info h4').textContent;
            
            if (isChecked) {
                showToast(`${label} has been enabled`, 'success');
            } else {
                showToast(`${label} has been disabled`, 'success');
            }
        });
    });

    /* =========================
    SELECT DROPDOWNS
    ========================= */
    const themeSelect = document.getElementById('themeSelect');
    const languageSelect = document.getElementById('languageSelect');

    if (themeSelect) {
        const savedTheme = localStorage.getItem('appTheme') || 'auto';
        themeSelect.value = savedTheme;
        applyTheme(savedTheme);
        
        themeSelect.addEventListener('change', (e) => {
            const theme = e.target.value;
            applyTheme(theme);
            showToast(`Theme changed to ${capitalizeFirstLetter(theme)}`, 'success');
        });
    }

    if (languageSelect) {
        const savedLanguage = localStorage.getItem('appLanguage') || 'en';
        languageSelect.value = savedLanguage;
        applyLanguage(savedLanguage);
        
        languageSelect.addEventListener('change', (e) => {
            const language = e.target.value;
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            
            console.log('Changing language to:', language);
            console.log('CSRF Token:', csrfToken);
            
            // Send to backend using web route (not API)
            fetch('/language/change', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken || ''
                },
                body: JSON.stringify({ language: language })
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    applyLanguage(language);
                    showToast(`Language changed to ${getLanguageName(language)}`, 'success');
                } else {
                    showToast(data.message || 'Failed to change language', 'error');
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                showToast('Failed to change language', 'error');
            });
        });
    }

    /* =========================
    ACTION BUTTONS
    ========================= */
    const buttons = document.querySelectorAll('.btn-setting.secondary');
    buttons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            const action = e.currentTarget.textContent.trim();
            
            if (action.includes('View Sessions')) {
                showToast('Opening active sessions...', 'success');
            } else if (action.includes('Clear Cache')) {
                showToast('Cache cleared successfully', 'success');
            } else if (action.includes('Export Data')) {
                showToast('Your data is being prepared for download...', 'success');
            }
        });
    });

    /* =========================
    TWO-FACTOR TOGGLE
    ========================= */
    const twoFactorToggle = document.getElementById('twoFactorToggle');
    if (twoFactorToggle) {
        twoFactorToggle.addEventListener('change', (e) => {
            if (e.target.checked) {
                showToast('Two-Factor Authentication enabled', 'success');
            } else {
                showToast('Two-Factor Authentication disabled', 'success');
            }
        });
    }

    // Listen for system theme changes
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
        const savedTheme = localStorage.getItem('appTheme');
        if (!savedTheme || savedTheme === 'auto') {
            applyTheme('auto');
        }
    });
});