import './bootstrap';

/* Bootstrap 5 */
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap-icons/font/bootstrap-icons.css';
import 'bootstrap';

/* Estilos propios de EDMA */
import '../css/app.css';

/* EDMA Portal */
import './portal/portal';

/* EDMA Website */
import './website/programs-home';
import './website/reveal';


/* =========================================================
   EDMA Website - Programas
   Interacción de niveles dentro de los modales
   ========================================================= */

document.addEventListener('DOMContentLoaded', () => {

    const levelRoutes = document.querySelectorAll(
        '.edma-modal-level-path'
    );

    levelRoutes.forEach((route) => {

        const nodes = route.querySelectorAll(
            '.edma-level-node'
        );

        const details = route.querySelectorAll(
            '.edma-level-detail'
        );

        nodes.forEach((node) => {

            node.addEventListener('click', () => {

                const targetId =
                    node.dataset.levelTarget;

                const target =
                    route.querySelector(`#${targetId}`);

                if (!target) {
                    return;
                }


                /* Desactivar niveles actuales */

                nodes.forEach((item) => {

                    item.classList.remove(
                        'is-active'
                    );

                    item.setAttribute(
                        'aria-expanded',
                        'false'
                    );

                });


                details.forEach((detail) => {

                    detail.classList.remove(
                        'is-active'
                    );

                });


                /* Activar nivel seleccionado */

                node.classList.add(
                    'is-active'
                );

                node.setAttribute(
                    'aria-expanded',
                    'true'
                );

                target.classList.add(
                    'is-active'
                );

            });

        });

    });

});