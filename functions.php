<?php

/**
 * Theme setup.
 */
function tailpress_setup() {
	add_theme_support( 'title-tag' );

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'tailpress' ),
		)
	);

	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		)
	);

    add_theme_support( 'custom-logo' );
	add_theme_support( 'post-thumbnails' );

	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );

	add_theme_support( 'editor-styles' );
	add_editor_style( 'css/editor-style.css' );
}

add_action( 'after_setup_theme', 'tailpress_setup' );

/**
 * Enqueue theme assets.
 */
function tailpress_enqueue_scripts() {
	$theme = wp_get_theme();

	wp_enqueue_style( 'tailpress', tailpress_asset( 'css/app.css' ), array(), $theme->get( 'Version' ) );
	wp_enqueue_script( 'tailpress', tailpress_asset( 'js/app.js' ), array(), $theme->get( 'Version' ) );
}

add_action( 'wp_enqueue_scripts', 'tailpress_enqueue_scripts' );

/**
 * Get asset path.
 *
 * @param string  $path Path to asset.
 *
 * @return string
 */
function tailpress_asset( $path ) {
	if ( wp_get_environment_type() === 'production' ) {
		return get_stylesheet_directory_uri() . '/' . $path;
	}

	return add_query_arg( 'time', time(),  get_stylesheet_directory_uri() . '/' . $path );
}

/**
 * Adds option 'li_class' to 'wp_nav_menu'.
 *
 * @param string  $classes String of classes.
 * @param mixed   $item The current item.
 * @param WP_Term $args Holds the nav menu arguments.
 *
 * @return array
 */
function tailpress_nav_menu_add_li_class( $classes, $item, $args, $depth ) {
	if ( isset( $args->li_class ) ) {
		$classes[] = $args->li_class;
	}

	if ( isset( $args->{"li_class_$depth"} ) ) {
		$classes[] = $args->{"li_class_$depth"};
	}

	return $classes;
}

add_filter( 'nav_menu_css_class', 'tailpress_nav_menu_add_li_class', 10, 4 );

/**
 * Adds option 'submenu_class' to 'wp_nav_menu'.
 *
 * @param string  $classes String of classes.
 * @param mixed   $item The current item.
 * @param WP_Term $args Holds the nav menu arguments.
 *
 * @return array
 */
function tailpress_nav_menu_add_submenu_class( $classes, $args, $depth ) {
	if ( isset( $args->submenu_class ) ) {
		$classes[] = $args->submenu_class;
	}

	if ( isset( $args->{"submenu_class_$depth"} ) ) {
		$classes[] = $args->{"submenu_class_$depth"};
	}

	return $classes;
}

add_filter( 'nav_menu_submenu_css_class', 'tailpress_nav_menu_add_submenu_class', 10, 3 );




















add_filter('use_block_editor_for_post_type', '__return_false', 100);











// Função para criar tipos de post personalizados
function create_custom_post_types() {
    // Convidados Post Type
    register_post_type('convidados',
        array(
            'labels'      => array(
                'name'          => __('Convidados'),
                'singular_name' => __('Convidado'),
            ),
            'public'      => true,
            'has_archive' => true,
            'rewrite'     => array('slug' => 'convidados'),
            'supports'    => array(''),
        )
    );

    // Confirmados Post Type
    register_post_type('confirmados',
        array(
            'labels'      => array(
                'name'          => __('Confirmados'),
                'singular_name' => __('Confirmado'),
            ),
            'public'      => true,
            'has_archive' => true,
            'rewrite'     => array('slug' => 'confirmados'),
            'supports'    => array(''),
            'taxonomies'  => array('category'),
        )
    );
}
add_action('init', 'create_custom_post_types');

// Função para adicionar metaboxes
function add_convidados_metaboxes() {
    add_meta_box(
        'convidado_details',
        'Detalhes do Convidado',
        'render_convidado_metabox',
        'convidados',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_convidados_metaboxes');

// Função para renderizar o metabox
function render_convidado_metabox($post) {
    $name = get_post_meta($post->ID, 'name', true);
    $message = get_post_meta($post->ID, 'message', true);
    $token = get_post_meta($post->ID, 'token', true);

    // Segurança para o metabox
    wp_nonce_field(basename(__FILE__), 'convidado_nonce');

    ?>
    <p>
        <label for="name">Nome:</label>
        <input type="text" name="name" id="name" value="<?php echo esc_attr($name); ?>" class="widefat">
    </p>
    <p>
		<label for="message">Mensagem Personalizada:</label>
        <textarea rows="6" type="text" name="message" id="message" class="widefat"><?php echo esc_textarea($message); ?></textarea>
	</p>
    <p>
        <label for="token">Token:</label>
        <input type="text" name="token" id="token" value="<?php echo esc_attr($token); ?>" class="widefat" disabled readonly>
    </p>
    <?php
}








// Adicionar o metabox
function add_confirmados_metaboxes() {
    add_meta_box(
        'confirmado_convidado_details',
        'Detalhes do Convidado Relacionado',
        'render_confirmado_metabox',
        'confirmados',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_confirmados_metaboxes');

// Função para renderizar o metabox
function render_confirmado_metabox($post) {
    // Recupera o ID do convidado relacionado
    $convidado_id = get_post_meta($post->ID, 'convidado_id', true);
    // Recupera o título do post do convidado relacionado
    $convidado_title = get_the_title($convidado_id);

    // Segurança para o metabox
    wp_nonce_field(basename(__FILE__), 'confirmado_nonce');

    ?>
    <p>
        <label for="convidado_title">Convidado por:</label>
        <input type="text" id="convidado_title" value="<?php echo esc_attr($convidado_title); ?>" class="widefat" disabled readonly>
    </p>
    <?php
}

// Salvar o metabox (opcional, se precisar salvar algum dado adicional)
function save_confirmado_metabox($post_id) {
    // Verifica a segurança do nonce
    if (!isset($_POST['confirmado_nonce']) || !wp_verify_nonce($_POST['confirmado_nonce'], basename(__FILE__))) {
        return $post_id;
    }

    // Verifica a capacidade do usuário
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    // Salvar os dados aqui, se necessário
}
add_action('save_post', 'save_confirmado_metabox');










// Adicionar o metabox
function add_convidado_confirmed_ids_metabox() {
    add_meta_box(
        'convidado_confirmed_ids',
        'IDs dos Confirmados',
        'render_convidado_confirmed_ids_metabox',
        'convidados',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_convidado_confirmed_ids_metabox');

// Função para renderizar o metabox
function render_convidado_confirmed_ids_metabox($post) {
    // Recupera os IDs dos posts confirmados
    $confirmed_ids = get_post_meta($post->ID, 'confirmed_post_ids', true);
    
    if (!empty($confirmed_ids) && is_array($confirmed_ids)) {
        $confirmed_titles = array();

        foreach ($confirmed_ids as $confirmed_id) {
            $post_title = get_the_title($confirmed_id);
            if ($post_title) {
                // Decodifica as entidades HTML para exibir corretamente os caracteres especiais
                $confirmed_titles[] = html_entity_decode($post_title);
            }
        }

        if (!empty($confirmed_titles)) {
            $confirmed_titles_list = implode("\n", $confirmed_titles);
		} else {
            $confirmed_titles_list = 'Nenhum título de post confirmado encontrado.';
        }

    } else {
        $confirmed_titles_list = 'Nenhum post confirmado encontrado.';
    }

    ?>
    <p>
        <label for="confirmed_post_titles">Títulos dos Posts Confirmados:</label>
        <textarea id="confirmed_post_titles" class="widefat" rows="3" readonly><?php echo esc_textarea($confirmed_titles_list); ?></textarea>
    </p>
    <?php
}

// Função para salvar o metabox (se necessário)
function save_convidado_confirmed_ids_metabox($post_id) {
    // Verifica a segurança do nonce
    if (!isset($_POST['convidado_nonce']) || !wp_verify_nonce($_POST['convidado_nonce'], basename(__FILE__))) {
        return $post_id;
    }

    // Verifica a capacidade do usuário
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    // Salvar os dados aqui, se necessário
}
add_action('save_post', 'save_convidado_confirmed_ids_metabox');















// Função para salvar os metadados do convidado
function save_convidado_metabox($post_id) {
    // Verifica o nonce para garantir que a requisição é válida
    if (!isset($_POST['convidado_nonce']) || !wp_verify_nonce($_POST['convidado_nonce'], basename(__FILE__))) {
        return $post_id;
    }

    // Verifica a capacidade do usuário para editar o post
    if (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }

    // Se o post está sendo atualizado, não precisamos executar isso durante a atualização automática do título
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    // Se estamos no processo de atualização do título, não precisamos executar isso novamente
    if (isset($_POST['post_title']) && $_POST['post_title'] === sanitize_text_field($_POST['name']) . ' - #' . $post_id) {
        return $post_id;
    }

    // Atualiza o campo "name"
    $name = sanitize_text_field($_POST['name']);
    update_post_meta($post_id, 'name', $name);

    // Atualiza o campo "message"
    $message = sanitize_text_field($_POST['message']);
    update_post_meta($post_id, 'message', $message);

    // Verifica se o token já está gerado, se não, gera um novo token
    $token = get_post_meta($post_id, 'token', true);
    if (empty($token)) {
        $token = bin2hex(random_bytes(16));
        update_post_meta($post_id, 'token', $token);
    }

    // Atualiza o título do post concatenando o nome e o ID
    $post_title = $name . ' - #' . $post_id;
    remove_action('save_post', 'save_convidado_metabox'); // Evita loop infinito
    $post_data = array(
        'ID' => $post_id,
        'post_title' => $post_title,
    );
    wp_update_post($post_data);
    add_action('save_post', 'save_convidado_metabox'); // Restaura a ação
}
add_action('save_post', 'save_convidado_metabox');
















add_action('rest_api_init', function () {
    register_rest_route('confirmacao-presenca/v1', '/adicionar-confirmados', array(
        'methods' => 'POST',
        'callback' => 'adicionar_confirmados',
    ));
});

function adicionar_confirmados($request) {
    $params = $request->get_params();

    // Verifica e sanitiza os parâmetros
    if (!validate_params($params)) {
        return new WP_Error('invalid_data', 'Dados inválidos.', array('status' => 400));
    }

    $people = $params['people'];
    $token = sanitize_text_field($params['token']);

    // Identifica o post do convidado
    $convidado_id = get_convidado_id_by_token($token);
    if (!$convidado_id) {
        return new WP_Error('invalid_token', 'Token inválido.', array('status' => 400));
    }

    // Cria e atualiza os posts confirmados
    $ids = create_confirmed_posts($people, $convidado_id);

	// Salva os IDs dos posts confirmados no post do convidado
	update_post_meta($convidado_id, 'confirmed_post_ids', $ids);

    return $ids;
}

function validate_params($params) {
    return isset($params['people']) && is_array($params['people']) && isset($params['token']);
}

function get_convidado_id_by_token($token) {
    $query = new WP_Query(array(
        'meta_key' => 'token',
        'meta_value' => $token,
        'post_type' => 'convidados',
        'post_status' => 'publish'
    ));

    if ($query->have_posts()) {
        $query->the_post();
        return get_the_ID();
    }

    return false;
}

function create_confirmed_posts($people, $convidado_id) {
    $ids = array();

    foreach ($people as $nome) {
        $post_id = wp_insert_post(array(
            'post_title' => $nome, // Apenas o nome da pessoa no título
            'post_type' => 'confirmados',
            'post_status' => 'publish'
        ));

        if ($post_id) {
            // Atualiza o título do post para incluir o ID
            wp_update_post(array(
                'ID' => $post_id,
                'post_title' => $nome . ' - #' . $post_id
            ));

            // Adiciona o ID do convidado ao post confirmado
            add_post_meta($post_id, 'convidado_id', $convidado_id);

            $ids[] = $post_id;
        }
    }

    return $ids;
}















// Função para adicionar meta box
function adicionar_meta_box_convidados() {
    add_meta_box(
        'meta-box-convidados',
        'URL para confirmação de presença',
        'exibir_meta_box_convidados',
        'convidados',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'adicionar_meta_box_convidados');

// Função para exibir conteúdo da meta box
function exibir_meta_box_convidados($post) {
    // Verifica se o post possui o campo personalizado 'token'
    $token = get_post_meta($post->ID, 'token', true);
    
    if ($token) {
        // Constrói a URL com o token
        $url = esc_url(add_query_arg('token', $token, home_url('/confirme-sua-presenca/')));
        ?>
        
        <!-- Campo de URL com token -->
        <input type="text" id="token-url-input" value="<?php echo $url; ?>" readonly>
        <button class="button button-primary" id="copy-token-url">Copiar URL</button>

        <script>
        // Script para copiar a URL ao clicar no botão
        document.addEventListener('DOMContentLoaded', function() {
            var copyButton = document.getElementById('copy-token-url');
            var tokenUrlInput = document.getElementById('token-url-input');

            copyButton.addEventListener('click', function() {
                event.preventDefault();
                tokenUrlInput.select();
                document.execCommand('copy');
                // alert('URL copiada para a área de transferência.');
            });
        });
        </script>

        <?php
    } else {
        echo '<p>Não há token definido para este convidado.</p>';
    }
}
