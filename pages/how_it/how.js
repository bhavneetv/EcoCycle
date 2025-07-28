document.addEventListener('DOMContentLoaded', () => {
    const timeline = document.querySelector('.timeline');
    const timelineItems = document.querySelectorAll('.timeline-item');

    const options = {
        root: null,
        threshold: 0.2,
        rootMargin: "0px"
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate');
                updateTimelineLine(entry.target);
            }
        });
    }, options);

    // Observe timeline items
    timelineItems.forEach(item => {
        observer.observe(item);
    });

    // Calculate and update the timeline line height
    function updateTimelineLine(element) {
        const timelineRect = timeline.getBoundingClientRect();
        const elementRect = element.getBoundingClientRect();
        const relativeTop = elementRect.top - timelineRect.top;
        const line = document.querySelector('.timeline-line');

        if (line) {
            line.style.height = `${relativeTop + elementRect.height}px`;
        }
    }
});