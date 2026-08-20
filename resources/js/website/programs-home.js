document.addEventListener('DOMContentLoaded', () => {

    const selectorButtons = document.querySelectorAll(
        '.edma-program-selector__button'
    );

    const showcase = document.querySelector(
        '.edma-program-showcase'
    );

    const title = document.getElementById(
        'edmaProgramTitle'
    );

    const description = document.getElementById(
        'edmaProgramDescription'
    );

    const image = document.getElementById(
        'edmaProgramImage'
    );

    const glassText = document.getElementById(
        'edmaProgramGlassText'
    );

    const index = document.getElementById(
        'edmaProgramIndex'
    );

    if (
        !selectorButtons.length ||
        !showcase ||
        !title ||
        !description ||
        !image
    ) {
        return;
    }


    const programs = {

        kids: {
            title: 'Inglés para niños',

            description:
                'Una experiencia de aprendizaje diseñada para desarrollar habilidades comunicativas desde edades tempranas mediante actividades prácticas, progresivas y apropiadas para su etapa.',

            image:
                image.dataset.kidsImage,

            glass:
                'Aprende mientras desarrollas confianza',

            index:
                '01'
        },

        adults: {
            title: 'Inglés para jóvenes y adultos',

            description:
                'Una formación progresiva orientada a fortalecer la comunicación, comprensión y uso práctico del inglés para estudios, trabajo y situaciones de la vida cotidiana.',

            image:
                image.dataset.adultsImage,

            glass:
                'Comunicación para nuevas oportunidades',

            index:
                '02'
        }
    };


    selectorButtons.forEach((button) => {

        button.addEventListener('click', () => {

            const selectedProgram =
                button.dataset.program;

            const data =
                programs[selectedProgram];

            if (!data) {
                return;
            }


            selectorButtons.forEach((item) => {

                item.classList.remove(
                    'is-active'
                );

                item.setAttribute(
                    'aria-selected',
                    'false'
                );

            });


            button.classList.add(
                'is-active'
            );

            button.setAttribute(
                'aria-selected',
                'true'
            );


            showcase.classList.add(
                'is-changing'
            );


            window.setTimeout(() => {

                title.textContent =
                    data.title;

                description.textContent =
                    data.description;

                image.src =
                    data.image;

                glassText.textContent =
                    data.glass;

                index.textContent =
                    data.index;


                showcase.classList.remove(
                    'is-changing'
                );

            }, 220);

        });

    });

});