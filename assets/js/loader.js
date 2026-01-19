  window.addEventListener("load", () => {
    setTimeout(() => {
      const preloader = document.getElementById("preloader");
      preloader.style.opacity = "0";
      preloader.style.transition = "opacity 0.5s ease";

      setTimeout(() => {
        preloader.style.display = "none";

        const mainContent = document.getElementById("mainContent");
        if (mainContent) {
          mainContent.classList.remove("opacity-0");
          mainContent.classList.add("animate__fadeInUp");
        }
      }, 500);
    }, 2000);
  });