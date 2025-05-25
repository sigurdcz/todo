// loader.js
const Loader = (function () {
    let percentageEl = null;
    let progress = 0;

    function init() {
        percentageEl = document.getElementById('loader-percentage');
    }

    function updatePercentage(target, speed = 20) {
        return new Promise(resolve => {
            const interval = setInterval(() => {
                if (progress < target) {
                    progress++;
                    if (percentageEl) percentageEl.textContent = progress + '%';
                } else {
                    clearInterval(interval);
                    resolve();
                }
            }, speed);
        });
    }

    return {
        init,
        updatePercentage,
        getProgress: () => progress
    };
})();
