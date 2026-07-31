document.addEventListener('DOMContentLoaded', () => {
    /*
    |--------------------------------------------------------------------------
    | Menu mobile
    |--------------------------------------------------------------------------
    */

    const mobileMenuButton = document.getElementById(
        'mobile-menu-button'
    );

    const mobileSidebar = document.getElementById(
        'mobile-sidebar'
    );

    const mobileOverlay = document.getElementById(
        'mobile-overlay'
    );

    const closeSidebarButton = document.getElementById(
        'close-sidebar-btn'
    );

    const openMobileMenu = () => {
        if (
            !mobileSidebar ||
            !mobileOverlay ||
            !mobileMenuButton
        ) {
            return;
        }

        mobileSidebar.classList.remove(
            'translate-x-full'
        );

        mobileSidebar.classList.add(
            'translate-x-0'
        );

        mobileOverlay.classList.remove(
            'pointer-events-none',
            'opacity-0'
        );

        mobileOverlay.classList.add(
            'pointer-events-auto',
            'opacity-100'
        );

        mobileMenuButton.setAttribute(
            'aria-expanded',
            'true'
        );

        document.body.classList.add(
            'overflow-hidden'
        );
    };

    const closeMobileMenu = () => {
        if (
            !mobileSidebar ||
            !mobileOverlay ||
            !mobileMenuButton
        ) {
            return;
        }

        mobileSidebar.classList.remove(
            'translate-x-0'
        );

        mobileSidebar.classList.add(
            'translate-x-full'
        );

        mobileOverlay.classList.remove(
            'pointer-events-auto',
            'opacity-100'
        );

        mobileOverlay.classList.add(
            'pointer-events-none',
            'opacity-0'
        );

        mobileMenuButton.setAttribute(
            'aria-expanded',
            'false'
        );

        document.body.classList.remove(
            'overflow-hidden'
        );
    };

    mobileMenuButton?.addEventListener(
        'click',
        openMobileMenu
    );

    closeSidebarButton?.addEventListener(
        'click',
        closeMobileMenu
    );

    mobileOverlay?.addEventListener(
        'click',
        closeMobileMenu
    );

    mobileSidebar
        ?.querySelectorAll('a')
        .forEach((link) => {
            link.addEventListener(
                'click',
                closeMobileMenu
            );
        });

    document.addEventListener(
        'keydown',
        (event) => {
            if (event.key === 'Escape') {
                closeMobileMenu();
            }
        }
    );

    window.addEventListener(
        'resize',
        () => {
            if (window.innerWidth >= 768) {
                closeMobileMenu();
            }
        }
    );

    /*
    |--------------------------------------------------------------------------
    | Dropdown tài khoản Desktop
    |--------------------------------------------------------------------------
    */

    const userMenuButton = document.getElementById(
        'user-menu-button'
    );

    const userMenu = document.getElementById(
        'user-menu'
    );

    const userChevron = document.getElementById(
        'user-chevron'
    );

    const closeUserMenu = () => {
        if (!userMenu || !userMenuButton) {
            return;
        }

        userMenu.classList.add('hidden');

        userMenuButton.setAttribute(
            'aria-expanded',
            'false'
        );

        userChevron?.classList.remove(
            'rotate-180'
        );
    };

    const toggleUserMenu = () => {
        if (!userMenu || !userMenuButton) {
            return;
        }

        const isHidden =
            userMenu.classList.contains('hidden');

        userMenu.classList.toggle('hidden');

        userMenuButton.setAttribute(
            'aria-expanded',
            String(isHidden)
        );

        userChevron?.classList.toggle(
            'rotate-180',
            isHidden
        );
    };

    userMenuButton?.addEventListener(
        'click',
        (event) => {
            event.stopPropagation();
            toggleUserMenu();
        }
    );

    userMenu?.addEventListener(
        'click',
        (event) => {
            event.stopPropagation();
        }
    );

    document.addEventListener(
        'click',
        closeUserMenu
    );
});