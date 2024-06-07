<?php
/*
 * Template Name: Confirme Sua Presença
 * Template Post Type: page
 */
?>

<?php get_header(); ?>

<div class="container mx-auto p-4">
    
    <?php
    // Verifica se o token está presente na URL
    if (isset($_GET['token'])) {
        // Obtém o token da URL
        $token = $_GET['token'];
        
        // Consulta o post pelo token
        $query = new WP_Query(array(
            'meta_key' => 'token',
            'meta_value' => $token,
            'post_type' => 'convidados',
            ));
            
            // Verifica se encontrou algum post com o token
            if ($query->have_posts()) {
                // Loop pelos posts encontrados
                while ($query->have_posts()) {
                    $query->the_post();
                    // Exibe o nome e a mensagem do convidado
                    $name = get_post_meta(get_the_ID(), 'name', true);
                    $message = get_post_meta(get_the_ID(), 'message', true);
                ?>
                <h1 class="text-3xl font-bold mb-4">Olá <?php echo esc_html($name); ?> Confirme Sua Presença</h1>
                <div class="bg-gray-100 p-4 rounded mb-4">
                    <p>Mensagem para o Convidado: <?php echo esc_html($message); ?></p>
                </div>


                <!-- Formulário para adicionar pessoas -->
                <form action="" method="post" class="mb-4" id="confirmation-form">
                    <h1 class="text-3xl font-bold mb-4">Confirme a presença de quem vai! </h1>
                    <div id="people-container" class="space-y-2">
                        <div class="flex items-center mb-2">
                            <input type="text" name="people" placeholder="Nome da Pessoa" class="w-full border border-gray-300 rounded px-4 py-2 mr-2">
                            <button type="button" class="bg-green-500 text-white px-3 py-2 rounded-full focus:outline-none focus:shadow-outline" onclick="addPerson()">+</button>
                        </div>
                    </div>
                    <!-- Botão de envio -->
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded mt-4">Confirmar Presença</button>
                </form>

                <script>
                    function addPerson() {
                        var container = document.getElementById('people-container');
                        var div = document.createElement('div');
                        div.className = 'flex items-center mb-2';
                        var inputName = document.createElement('input');
                        inputName.type = 'text';
                        inputName.name = 'people';
                        inputName.placeholder = 'Nome da Pessoa';
                        inputName.className = 'w-full border border-gray-300 rounded px-4 py-2 mr-2';
                        
                        var button = document.createElement('button');
                        button.type = 'button';
                        button.className = 'bg-red-500 text-white px-3 py-2 rounded-full focus:outline-none focus:shadow-outline';
                        button.innerHTML = '-';
                        button.onclick = function() {
                            container.removeChild(div);
                        };
                        div.appendChild(inputName);
                        div.appendChild(button);
                        container.appendChild(div);
                    }

                    document.getElementById('confirmation-form').addEventListener('submit', function(event) {

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
                                'Content-Type': 'application/json',                            }
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
                
                <?php
            }
        } else {
            // Mensagem caso nenhum post seja encontrado com o token
            ?>
            <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                <p>Nenhum post encontrado com o token fornecido.</p>
            </div>
            <?php
        }
        
        // Restaura os dados originais do post
        wp_reset_postdata();
    } else {
        // Mensagem caso o token não esteja presente na URL
        ?>
        <div class="bg-yellow-100 text-yellow-700 p-4 rounded mb-4">
            <p>Por favor, forneça um token válido.</p>
        </div>
        <?php
    }
    ?>

    

</div>


<?php get_footer(); ?>
