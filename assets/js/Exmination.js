


    // ........................................................

  document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const dropdownContents = document.querySelectorAll('.dropdown-content');
    const arrowIcons = document.querySelectorAll('.arrow-icon');
    const sidebarNav = document.getElementById('sidebarNav');
    const menuItems = sidebarNav.querySelectorAll('.menu-item');
    const LAST_ACTIVE_MENU_ITEM = 'lastActiveMenuItem';

    // 🔁 Sidebar Toggle (Mobile)
    window.toggleSidebar = function () {
      const isHidden = sidebar.classList.contains('-translate-x-full');
      sidebar.classList.toggle('-translate-x-full', !isHidden);
      overlay.classList.toggle('hidden', isHidden);
    };

    // 🔄 Reset All Menu Items
    function resetAllMenuItems() {
      menuItems.forEach(mi => {
        mi.classList.remove('bg-[#673AB7]', 'active-menu');
        mi.querySelector('a')?.classList.remove('text-white');
        mi.querySelector('span')?.classList.remove('text-white');
        const icon = mi.querySelector('.icon');
        if (icon) icon.style.filter = '';
      });

      dropdownContents.forEach(dropdown => {
        dropdown.classList.add('max-h-0');
        dropdown.style.maxHeight = '0px';
      });
      arrowIcons.forEach(arrow => {
        arrow.classList.remove('rotate-180');
        arrow.style.filter = '';
      });
    }

    // ✅ Set Active Item
    function setActiveMenuItem(itemToActivate) {
      resetAllMenuItems();

      if (itemToActivate) {
        itemToActivate.classList.add('bg-[#673AB7]', 'active-menu');
        itemToActivate.querySelector('a')?.classList.add('text-white');
        itemToActivate.querySelector('span')?.classList.add('text-white');
        const icon = itemToActivate.querySelector('.icon');
        if (icon) icon.style.filter = 'invert(100%)';

        let parentDropdownTrigger = null;
        dropdownContents.forEach(dropdown => {
          if (dropdown.contains(itemToActivate)) {
            parentDropdownTrigger = document.querySelector(`[data-dropdown-target="${dropdown.id}"]`);
          }
        });

        if (parentDropdownTrigger) {
          parentDropdownTrigger.classList.add('bg-[#673AB7]', 'active-menu');
          parentDropdownTrigger.querySelector('.icon').style.filter = 'invert(100%)';
          parentDropdownTrigger.querySelector('span').classList.add('text-white');

          const targetDropdown = document.getElementById(parentDropdownTrigger.dataset.dropdownTarget);
          targetDropdown.classList.remove('max-h-0');
          targetDropdown.style.maxHeight = targetDropdown.scrollHeight + 'px';

          const targetArrow = document.getElementById(parentDropdownTrigger.dataset.arrowTarget);
          targetArrow.classList.add('rotate-180');
          targetArrow.style.filter = 'invert(100%)';
        }

        localStorage.setItem(LAST_ACTIVE_MENU_ITEM, itemToActivate.dataset.id);
      } else {
        localStorage.removeItem(LAST_ACTIVE_MENU_ITEM);
      }
    }

    // ⬇️ Toggle Dropdown
    function toggleAnyDropdown(triggerElement) {
      const targetDropdown = document.getElementById(triggerElement.dataset.dropdownTarget);
      const targetArrow = document.getElementById(triggerElement.dataset.arrowTarget);
      const isCollapsed = targetDropdown.classList.contains('max-h-0');

      dropdownContents.forEach(dropdown => {
        if (dropdown.id !== triggerElement.dataset.dropdownTarget) {
          dropdown.classList.add('max-h-0');
          dropdown.style.maxHeight = '0px';
          const otherTrigger = document.querySelector(`[data-dropdown-target="${dropdown.id}"]`);
          if (otherTrigger) {
            const otherArrow = document.getElementById(otherTrigger.dataset.arrowTarget);
            if (otherArrow) {
              otherArrow.classList.remove('rotate-180');
              otherArrow.style.filter = '';
            }
          }
        }
      });

      if (isCollapsed) {
        setActiveMenuItem(triggerElement);
        targetDropdown.classList.remove('max-h-0');
        targetDropdown.style.maxHeight = targetDropdown.scrollHeight + 'px';
        targetArrow.classList.add('rotate-180');
        targetArrow.style.filter = 'invert(100%)';
      } else {
        targetDropdown.style.maxHeight = targetDropdown.scrollHeight + 'px';
        void targetDropdown.offsetWidth;
        targetDropdown.classList.add('max-h-0');
        targetDropdown.style.maxHeight = '0px';

        triggerElement.classList.remove('bg-[#673AB7]', 'active-menu');
        triggerElement.querySelector('.icon').style.filter = '';
        triggerElement.querySelector('span').classList.remove('text-white');
        targetArrow.classList.remove('rotate-180');
        targetArrow.style.filter = '';
      }
    }

    // 📦 Sidebar Click Handler
    sidebarNav.addEventListener('click', function (event) {
      const clickedItem = event.target.closest('.menu-item');
      if (clickedItem) {
        if (clickedItem.hasAttribute('data-dropdown-target')) {
          toggleAnyDropdown(clickedItem);
        } else {
          setActiveMenuItem(clickedItem);

          // Auto-close sidebar on mobile after selecting menu
          if (window.innerWidth < 1024) toggleSidebar();
        }
      }
    });

    // ⚙️ Initial Active Item (URL or Storage)
    function initializeActiveState() {
      const currentPath = window.location.pathname.split('/').pop();
      let itemToActivate = null;

      menuItems.forEach(mi => {
        const link = mi.querySelector('a');
        if (link && link.href) {
          const linkPath = link.href.split('/').pop();
          const isHomePage = (linkPath === 'Sidebar.html' && (currentPath === '' || currentPath === 'Sidebar.html' || currentPath === 'index.html'));
          if (linkPath === currentPath || isHomePage) {
            itemToActivate = mi;
          }
        }
      });

      if (itemToActivate) {
        setActiveMenuItem(itemToActivate);
      } else {
        const lastActiveId = localStorage.getItem(LAST_ACTIVE_MENU_ITEM);
        if (lastActiveId) {
          itemToActivate = document.querySelector(`[data-id="${lastActiveId}"]`);
          if (itemToActivate) {
            setActiveMenuItem(itemToActivate);
          } else {
            localStorage.removeItem(LAST_ACTIVE_MENU_ITEM);
            setActiveMenuItem(document.querySelector('[data-id="home"]'));
          }
        } else {
          setActiveMenuItem(document.querySelector('[data-id="home"]'));
        }
      }
    }

    initializeActiveState();
  });


