<?php
use \Elementor\Widget_Base;

class Widget_Elementor extends Widget_Base {

    // Defina o código do widget aqui.

    public function get_name() {
        return 'widget-elementor';
    }

    public function get_title() {
        return 'Widget Elementor';
    }

    public function get_icon() {
        return 'eicon-type-tool';
    }

    public function get_categories() {
        return [ 'basic' ];
    }

    protected function _register_controls() {
        // Seção de Conteúdo
        $this->start_controls_section(
            'content_section',
            [
                'label' => __( 'Conteúdo', 'plugin-elementor' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        // Controle de Texto
        $this->add_control(
            'texto',
            [
                'label' => __( 'Texto', 'plugin-elementor' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA, // Alterado para suportar HTML
                'default' => __( 'Texto padrão', 'plugin-elementor' ),
                'placeholder' => __( 'Digite seu texto aqui', 'plugin-elementor' ),
            ]
        );
        $this->add_control(
			'testimonial_alignment',
			[
				'label' => __( 'Alignment', 'plugin-elementor' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => [
					'left'    => [
						'title' => esc_html__( 'Left', 'elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'elementor' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-testimonial-wrapper' => 'text-align: {{VALUE}}',
				],
				'style_transfer' => true,
			]
		);

        // Controle de Data de Início
        $this->add_control(
            'data_inicio',
            [
                'label' => __( 'Data de Início', 'plugin-elementor' ),
                'type' => \Elementor\Controls_Manager::DATE_TIME,
                'default' => '',
            ]
        );

        // Controle de Data de Fim
        $this->add_control(
            'data_fim',
            [
                'label' => __( 'Data de Fim', 'plugin-elementor' ),
                'type' => \Elementor\Controls_Manager::DATE_TIME,
                'default' => '',
            ]
        );

        // Controle de Classe do Contêiner do Popup
        $this->add_control(
            'popup_container_class',
            [
                'label' => __( 'Classe do Popup', 'plugin-elementor' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'popup',
                'description' => __( 'Insira a classe CSS para o contêiner do popup.', 'plugin-elementor' ),
            ]
        );

        $this->end_controls_section();

        // Seção de Estilo
        $this->start_controls_section(
            'style_section',
            [
                'label' => __( 'Estilo', 'plugin-elementor' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Controle do Background Color
        $this->add_control(
            'background_color',
            [
                'label' => __( 'Cor do Fundo', 'plugin-elementor' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .popup' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // Controle do Text Color
        $this->add_control(
            'text_color',
            [
                'label' => __( 'Cor do Texto', 'plugin-elementor' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .popup' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Controle do Border Radius
        $this->add_control(
            'border_radius',
            [
                'label' => __( 'Border radius', 'plugin-elementor' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .popup' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Controle do Box Shadow
        $this->add_control(
            'box_shadow',
            [
                'label' => __( 'Sombreamento da Caixa', 'plugin-elementor' ),
                'type' => \Elementor\Controls_Manager::BOX_SHADOW,
                'selectors' => [
                    '{{WRAPPER}} .popup' => 'box-shadow: {{HORIZONTAL}} {{VERTICAL}} {{BLUR}} {{SPREAD}} {{COLOR}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings();

        // Obtém o valor da caixa de texto definido pelo usuário.
        $texto = ! empty( $settings['texto'] ) ? $settings['texto'] : '';

        // Obtém o valor do controle de estilo CSS para o popup.
        $popup_style = 'background-color: ' . $settings['background_color'] . ';';
        $popup_style .= ' text-align: ' . $settings['testimonial_alignment'] . ';';
        $popup_style .= ' color: ' . $settings['text_color'] . ';';
        $popup_style .= ' border-radius: ' . $settings['border_radius']['top'] . $settings['border_radius']['unit'] . ' ';
        $popup_style .= $settings['border_radius']['right'] . $settings['border_radius']['unit'] . ' ';
        $popup_style .= $settings['border_radius']['bottom'] . $settings['border_radius']['unit'] . ' ';
        $popup_style .= $settings['border_radius']['left'] . $settings['border_radius']['unit'] . ';';
        $popup_style .= ' box-shadow: ' . $settings['box_shadow']['horizontal'] . ' ' . $settings['box_shadow']['vertical'] . ' ';
        $popup_style .= $settings['box_shadow']['blur'] . ' ' . $settings['box_shadow']['spread'] . ' ' . $settings['box_shadow']['color'] . ';';

        // Obtém a classe do contêiner do popup definida pelo usuário.
        $popup_container_class = ! empty( $settings['popup_container_class'] ) ? $settings['popup_container_class'] : 'popup';

        // Verifica se o popup deve ser exibido de acordo com a data de início e fim.
        $display_popup = false;
        $data_inicio = ! empty( $settings['data_inicio'] ) ? $settings['data_inicio'] : '';
        $data_fim = ! empty( $settings['data_fim'] ) ? $settings['data_fim'] : '';

        if ( $data_inicio && $data_fim ) {
            $hoje = current_time( 'timestamp' );
            $data_inicio_timestamp = strtotime( $data_inicio );
            $data_fim_timestamp = strtotime( $data_fim );

            if ( $hoje >= $data_inicio_timestamp && $hoje <= $data_fim_timestamp ) {
                $display_popup = true;
            }
        }

        if ( $display_popup ) {
            // Formata a data de início para exibir como "dia, mês, ano e hora".
            $data_inicio_formatada = date_i18n( 'l, j F Y H:i', strtotime( $data_inicio ) );

            // Formata a data de fim para exibir como "dia, mês, ano e hora".
            $data_fim_formatada = date_i18n( 'l, j F Y H:i', strtotime( $data_fim ) );

            // Exibe o popup com o valor da caixa de texto na página, aplicando os estilos CSS personalizados.
            echo '<div class="' . $popup_container_class . '" style="' . $popup_style . '">';
            echo $texto;
            echo '</div>';
        }
    }

    protected function _content_template() {
        // Template do conteúdo do widget aqui (opcional).
    }
}
