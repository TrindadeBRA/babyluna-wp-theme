<?php
/*
 * Template Name: Confirme Sua Presença
 * Template Post Type: page
 */
?>

<?php get_header(); ?>




<?php
// Verifica se o token está presente na URL
if (isset($_GET['token'])) {
    // Obtém o token da URL
    $token = $_GET['token'];

    // Consulta o post pelo token
    $query = new WP_Query(
        array(
            'meta_key' => 'token',
            'meta_value' => $token,
            'post_type' => 'convidados',
        )
    );

    // Verifica se encontrou algum post com o token
    if ($query->have_posts()) {
        // Loop pelos posts encontrados
        while ($query->have_posts()) {
            $query->the_post();
            // Exibe o nome e a mensagem do convidado
            $name = get_post_meta(get_the_ID(), 'name', true);
            $message = get_post_meta(get_the_ID(), 'message', true);
            $message_default = "É com imensa alegria que convidamos vocês para o Chá de Bebê da Luna! Filha da Lavinia e do Lucas, a Luna está prestes a chegar para encher nossas vidas de amor e felicidade. "
            ?>

            <div>
                <div class="bg-[#F6CED8] py-28">
                    <div class="mx-auto container h-full flex flex-col justify-center items-center gap-y-8">
                        <h1 class="text-2xl lg:text-5xl text-center font-bold text-pink-800 mb-4">Olá
                            <?php echo esc_html($name); ?>!
                        </h1>
                        <h2 class="text-xl lg:text-3xl text-center text-pink-600">
                            <?php
                            if ($message) {
                                echo esc_html($message);
                            } else {
                                echo esc_html($message_default);
                            }
                            ?>
                        </h2>
                        <div class="flex items-center justify-center mt-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-pink-800 animate-bounce" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>


            <div>
                <div class="bg-[#fce4ea] py-28">
                    <div class="mx-auto container h-full flex flex-col justify-center items-center gap-y-8">
                        <h3 class="text-xl lg:text-3xl text-center font-medium text-pink-800 mb-4">Estamos emocionados em convidar
                            você para
                            celebrar conosco o Chá de Bebê da Luna! Por favor, preencha o formulário abaixo com o nome de todos que
                            irão participar, <span class="font-bold underline">incluindo os seus!</span></h3>

                    </div>


                    <div class="container mx-auto">

                        <!-- Formulário para adicionar pessoas -->
                        <form action="" method="post" class="mt-12" id="confirmation-form">
                            <div id="people-container" class="space-y-2">

                                <div class="flex flex-col-reverse lg:flex-row lg:justify-between lg:items-center mb-8">
                                    <h1 class="hidden lg:block text-lg text-left font-bold text-pink-800">Nomes completos</h1>
                                    <button type="button"
                                        class="bg-[#db2777] text-white px-3 py-2 rounded-md focus:outline-none focus:shadow-outline"
                                        onclick="addPerson()">Adicionar um novo nome!</button>
                                </div>


                                <div class="flex flex-col mb-2">
                                    <input type="text" name="people" value="<?php echo esc_html($name); ?>"
                                        placeholder="Nome da Pessoa"
                                        class="w-full border border-[#fff] font-bold text-lg bg-[#ffe7f2] rounded px-4 py-2">
                                </div>
                            </div>
                            <!-- Botão de envio -->
                            <button type="submit" class="bg-[#db2777] text-white px-4 py-2 rounded mt-12">Confirmar
                                presenças</button>
                        </form>





                        <script>
                            function addPerson() {
                                var container = document.getElementById('people-container');
                                var div = document.createElement('div');
                                div.className = 'flex items-center mb-2 gap-x-4';
                                var inputName = document.createElement('input');
                                inputName.type = 'text';
                                inputName.name = 'people';
                                inputName.placeholder = 'Nome completo da Pessoa';
                                inputName.className = 'w-full border border-[#fff] font-bold text-lg bg-[#ffe7f2] rounded px-4 py-2';

                                var button = document.createElement('button');
                                button.type = 'button';
                                button.className = 'bg-[#db2777] text-white px-3 py-2 rounded-full focus:outline-none focus:shadow-outline';
                                button.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 64 64" width="24px" height="24px" fill="#fff"><path d="M 28 6 C 25.791 6 24 7.791 24 10 L 24 12 L 23.599609 12 L 10 14 L 10 17 L 54 17 L 54 14 L 40.400391 12 L 40 12 L 40 10 C 40 7.791 38.209 6 36 6 L 28 6 z M 28 10 L 36 10 L 36 12 L 28 12 L 28 10 z M 12 19 L 14.701172 52.322266 C 14.869172 54.399266 16.605453 56 18.689453 56 L 45.3125 56 C 47.3965 56 49.129828 54.401219 49.298828 52.324219 L 51.923828 20 L 12 19 z M 20 26 C 21.105 26 22 26.895 22 28 L 22 51 L 19 51 L 18 28 C 18 26.895 18.895 26 20 26 z M 32 26 C 33.657 26 35 27.343 35 29 L 35 51 L 29 51 L 29 29 C 29 27.343 30.343 26 32 26 z M 44 26 C 45.105 26 46 26.895 46 28 L 45 51 L 42 51 L 42 28 C 42 26.895 42.895 26 44 26 z"/></svg>';
                                button.onclick = function () {
                                    container.removeChild(div);
                                };
                                div.appendChild(inputName);
                                div.appendChild(button);
                                container.appendChild(div);
                            }

                            document.getElementById('confirmation-form').addEventListener('submit', function (event) {

                                event.preventDefault(); // Previne o comportamento padrão do formulário

                                // Obtenha o formulário e crie um novo FormData
                                const form = document.getElementById('confirmation-form');
                                const formData = new FormData(form);

                                // Inicialize um array para armazenar os nomes das pessoas
                                const peopleArray = [];

                                // Itere sobre os campos 'people' no FormData
                                for (const pair of formData.entries()) {
                                    // Se o nome do campo for 'people', adicione o valor ao array
                                    if (pair[0] === 'people') {
                                        peopleArray.push(pair[1]);
                                    }
                                }

                                const urlParams = new URLSearchParams(window.location.search);
                                const token = urlParams.get('token');

                                // Crie um objeto com os dados no formato esperado pelo endpoint
                                const requestData = {
                                    people: peopleArray,
                                    token: token
                                };

                                // Converta o objeto em JSON
                                const jsonData = JSON.stringify(requestData);

                                console.log("XX>>", jsonData)

                                // Realiza a requisição AJAX para o endpoint personalizado
                                fetch('/wp-json/confirmacao-presenca/v1/adicionar-confirmados', {
                                    method: 'POST',
                                    body: jsonData,
                                    headers: {
                                        'Content-Type': 'application/json',
                                    }
                                })
                                    .then(response => {
                                        if (response.ok) {
                                            return response.json();
                                        }
                                        throw new Error('Ocorreu um erro durante a requisição AJAX.');
                                    })
                                    .then(data => {
                                        alert('IDs dos posts criados: ' + data.join(', ')); // Ajusta a forma como você lida com a resposta
                                    })
                                    .catch(error => {
                                        console.error(error.message);
                                    });
                            });
                        </script>

                    </div>

                </div>





                <div>
                    <div class="bg-[#F6CED8] py-28">
                        <div class="mx-auto container h-full flex flex-col justify-center items-center gap-y-8">

                            <h1 class="text-xl lg:text-3xl text-center font-bold text-pink-800 mb-4">Informações:</h1>

                            <div class="flex justify-normal items-center gap-x-6 w-[400px]">

                                <svg viewBox="0 0 8.4666669 8.4666669" id="svg8" version="1.1" class="h-12 w-12 text-pink-800"
                                    xmlns="http://www.w3.org/2000/svg" xmlns:cc="http://creativecommons.org/ns#"
                                    xmlns:dc="http://purl.org/dc/elements/1.1/"
                                    xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
                                    xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
                                    xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
                                    xmlns:svg="http://www.w3.org/2000/svg">
                                    <defs id="defs2" />
                                    <g id="layer1" transform="translate(0,-288.53332)">
                                        <path
                                            d="m 4.2324219,288.79688 c -1.6042437,0 -2.9101556,1.30591 -2.9101563,2.91015 -10e-7,2.82277 2.7460938,4.96875 2.7460938,4.96875 a 0.26460978,0.26460978 0 0 0 0.3300781,0 c 0,0 2.7460996,-2.14598 2.7460937,-4.96875 -3.4e-6,-1.60424 -1.3078657,-2.91015 -2.9121093,-2.91015 z m 0,0.52929 c 1.3182605,0 2.3828097,1.0626 2.3828125,2.38086 4.8e-6,2.30926 -2.0910618,4.13374 -2.3808594,4.38086 -0.2884142,-0.24588 -2.3828134,-2.0707 -2.3828125,-4.38086 5e-7,-1.31826 1.0625988,-2.38086 2.3808594,-2.38086 z"
                                            id="path929"
                                            style="color:#9d174d;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:medium;line-height:normal;font-family:sans-serif;font-variant-ligatures:normal;font-variant-position:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-alternates:normal;font-feature-settings:normal;text-indent:0;text-align:start;text-decoration:none;text-decoration-line:none;text-decoration-style:solid;text-decoration-color:#9d174d;letter-spacing:normal;word-spacing:normal;text-transform:none;writing-mode:lr-tb;direction:ltr;text-orientation:mixed;dominant-baseline:auto;baseline-shift:baseline;text-anchor:start;white-space:normal;shape-padding:0;clip-rule:nonzero;display:inline;overflow:visible;visibility:visible;opacity:1;isolation:auto;mix-blend-mode:normal;color-interpolation:sRGB;color-interpolation-filters:linearRGB;solid-color:#9d174d;solid-opacity:1;vector-effect:none;fill:#9d174d;fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:0.52916664;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;paint-order:stroke fill markers;color-rendering:auto;image-rendering:auto;shape-rendering:auto;text-rendering:auto;enable-background:accumulate" />

                                        <path
                                            d="m 4.2324219,290.38477 c -0.7274912,0 -1.3222633,0.59477 -1.3222657,1.32226 -4.5e-6,0.7275 0.5947697,1.32422 1.3222657,1.32422 0.727496,0 1.3242233,-0.59672 1.3242187,-1.32422 -2.3e-6,-0.72749 -0.5967275,-1.32226 -1.3242187,-1.32226 z m 0,0.52929 c 0.4415089,0 0.7949204,0.35146 0.7949219,0.79297 2.7e-6,0.44151 -0.35341,0.79492 -0.7949219,0.79492 -0.441512,0 -0.7929715,-0.35341 -0.7929688,-0.79492 1.4e-6,-0.44151 0.3514598,-0.79297 0.7929688,-0.79297 z"
                                            id="circle931"
                                            style="color:#9d174d;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:medium;line-height:normal;font-family:sans-serif;font-variant-ligatures:normal;font-variant-position:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-alternates:normal;font-feature-settings:normal;text-indent:0;text-align:start;text-decoration:none;text-decoration-line:none;text-decoration-style:solid;text-decoration-color:#9d174d;letter-spacing:normal;word-spacing:normal;text-transform:none;writing-mode:lr-tb;direction:ltr;text-orientation:mixed;dominant-baseline:auto;baseline-shift:baseline;text-anchor:start;white-space:normal;shape-padding:0;clip-rule:nonzero;display:inline;overflow:visible;visibility:visible;opacity:1;isolation:auto;mix-blend-mode:normal;color-interpolation:sRGB;color-interpolation-filters:linearRGB;solid-color:#9d174d;solid-opacity:1;vector-effect:none;fill:#9d174d;fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:0.52916664;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;paint-order:stroke fill markers;color-rendering:auto;image-rendering:auto;shape-rendering:auto;text-rendering:auto;enable-background:accumulate" />

                                    </g>

                                </svg>

                                <div>
                                    <h3 class="text-pink-600 text-lg font-bold">Condomínio Residencial Arco Iris</h3>
                                    <p class="text-pink-600 text-base font-medium">R. dos Vianas, 1545 - Vila Vianas</p>
                                    <p class="text-pink-600 text-base font-medium">São Bernardo do Campo - SP</p>
                                </div>

                            </div>

                            <div class="flex justify-normal items-center gap-x-6 w-[400px]">

                                <svg viewBox="0 0 24 24" fill="none" class="h-12 w-12 text-pink-800"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M3 9H21M7 3V5M17 3V5M11.9976 12.7119C11.2978 11.9328 10.1309 11.7232 9.25414 12.4367C8.37738 13.1501 8.25394 14.343 8.94247 15.1868C9.33119 15.6632 10.2548 16.4983 10.9854 17.1353C11.3319 17.4374 11.5051 17.5885 11.7147 17.6503C11.8934 17.703 12.1018 17.703 12.2805 17.6503C12.4901 17.5885 12.6633 17.4374 13.0098 17.1353C13.7404 16.4983 14.664 15.6632 15.0527 15.1868C15.7413 14.343 15.6329 13.1426 14.7411 12.4367C13.8492 11.7307 12.6974 11.9328 11.9976 12.7119ZM6.2 21H17.8C18.9201 21 19.4802 21 19.908 20.782C20.2843 20.5903 20.5903 20.2843 20.782 19.908C21 19.4802 21 18.9201 21 17.8V8.2C21 7.07989 21 6.51984 20.782 6.09202C20.5903 5.71569 20.2843 5.40973 19.908 5.21799C19.4802 5 18.9201 5 17.8 5H6.2C5.0799 5 4.51984 5 4.09202 5.21799C3.71569 5.40973 3.40973 5.71569 3.21799 6.09202C3 6.51984 3 7.07989 3 8.2V17.8C3 18.9201 3 19.4802 3.21799 19.908C3.40973 20.2843 3.71569 20.5903 4.09202 20.782C4.51984 21 5.07989 21 6.2 21Z"
                                        stroke="#9d174d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>

                                <div>
                                    <p class="text-pink-600 text-lg font-bold">20 de Julho de 2024</p>
                                    <p class="text-pink-600 text-base font-medium">Apartir das 15h</p>
                                </div>

                            </div>



                        </div>
                    </div>
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3653.2536751069124!2d-46.5384095!3d-23.702633499999994!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94ce4221037963af%3A0xbf20a1bf56c423db!2sR.%20dos%20Vianas%2C%201545%20-%20Baeta%20Neves%2C%20S%C3%A3o%20Bernardo%20do%20Campo%20-%20SP%2C%2009760-510!5e0!3m2!1spt-BR!2sbr!4v1719192752831!5m2!1spt-BR!2sbr"
                        width="100%" height="500" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>


            </div>





            <?php
        }
    } else {
        // Mensagem caso nenhum post seja encontrado com o token
        ?>
        <div class="w-screen h-screen flex justify-center items-center bg-red-100 text-red-700 p-4 rounded mb-4">
            <p>Nenhum convite encontrado com o token fornecido.</p>
        </div>
        <?php
    }

    // Restaura os dados originais do post
    wp_reset_postdata();
} else {
    // Mensagem caso o token não esteja presente na URL
    ?>
    <div class="w-screen h-screen flex justify-center items-center bg-yellow-100 text-yellow-700 p-4 rounded mb-4">
        <p>Por favor, forneça um token válido.</p>
    </div>
    <?php
}
?>





<?php get_footer(); ?>