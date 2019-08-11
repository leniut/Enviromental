<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class Widget_My_Custom_Elementor_Thing extends Widget_Base {

	public function get_name() {
		return 'my-weather-data';
	}
	public function get_title() {
		return __( 'Dane Pogodowe', 'elementor' );
	}
	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
		return 'fa fa-cloud';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_my_custom',
			[
				'label' => esc_html__( 'Kontrolka', 'elementor' ),
			]
		);
		
		
		$this->add_control(
			'icon',
			[
				'label' => __( 'Choose Icon', 'elementor' ),
				'type' => Controls_Manager::ICON,
				'default' => 'fa fa-star',
			]
		);

		$this->add_control(
			'title_text',
			[
				'label' => __( 'Title', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
			]
		);		

		$this->add_control(
			'data_combo',
			[
				'label' => __( 'Wyświetl Dane', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'temperature' => __( 'Temperatura', 'elementor' ),
					'pressure' => __( 'Ciśnienie', 'elementor' ),
					'rain' => __( 'Opady', 'elementor' ),
					'wind_speed' => __( 'Prędkość wiatru', 'elementor' ),
					'wind_dir' => __( 'Kierunek wiatru', 'elementor' ),
					'humidity' => __( 'Wilgotność', 'elementor' ),
					'leq' => __( 'Dźwięk', 'elementor' ),
					'pm1' => __( 'PM 1', 'elementor' ),
					'pm25' => __( 'PM 2.5', 'elementor' ),
					'pm10' => __( 'PM 10', 'elementor' ),
					'ch20_ppb' => __( 'Pył: ch20_ppb', 'elementor' ),
					'ch20_ug_m3' => __( 'Pył: ch20_ug_m3', 'elementor' ),	
					'lzo' => __( 'LZO', 'elementor' ),
				],
				'default' => 'temperature',
			]
		);


		$this->add_control(
			'unit_text',
			[
				'label' => __( 'Jednostka', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
			]
		);		
		
		$this->add_control(
            'icon_swith',
            [
                'label' => __( 'Wzrost/Spadek', 'elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
					'true' => [
						'title' => __( 'Yes', 'elementor' ),
						'icon' => 'fa fa-check',
					],
					'false' => [
						'title' => __( 'No', 'elementor' ),
						'icon' => 'fa fa-ban',
					]
				],
				'default' => 'false',
				'toggle' => false,
            ]
        );
				
		$this->add_control(
            'icon_show_date',
            [
                'label' => __( 'Pokaż Datę', 'elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
					'true' => [
						'title' => __( 'Yes', 'elementor' ),
						'icon' => 'fa fa-check',
					],
					'false' => [
						'title' => __( 'No', 'elementor' ),
						'icon' => 'fa fa-ban',
					]
				],
				'default' => 'false',
				'toggle' => false,
            ]
        );
				
		$this->add_control(
			'date_text',
			[
				'label' => __( 'Tytuł daty', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
			]
		);
		
		$this->add_control(
			'icon_date_format',
			[
				'label' => __( 'Format Daty (d-m-Y H:i:s)', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
			]
		);
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_my_custom',
			[
				'label' => __( 'Style', 'elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Text Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-weather-control' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .elementor-weather-control i,
								{{WRAPPER}} .elementor-weather-text',
			]
		);

		$this->add_responsive_control(
			'text_align',
			[
				'label' => __( 'Alignment', 'elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'elementor' ),
						'icon' => 'fa fa-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'elementor' ),
						'icon' => 'fa fa-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-weather-control' => 'text-align: {{VALUE}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'icon_align',
			[
				'label' => __( 'Icon Alignment', 'elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'middle',
				'options' => [
					'top' => [
						'title' => __( 'Top', 'elementor' ),
						'icon' => 'fa fa-arrow-up',
					],
					'middle' => [
						'title' => __( 'Middle', 'elementor' ),
						'icon' => 'fa fa-minus',
					],
					'bottom' => [
						'title' => __( 'Bottom', 'elementor' ),
						'icon' => 'fa fa-arrow-down',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-weather-control i' => 'vertical-align: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'text_space',
			[
				'label' => __( 'Odstęp tytułu', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-weather-data' => 'margin:0 calc({{SIZE}}{{UNIT}}/2)',
				],
			]
		);
		$this->add_control(
			'value_color',
			[
				'label' => __( 'Color Strzałki', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .wearther-value' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_value_axis',
			[
				'label' => __( 'Date', 'elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		
				$this->add_control(
			'date_color',
			[
				'label' => __( 'Text Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-weather-date' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'date_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .elementor-weather-date',
			]
		);

		$this->add_responsive_control(
			'date_align',
			[
				'label' => __( 'Alignment', 'elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'elementor' ),
						'icon' => 'fa fa-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'elementor' ),
						'icon' => 'fa fa-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-weather-date' => 'text-align: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_section();


	}
	protected function render( ) {
		$settings = $this->get_settings();
		$this->add_render_attribute( 'i', 'class', $settings['icon'] );
				
		$repo = new \Table_Repository(SHOW_TABLE_ALL);
		$value = $repo->get_single_var($settings['data_combo'],$settings['data_combo'].' IS NOT NULL','sync_date desc')??0;
		$date = '';
		if($settings['icon_show_date']=='true'){			
			$date = $repo->get_single_var('sync_date',$settings['data_combo'].' IS NOT NULL','sync_date desc')??0;
			if(isset($date) && !empty($settings['icon_date_format'])){
				$date = date($settings['icon_date_format'],strtotime($date));
			}
		}
		$switch='';
		if($settings['icon_swith']){
			$valuelast = $repo->get_single_var($settings['data_combo'],'','sync_date desc',1)??0;
		
			if($value<$valuelast){
				$switch = '<i class="wearther-value fa fa-caret-down text-danger" style="display:inline-block"></i>';
			}
			else if($value>$valuelast){		
				$switch = '<i class="wearther-value fa fa-caret-up text-success" style="display:inline-block"></i>';
			}
			else{
				$switch = '<i class="wearther-value fa fa-minus text-primary" style="display:inline-block"></i>';		
			}			
		}
		?>
		<div class="elementor-weather-control">
			<i <?php echo $this->get_render_attribute_string( 'i' ); ?>></i>
			<div class="elementor-weather-data" style="display: inline-block;vertical-align: middle;">
				<div class="elementor-weather-text">
					<span><?php echo $settings['title_text']; ?></span>
					<span><?php echo $value; ?></span>
					<span><?php echo $settings['unit_text']; ?></span>
				</div>	
				<?php if($settings['icon_show_date']=="true"){ ?>
					<div class="elementor-weather-date">
						<span><?php echo $settings['date_text']; ?></span>
						<span ><?php echo $date ?>
					</div>
				<?php }?>
			</div>
			<?php echo $switch ?> 
		</div>
		<?php

	}
	protected function content_template() {}
	public function render_plain_content( $instance = [] ) {}
}
Plugin::instance()->widgets_manager->register_widget_type( new Widget_My_Custom_Elementor_Thing() );