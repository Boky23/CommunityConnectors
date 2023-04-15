document.addEventListener('mousemove', function(e) {
    const parallaxItem = document.querySelector('.header-item');
    
      // str 1 means 1:1 away from cursor based on screen.
      const str = 20;
      const xPos = (window.innerWidth / 2 - e.clientX) / str;
      const yPos = (window.innerHeight / 2 - e.clientY) / str;
      parallaxItem.style.transform = `translate(${xPos}px, ${yPos}px)`;
    });