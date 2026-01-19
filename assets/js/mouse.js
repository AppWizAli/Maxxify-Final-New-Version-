
    const cursorInner = document.querySelector(".cursor-inner");
    const cursorOuter = document.querySelector(".cursor-outer");

    let mouseX = 0, mouseY = 0;
    let posX = 0, posY = 0;

    document.addEventListener("mousemove", e => {
      mouseX = e.clientX;
      mouseY = e.clientY;
      cursorInner.style.left = mouseX + "px";
      cursorInner.style.top = mouseY + "px";
    });

    function animateOuterCursor() {
      posX += (mouseX - posX) / 8;
      posY += (mouseY - posY) / 8;
      cursorOuter.style.left = posX + "px";
      cursorOuter.style.top = posY + "px";
      requestAnimationFrame(animateOuterCursor);
    }
    animateOuterCursor();

    // Hover effect on interactive elements
    const hoverTargets = document.querySelectorAll("button, a, .test-button");

    hoverTargets.forEach(el => {
      el.addEventListener("mouseenter", () => {
        cursorInner.classList.add("cursor-hover");
        cursorOuter.classList.add("cursor-hover");
      });
      el.addEventListener("mouseleave", () => {
        cursorInner.classList.remove("cursor-hover");
        cursorOuter.classList.remove("cursor-hover");
      });
    });