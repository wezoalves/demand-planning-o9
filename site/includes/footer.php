            </div>
            </main>
            </div>

            <script>
                // Sidebar toggle functionality
                const sidebar = document.getElementById('sidebar');
                const mainContent = document.getElementById('mainContent');
                const sidebarToggle = document.getElementById('sidebarToggle');

                // Check for saved sidebar state
                const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';

                if (sidebarCollapsed) {
                    sidebar.classList.add('collapsed');
                    mainContent.classList.add('sidebar-collapsed');
                    sidebarToggle.querySelector('i').classList.remove('fa-chevron-left');
                    sidebarToggle.querySelector('i').classList.add('fa-chevron-right');
                }

                // Toggle sidebar
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                    mainContent.classList.toggle('sidebar-collapsed');

                    const isCollapsed = sidebar.classList.contains('collapsed');
                    localStorage.setItem('sidebarCollapsed', isCollapsed);

                    if (isCollapsed) {
                        sidebarToggle.querySelector('i').classList.remove('fa-chevron-left');
                        sidebarToggle.querySelector('i').classList.add('fa-chevron-right');
                    } else {
                        sidebarToggle.querySelector('i').classList.remove('fa-chevron-right');
                        sidebarToggle.querySelector('i').classList.add('fa-chevron-left');
                    }
                });

                // Navigation item click handlers
                document.querySelectorAll('.nav-item[data-chapter]').forEach(item => {
                    item.addEventListener('click', function(e) {
                        e.preventDefault();

                        const chapter = this.getAttribute('data-chapter');
                        const submenu = document.getElementById('submenu-' + chapter);
                        const chevron = this.querySelector('.nav-chevron');

                        // Toggle submenu
                        if (submenu.classList.contains('collapsed')) {
                            submenu.classList.remove('collapsed');
                            this.classList.add('expanded');
                            chevron.style.transform = 'rotate(90deg)';
                        } else {
                            submenu.classList.add('collapsed');
                            this.classList.remove('expanded');
                            chevron.style.transform = 'rotate(0deg)';
                        }
                    });
                });

                // Auto-expand active chapter
                const activeNavItem = document.querySelector('.nav-item.active');
                if (activeNavItem) {
                    const chapter = activeNavItem.getAttribute('data-chapter');
                    const submenu = document.getElementById('submenu-' + chapter);
                    const chevron = activeNavItem.querySelector('.nav-chevron');

                    if (submenu) {
                        submenu.classList.remove('collapsed');
                        activeNavItem.classList.add('expanded');
                        chevron.style.transform = 'rotate(90deg)';
                    }
                }

                // Auto-expand chapter if we're on a content page
                const urlParams = new URLSearchParams(window.location.search);
                const currentSlug = urlParams.get('slug');
                const currentChapter = urlParams.get('chapter');

                if (currentSlug || currentChapter) {
                    // Find the chapter that contains the current content
                    let targetChapter = currentChapter;

                    if (currentSlug && !currentChapter) {
                        // If we're on a content page, find which chapter it belongs to
                        const contentItems = document.querySelectorAll('.nav-submenu-item');
                        contentItems.forEach(item => {
                            if (item.href.includes(currentSlug)) {
                                const parentSubmenu = item.closest('.nav-submenu');
                                if (parentSubmenu) {
                                    targetChapter = parentSubmenu.id.replace('submenu-', '');
                                }
                            }
                        });
                    }

                    if (targetChapter) {
                        const chapterItem = document.querySelector(`[data-chapter="${targetChapter}"]`);
                        const submenu = document.getElementById('submenu-' + targetChapter);
                        const chevron = chapterItem?.querySelector('.nav-chevron');

                        if (chapterItem && submenu) {
                            submenu.classList.remove('collapsed');
                            chapterItem.classList.add('expanded');
                            if (chevron) {
                                chevron.style.transform = 'rotate(90deg)';
                            }
                        }
                    }
                }

                // Função de busca
                document.getElementById('searchInput').addEventListener('input', function(e) {
                    const query = e.target.value.toLowerCase();
                    const navItems = document.querySelectorAll('.nav-item, .nav-submenu-item');

                    navItems.forEach(item => {
                        const text = item.textContent.toLowerCase();
                        const shouldShow = text.includes(query);

                        if (item.classList.contains('nav-item')) {
                            // Para itens principais, mostrar se o texto corresponde ou se algum submenu corresponde
                            const submenu = item.nextElementSibling;
                            if (submenu && submenu.classList.contains('nav-submenu')) {
                                const submenuItems = submenu.querySelectorAll('.nav-submenu-item');
                                const hasMatchingSubmenu = Array.from(submenuItems).some(subItem =>
                                    subItem.textContent.toLowerCase().includes(query)
                                );

                                item.style.display = (shouldShow || hasMatchingSubmenu || !query) ? 'flex' : 'none';

                                // Mostrar submenu se houver correspondência
                                if (hasMatchingSubmenu && query) {
                                    submenu.classList.remove('collapsed');
                                    item.classList.add('expanded');
                                    const chevron = item.querySelector('.nav-chevron');
                                    if (chevron) chevron.style.transform = 'rotate(90deg)';
                                }
                            } else {
                                item.style.display = shouldShow || !query ? 'flex' : 'none';
                            }
                        } else {
                            // Para itens do submenu
                            item.style.display = shouldShow || !query ? 'flex' : 'none';
                        }
                    });
                });
            </script>
            </body>

            </html>