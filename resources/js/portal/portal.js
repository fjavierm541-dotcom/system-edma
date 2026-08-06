document.addEventListener('DOMContentLoaded', () => {
    const wrapper = document.getElementById('portalWrapper');
    const sidebar = document.getElementById('portalSidebar');
    const sidebarToggle = document.getElementById('portalSidebarToggle');
    const sidebarOverlay = document.getElementById('portalSidebarOverlay');

    if (!wrapper || !sidebar || !sidebarToggle || !sidebarOverlay) {
        return;
    }

    const desktopBreakpoint = 992;

    const isDesktop = () => window.innerWidth >= desktopBreakpoint;

    const openMobileSidebar = () => {
        sidebar.classList.add('show');
        sidebarOverlay.classList.add('show');
        document.body.style.overflow = 'hidden';

        sidebarToggle.setAttribute('aria-expanded', 'true');
        sidebarOverlay.setAttribute('aria-hidden', 'false');
    };

    const closeMobileSidebar = () => {
        sidebar.classList.remove('show');
        sidebarOverlay.classList.remove('show');
        document.body.style.overflow = '';

        sidebarToggle.setAttribute('aria-expanded', 'false');
        sidebarOverlay.setAttribute('aria-hidden', 'true');
    };

    const toggleDesktopSidebar = () => {
        wrapper.classList.toggle('sidebar-collapsed');

        const isCollapsed =
            wrapper.classList.contains('sidebar-collapsed');

        localStorage.setItem(
            'edmaPortalSidebarCollapsed',
            String(isCollapsed)
        );

        sidebarToggle.setAttribute(
            'aria-expanded',
            String(!isCollapsed)
        );
    };

    const restoreDesktopSidebarState = () => {
        const sidebarWasCollapsed =
            localStorage.getItem('edmaPortalSidebarCollapsed') === 'true';

        wrapper.classList.toggle(
            'sidebar-collapsed',
            sidebarWasCollapsed
        );

        sidebarToggle.setAttribute(
            'aria-expanded',
            String(!sidebarWasCollapsed)
        );
    };

    sidebarToggle.addEventListener('click', () => {
        if (isDesktop()) {
            toggleDesktopSidebar();
            return;
        }

        if (sidebar.classList.contains('show')) {
            closeMobileSidebar();
            return;
        }

        openMobileSidebar();
    });

    sidebarOverlay.addEventListener('click', closeMobileSidebar);

    window.addEventListener('resize', () => {
        if (isDesktop()) {
            closeMobileSidebar();
            restoreDesktopSidebarState();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (
            event.key === 'Escape' &&
            sidebar.classList.contains('show')
        ) {
            closeMobileSidebar();
        }
    });

    if (isDesktop()) {
        restoreDesktopSidebarState();
    }
});