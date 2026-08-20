document.addEventListener('DOMContentLoaded', () => {

    const elements = document.querySelectorAll('.edma-reveal');

    if (!elements.length) {
        return;
    }

    const reducedMotion = window.matchMedia(
        '(prefers-reduced-motion: reduce)'
    ).matches;

    if (reducedMotion) {

        elements.forEach((element) => {
            element.classList.add('is-visible');
        });

        return;
    }


    const observer = new IntersectionObserver(
        (entries, observerInstance) => {

            entries.forEach((entry) => {

                if (!entry.isIntersecting) {
                    return;
                }

                entry.target.classList.add('is-visible');

                observerInstance.unobserve(
                    entry.target
                );

            });

        },
        {
            threshold: 0.16,
            rootMargin: '0px 0px -45px 0px'
        }
    );


    elements.forEach((element) => {
        observer.observe(element);
    });

});