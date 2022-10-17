
function load(img) {
    const url = img.getAttribute('lazy-src');
    img.setAttribute('src', url);

    img.removeAttribute('lazy-src');
}

function ready() {
    if ('IntersectionObserver' in window) {
        var lazyImgs = document.querySelectorAll('[lazy-src]');

        let observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    load(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        });

        lazyImgs.forEach(img => {
            observer.observe(img);
        });

    } else {
        var lazyImgs = document.querySelectorAll('[lazy-src]');

        var cssRule =
            "color: rgb(249, 162, 34);" +
            "font-size: 40px;" +
            "font-weight: bold;" +
            "text-shadow: 1px 1px 5px rgb(249, 162, 34);" +
            "filter: dropshadow(color=rgb(249, 162, 34), offx=1, offy=1);";
        console.log("%cTrình duyệt không hỗ trợ observer", cssRule);
        lazyImgs.forEach(img => {
            load(img);
        });
    }
}

document.addEventListener('DOMContentLoaded', ready);