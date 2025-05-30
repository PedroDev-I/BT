let lastScrollTop = 0; // A posição da rolagem anterior

window.addEventListener("scroll", function() {
    let currentScroll = window.pageYOffset || document.documentElement.scrollTop;

    if (currentScroll > lastScrollTop) {
        // Se a rolagem for para baixo, esconde a navbar
        document.getElementById("navbar-top").classList.add("navbar-hidden");
    } else {
        // Se a rolagem for para cima, mostra a navbar
        document.getElementById("navbar-top").classList.remove("navbar-hidden");
    }

    lastScrollTop = currentScroll <= 0 ? 0 : currentScroll; // Evita que o valor negativo seja registrado
});

