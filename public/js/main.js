// main.js
window.addEventListener('load', async () => {
    Loader.init(); 
    await Loader.updatePercentage(80);
    await HiderAds.delayedHideAsync();
    await Loader.updatePercentage(100);

    const loader = document.getElementById('loader');
    if (loader) {
        loader.style.opacity = '0';
        setTimeout(() => loader.remove(), 500);
    }
});

