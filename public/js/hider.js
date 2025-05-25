const HiderAds = (function () {
    function hideLastThreeBodyElements() {
        const bodyChildren = document.body.children;
        const count = Math.min(3, bodyChildren.length);
        for (let i = bodyChildren.length - count; i < bodyChildren.length; i++) {
            bodyChildren[i].style.display = 'none';
        }
    }

    function delayedHideAsync(delay = 3000) {
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
