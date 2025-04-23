let currentTestimonialIndex = 1;
showTestimonials(currentTestimonialIndex);

function showTestimonials(index) {
    let testimonials = document.querySelectorAll('.testimonials');
    if (index > testimonials.length) {
        currentTestimonialIndex = 1;
    }
    if (index < 1) {
        currentTestimonialIndex = testimonials.length;
    }
    for (let i = 0; i < testimonials.length; i++) {
        testimonials[i].style.display = 'none';
    }
    testimonials[currentTestimonialIndex - 1].style.display = 'grid';
}

function changeTestimonials(direction) {
    showTestimonials(currentTestimonialIndex += direction);
}