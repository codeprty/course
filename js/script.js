document.addEventListener('DOMContentLoaded', function () {
  const outerContainer = document.querySelector('.testimonial-card-container');
  const cards = Array.from(outerContainer.children);
  const total = cards.length;
  let index = 0;

  // Create inner container for sliding
  const innerContainer = document.createElement('div');
  innerContainer.classList.add('testimonial-card-container-inner');

  // Move cards into inner container
  cards.forEach(card => innerContainer.appendChild(card));
  outerContainer.appendChild(innerContainer);

  // Clone first card for seamless loop
  const clone = cards[0].cloneNode(true);
  innerContainer.appendChild(clone);

  function slideNext() {
    index++;
    innerContainer.style.transition = 'transform 0.5s ease-in-out';
    innerContainer.style.transform = `translateX(-${index * 100}%)`;

    if (index === total) {
      setTimeout(() => {
        innerContainer.style.transition = 'none';
        innerContainer.style.transform = 'translateX(0)';
        index = 0;
      }, 500);
    }
  }

  setInterval(slideNext, 3000);
});
