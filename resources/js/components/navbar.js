document.addEventListener("DOMContentLoaded", function () {
    // ===== Mobile Sidebar =====
    const openBtn = document.getElementById("mobile-menu-button");
    const closeBtn = document.getElementById("close-sidebar-btn");
    const sidebar = document.getElementById("mobile-sidebar");
    const overlay = document.getElementById("mobile-overlay");

    function openSidebar() {
        sidebar.classList.remove("translate-x-full");
        overlay.classList.remove("opacity-0", "pointer-events-none");
        overlay.classList.add("opacity-100");
        document.body.style.overflow = "hidden";
        openBtn?.setAttribute("aria-expanded", "true");
    }

    function closeSidebar() {
        sidebar.classList.add("translate-x-full");
        overlay.classList.add("opacity-0", "pointer-events-none");
        overlay.classList.remove("opacity-100");
        document.body.style.overflow = "";
        openBtn?.setAttribute("aria-expanded", "false");
    }

    openBtn?.addEventListener("click", openSidebar);
    closeBtn?.addEventListener("click", closeSidebar);
    overlay?.addEventListener("click", closeSidebar);
    sidebar
        ?.querySelectorAll("a")
        .forEach((link) => link.addEventListener("click", closeSidebar));

    // ===== Desktop User Dropdown =====
    const userBtn = document.getElementById("user-menu-button");
    const userMenu = document.getElementById("user-menu");
    const chevron = document.getElementById("user-chevron");

    function toggleUserMenu() {
        const isOpen = !userMenu.classList.contains("hidden");
        if (isOpen) {
            userMenu.classList.add("hidden");
            chevron?.classList.remove("rotate-180");
            userBtn?.setAttribute("aria-expanded", "false");
        } else {
            userMenu.classList.remove("hidden");
            chevron?.classList.add("rotate-180");
            userBtn?.setAttribute("aria-expanded", "true");
        }
    }

    function closeUserMenu() {
        userMenu?.classList.add("hidden");
        chevron?.classList.remove("rotate-180");
        userBtn?.setAttribute("aria-expanded", "false");
    }

    userBtn?.addEventListener("click", function (e) {
        e.stopPropagation();
        toggleUserMenu();
    });

    // Đóng khi click ra ngoài
    document.addEventListener("click", function (e) {
        if (
            userMenu &&
            !userMenu.contains(e.target) &&
            !userBtn?.contains(e.target)
        ) {
            closeUserMenu();
        }
    });

    // Đóng khi nhấn Escape
    document.addEventListener("keydown", function (e) {
        if (e.key === "Escape") closeUserMenu();
    });
});
