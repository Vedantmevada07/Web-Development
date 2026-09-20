/**
 * Global Interactivity & Theme Configuration Script
 * Safe for multi-page deployment
 */
document.addEventListener('DOMContentLoaded', () => {
    
    // =========================================================================
    // 1. GLOBAL LIGHT/DARK THEME SWITCHER (With localStorage Persistence)
    // =========================================================================
    
    const THEME_KEY = 'ui-theme';
    const bodyEl = document.body;

    /**
     * Applies the selected theme variables and updates button text if present
     * @param {'dark' | 'light'} theme 
     */
    const applyTheme = (theme) => {
        // Find the toggle button on the current page (if it exists)
        const themeToggleBtn = document.querySelector('#theme-toggle');

        if (theme === 'dark') {
            bodyEl.classList.add('dark-theme');
            if (themeToggleBtn) themeToggleBtn.innerHTML = '☀️ Light Mode';
        } else {
            bodyEl.classList.remove('dark-theme');
            if (themeToggleBtn) themeToggleBtn.innerHTML = '🌙 Dark Mode';
        }
    };

    // Initialize: Check localStorage immediately on page load, fallback to light
    const activeTheme = localStorage.getItem(THEME_KEY) || 'light';
    applyTheme(activeTheme);

    // Event Delegation: Safely capture clicks on any page matching our button ID
    document.addEventListener('click', (event) => {
        const toggleBtn = event.target.closest('#theme-toggle');
        if (!toggleBtn) return; // Exit if the clicked item isn't the theme switcher

        const isDarkNow = bodyEl.classList.contains('dark-theme');
        const nextTheme = isDarkNow ? 'light' : 'dark';

        localStorage.setItem(THEME_KEY, nextTheme);
        applyTheme(nextTheme);
    });

    // =========================================================================
    // 2. ADDITIONAL COMPONENT INITIALIZATIONS (Problem 4 Extensions)
    // =========================================================================
    
    // FAQ Accordion logic goes here...
    
    // Modal Popup logic goes here...
    
    // Slider / Carousel logic goes here...
    
    // Notification Banner logic goes here...
    
    // Hamburger Menu logic goes here...

});
