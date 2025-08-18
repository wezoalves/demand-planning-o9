            </div>
            </main>
            </div>

            <script>
                // Função de busca
                document.getElementById('searchInput').addEventListener('input', function(e) {
                    const query = e.target.value.toLowerCase();
                    const chapterGroups = document.querySelectorAll('.chapter-group');

                    chapterGroups.forEach(group => {
                        const chapterLink = group.querySelector('a');
                        const submenu = group.querySelector('.chapter-submenu');
                        const submenuLinks = submenu.querySelectorAll('a');

                        let hasMatch = false;

                        // Verificar se o título do capítulo corresponde
                        if (chapterLink.textContent.toLowerCase().includes(query)) {
                            hasMatch = true;
                        }

                        // Verificar links do submenu
                        submenuLinks.forEach(link => {
                            if (link.textContent.toLowerCase().includes(query)) {
                                hasMatch = true;
                                link.style.display = 'block';
                            } else {
                                link.style.display = query ? 'none' : 'block';
                            }
                        });

                        // Mostrar/ocultar grupo do capítulo
                        group.style.display = hasMatch || !query ? 'block' : 'none';
                    });
                });

                // Toggle submenu (opcional - para expandir/colapsar capítulos)
                document.querySelectorAll('.chapter-group > a').forEach(link => {
                    link.addEventListener('click', function(e) {
                        const submenu = this.nextElementSibling;
                        const icon = this.querySelector('.fa-chevron-down');

                        if (submenu && submenu.classList.contains('chapter-submenu')) {
                            e.preventDefault();
                            submenu.style.display = submenu.style.display === 'none' ? 'block' : 'none';
                            icon.classList.toggle('fa-chevron-down');
                            icon.classList.toggle('fa-chevron-right');
                        }
                    });
                });
            </script>
            </body>

            </html>