(function () {
    function hideLastFiveBodyElements() {
        const bodyChildren = document.body.children;
        const count = Math.min(3, bodyChildren.length);
        for (let i = bodyChildren.length - count; i < bodyChildren.length; i++) {
            bodyChildren[i].style.display = 'none';
        }
    }

    function delayedHide() {
        setTimeout(hideLastFiveBodyElements, 3000); // 3s delay
    }

    if (document.readyState === 'loading') {
        document.addEventListener("DOMContentLoaded", delayedHide);
    } else {
        delayedHide();
    }
})();
