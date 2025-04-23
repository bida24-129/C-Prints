document.addEventListener('DOMContentLoaded', function() {
    const serviceItems = document.querySelectorAll('.service-item');
    serviceItems.forEach((item, index) => {
        item.style.opacity = 0;
        item.style.transform = 'translateY(20px)';
        setTimeout(() => {
            item.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
            item.style.opacity = 1;
            item.style.transform = 'translateY(0)';
        }, index * 200);
    });
});