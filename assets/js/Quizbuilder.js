// sidebar dropdwnn 

 document.addEventListener('DOMContentLoaded', function() {
            // Select all dropdown content containers and arrow icons using classes
            const dropdownContents = document.querySelectorAll('.dropdown-content');
            const arrowIcons = document.querySelectorAll('.arrow-icon');
            const sidebarNav = document.getElementById('sidebarNav');
            const menuItems = sidebarNav.querySelectorAll('.menu-item'); // All menu items

            const LAST_ACTIVE_MENU_ITEM = 'lastActiveMenuItem';

            // Function to reset all menu items to their inactive state
            function resetAllMenuItems() {
                menuItems.forEach(mi => {
                    mi.classList.remove('bg-[#673AB7]', 'active-menu'); // Remove background and active class
                    const link = mi.querySelector('a');
                    if (link) link.classList.remove('text-white'); // Reset link text color
                    const span = mi.querySelector('span');
                    if (span) span.classList.remove('text-white'); // Reset span text color (for 'Products')
                    const icon = mi.querySelector('.icon');
                    if (icon) icon.style.filter = ''; // Reset icon color (remove invert filter)
                });

                // Close all dropdowns and reset their arrows
                dropdownContents.forEach(dropdown => {
                    dropdown.classList.add('max-h-0');
                    dropdown.style.maxHeight = '0px';
                });
                arrowIcons.forEach(arrow => {
                    arrow.classList.remove('rotate-180');
                    arrow.style.filter = '';
                });
            }

            // Function to set the active state for a specific menu item
            function setActiveMenuItem(itemToActivate) {
                resetAllMenuItems(); // Always reset all first

                if (itemToActivate) {
                    itemToActivate.classList.add('bg-[#673AB7]', 'active-menu');
                    const link = itemToActivate.querySelector('a');
                    if (link) link.classList.add('text-white');
                    const span = itemToActivate.querySelector('span');
                    if (span) span.classList.add('text-white');
                    const icon = itemToActivate.querySelector('.icon');
                    if (icon) icon.style.filter = 'invert(100%)';

                    // Check if the activated item is a child of any dropdown
                    let parentDropdownTrigger = null;
                    dropdownContents.forEach(dropdown => {
                        if (dropdown.contains(itemToActivate)) {
                            // Find the parent menu-item that triggers this dropdown
                            parentDropdownTrigger = document.querySelector(`[data-dropdown-target="${dropdown.id}"]`);
                        }
                    });

                    if (parentDropdownTrigger) {
                        // Activate the parent dropdown trigger
                        parentDropdownTrigger.classList.add('bg-[#673AB7]', 'active-menu');
                        parentDropdownTrigger.querySelector('.icon').style.filter = 'invert(100%)';
                        parentDropdownTrigger.querySelector('span').classList.add('text-white');

                        // Open the parent dropdown
                        const targetDropdownId = parentDropdownTrigger.dataset.dropdownTarget;
                        const targetDropdown = document.getElementById(targetDropdownId);
                        targetDropdown.classList.remove('max-h-0');
                        targetDropdown.style.maxHeight = targetDropdown.scrollHeight + 'px';

                        // Rotate and color the parent's arrow
                        const targetArrowId = parentDropdownTrigger.dataset.arrowTarget;
                        const targetArrow = document.getElementById(targetArrowId);
                        targetArrow.classList.add('rotate-180');
                        targetArrow.style.filter = 'invert(100%)';
                    }

                    // Save the active item's ID to localStorage
                    localStorage.setItem(LAST_ACTIVE_MENU_ITEM, itemToActivate.dataset.id);
                } else {
                    localStorage.removeItem(LAST_ACTIVE_MENU_ITEM);
                }
            }

            // Centralized function to toggle any dropdown
            function toggleAnyDropdown(triggerElement) {
                const targetDropdownId = triggerElement.dataset.dropdownTarget;
                const targetArrowId = triggerElement.dataset.arrowTarget;

                const targetDropdown = document.getElementById(targetDropdownId);
                const targetArrow = document.getElementById(targetArrowId);

                const isCollapsed = targetDropdown.classList.contains('max-h-0');

                // Close all other dropdowns before opening a new one,
                // but keep the current dropdown (if it's being closed) in its state
                dropdownContents.forEach(dropdown => {
                    if (dropdown.id !== targetDropdownId) { // Close others
                        dropdown.classList.add('max-h-0');
                        dropdown.style.maxHeight = '0px';
                        const correspondingTrigger = document.querySelector(`[data-dropdown-target="${dropdown.id}"]`);
                        if (correspondingTrigger) {
                             const correspondingArrow = document.getElementById(correspondingTrigger.dataset.arrowTarget);
                             if(correspondingArrow) {
                                correspondingArrow.classList.remove('rotate-180');
                                correspondingArrow.style.filter = '';
                             }
                        }
                    }
                });


                if (isCollapsed) {
                    // Open the target dropdown
                    setActiveMenuItem(triggerElement); // Activate the dropdown header itself
                    targetDropdown.classList.remove('max-h-0');
                    targetDropdown.style.maxHeight = targetDropdown.scrollHeight + 'px';
                    targetArrow.classList.add('rotate-180');
                    targetArrow.style.filter = 'invert(100%)';
                } else {
                    // Close the target dropdown
                    targetDropdown.style.maxHeight = targetDropdown.scrollHeight + 'px';
                    void targetDropdown.offsetWidth; // Trigger reflow
                    targetDropdown.classList.add('max-h-0');
                    targetDropdown.style.maxHeight = '0px';

                    // Deactivate the trigger and reset its arrow
                    // If a child of this dropdown was active, it will be reset by setActiveMenuItem's initial reset
                    triggerElement.classList.remove('bg-[#673AB7]', 'active-menu');
                    triggerElement.querySelector('.icon').style.filter = '';
                    triggerElement.querySelector('span').classList.remove('text-white');
                    targetArrow.classList.remove('rotate-180');
                    targetArrow.style.filter = '';

                    // Important: if we just closed a dropdown, and there's no specific child active
                    // we need to make sure localStorage doesn't point to a child of this now closed dropdown.
                    // This is handled by initializeActiveState on next page load or explicit click.
                    // For simply closing, we might want to default to home if no other active item is known.
                    // However, the current logic of setActiveMenuItem handles setting localStorage.
                    // If you close a dropdown, and nothing else is clicked, localStorage will still hold the last *clicked* item.
                    // If the last clicked item was a sub-item of the dropdown just closed, it will still try to activate it on reload.
                    // This is acceptable behavior for persistence.
                }
            }


            // Event listener for sidebar menu item clicks
            sidebarNav.addEventListener('click', function(event) {
                const clickedItem = event.target.closest('.menu-item');

                if (clickedItem) {
                    // Check if the clicked item is a dropdown trigger
                    if (clickedItem.hasAttribute('data-dropdown-target')) {
                        toggleAnyDropdown(clickedItem);
                    } else {
                        // For regular menu items (or sub-items of dropdowns)
                        setActiveMenuItem(clickedItem);
                    }
                }
            });

            // Initial setup on page load to determine the active state
            function initializeActiveState() {
                const currentPath = window.location.pathname.split('/').pop();
                let itemToActivate = null;

                // Priority 1: Find an item whose link matches the current page URL
                menuItems.forEach(mi => {
                    const link = mi.querySelector('a');
                    if (link && link.href) {
                        const linkPath = link.href.split('/').pop();
                        // Special handling for 'Sidebar.html' (can also be '' for index.html or root)
                        const isHomePage = (linkPath === 'Sidebar.html' && (currentPath === '' || currentPath === 'Sidebar.html' || currentPath === 'index.html'));

                        if (linkPath === currentPath || isHomePage) {
                            itemToActivate = mi;
                        }
                    }
                });

                if (itemToActivate) {
                    setActiveMenuItem(itemToActivate);
                } else {
                    // Priority 2: If no direct URL match, try localStorage
                    let lastActiveId = localStorage.getItem(LAST_ACTIVE_MENU_ITEM);
                    if (lastActiveId) {
                        itemToActivate = document.querySelector(`[data-id="${lastActiveId}"]`);
                        if (itemToActivate) {
                            setActiveMenuItem(itemToActivate);
                        } else {
                            // If ID in localStorage but element not found, clear storage and default
                            localStorage.removeItem(LAST_ACTIVE_MENU_ITEM);
                            setActiveMenuItem(document.querySelector('[data-id="home"]'));
                        }
                    } else {
                        // Priority 3: No match found by URL or localStorage, default to 'Home'
                        setActiveMenuItem(document.querySelector('[data-id="home"]'));
                    }
                }
            }

            initializeActiveState();
        });


 const tutorBtn = document.getElementById('tutorBtn');
    const testBtn = document.getElementById('testBtn');
    const tutorContent = document.getElementById('tutorContent');
    const testContent = document.getElementById('testContent');

    if (tutorBtn && testBtn && tutorContent && testContent) {
        tutorBtn.addEventListener('click', () => {
          tutorBtn.classList.add('bg-[#673AB7]', 'text-white');
          tutorBtn.classList.remove('bg-purple-100', 'text-[#673AB7]');
          testBtn.classList.add('bg-purple-100', 'text-[#673AB7]');
          testBtn.classList.remove('bg-[#673AB7]', 'text-white');

          tutorContent.classList.remove('hidden');
          testContent.classList.add('hidden');
        });

        testBtn.addEventListener('click', () => {
          testBtn.classList.add('bg-[#673AB7]', 'text-white');
          testBtn.classList.remove('bg-purple-100', 'text-[#673AB7]');
          tutorBtn.classList.add('bg-purple-100', 'text-[#673AB7]');
          tutorBtn.classList.remove('bg-[#673AB7]', 'text-white');

          tutorContent.classList.add('hidden');
          testContent.classList.remove('hidden');
        });
    }