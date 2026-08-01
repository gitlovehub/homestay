/**
 * Menu thao tác dùng chung cho các trang quản trị.
 *
 * Chức năng:
 * - Chỉ mở một menu tại một thời điểm.
 * - Bấm ra ngoài để đóng menu.
 * - Nhấn phím Escape để đóng menu.
 */
const closeAllActionMenus = (exceptMenu = null) => {
    document
        .querySelectorAll('details[data-action-menu][open]')
        .forEach((menu) => {
            if (menu !== exceptMenu) {
                menu.removeAttribute('open');
            }
        });
};

document.addEventListener('click', (event) => {
    const summary = event.target.closest(
        'details[data-action-menu] > summary'
    );

    /*
     * Khi bấm nút ba chấm:
     * đóng tất cả menu khác, giữ lại menu hiện tại.
     */
    if (summary) {
        const currentMenu = summary.closest(
            'details[data-action-menu]'
        );

        closeAllActionMenus(currentMenu);

        return;
    }

    /*
     * Khi bấm ra ngoài menu:
     * đóng tất cả menu đang mở.
     */
    const clickedMenu = event.target.closest(
        'details[data-action-menu]'
    );

    if (!clickedMenu) {
        closeAllActionMenus();
    }
});

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        closeAllActionMenus();
    }
});