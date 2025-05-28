const HiderAds = (function () {
    function hideLastThreeBodyElements() {
        const bodyChildren = document.body.children;
        const count = Math.min(4, bodyChildren.length);
        for (let i = bodyChildren.length - count; i < bodyChildren.length; i++) {
            bodyChildren[i].style.display = 'none';
        }
    }

    function delayedHideAsync(delay = 1000) {
        return new Promise(resolve => {
            setTimeout(() => {
                hideLastThreeBodyElements();
                resolve();
            }, delay);
        });
    }

    return {
        delayedHideAsync
    };
})();
