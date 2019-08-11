<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class Widget_Weather_Elementor_Thing extends Widget_Base {

	public function get_name() {
		return 'my-weather-datas';
	}
	public function get_title() {
		return __( 'Lista Danych Pogodowych', 'elementor' );
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
			'weather_data',
			[
				'label' => '',
				'type' => Controls_Manager::REPEATER,		
				'default'=>[
					'data_combo'=>'temperature'
				],
				'fields' => [
					[
						'name' => 'icon',
						'label' => __( 'Choose Icon', 'elementor' ),
						'type' => Controls_Manager::ICON,
						'default' => 'fa fa-star',
					],
					[
						'name' => 'title_text',
						'label' => __( 'Title', 'elementor' ),
						'type' => Controls_Manager::TEXT,
						'default' => '',
					],
					[
						'name' => 'data_combo',
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
					],
					[
						'name' => 'unit_text',
						'label' => __( 'Jednostka', 'elementor' ),
						'type' => Controls_Manager::TEXT,
						'default' => '',
					],	
					[
						'name' => 'icon_swith',
						'label' => __( 'Wzrost/Spadek', 'elementor' ),
						'type' => Controls_Manager::SWITCHER,
						'label_on' => __( 'Show', 'elementor' ),
						'label_off' => __( 'Hide', 'elementor' ),
						'default' => false,
						'return_value' => true,
					],
					[
						'name' => 'text_separator',
						'label' => __( 'Separator', 'elementor' ),
						'type' => Controls_Manager::TEXT,
						'default' => '',
					],
				],
				'title_field' =>'{{data_combo}}',
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
				'selector' => '{{WRAPPER}} .elementor-weather-control',
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
		$this->add_control(
			'space_between_separators',
			[
				'label' => __( 'Odstęp pomiędzy separatorami', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .weather-separator:not(:last-child)' => 'margin:0 calc({{SIZE}}{{UNIT}}/2)',
					'{{WRAPPER}} .weather-separator:last-child' => 'margin-left: calc({{SIZE}}{{UNIT}}/2)',
				],
			]
		);

		$this->end_controls_section();


	}
	protected function render( ) {
		$settings = $this->get_settings();		
		?>
		<div class="elementor-weather-control">
			<?php foreach($settings['weather_data'] as $data){
				//$this->add_render_attribute( 'i', 'class', $data['icon'] );
				
				$repo = new \Table_Repository(SHOW_TABLE_ALL);
				$value = $repo->get_single_var($data['data_combo'],$data['data_combo'].' IS NOT NULL','sync_date desc')??0;
		
				$switch='';
				if($data['icon_swith']){
					$valuelast = $repo->get_single_var($data['data_combo'],$data['data_combo'].' IS NOT NULL','sync_date desc',1)??0;
		
					if($value<$valuelast){
						$switch = '<i class="fa fa-caret-down"></i>';
					}
					else if($value>$valuelast){		
						$switch = '<i class="fa fa-caret-up"></i>';
					}
					else{
						$switch = '<i class="fa fa-minus"></i>';		
					}
				}
			?>
				<?php if(!empty($data['icon'])){?>
					<i class="fa <?php echo $data['icon']; ?>"></i>
				<?php } ?>
				<?php if(!empty($data['title_text'])){?>
					<span><?php echo $data['title_text']; ?></span>
				<?php } ?>
				<span><?php echo $value; ?></span>
				<?php if(!empty($data['unit_text'])){?>
					<span><?php echo $data['unit_text']; ?></span>
				<?php } ?>
				<?php if(!empty($switch)){?>
					<?php echo $switch ?>
				<?php } ?>
				<?php if(!empty($data['text_separator'])){?>
					<span class="weather-separator"><?php echo $data['text_separator']; ?></span>
				<?php } ?>
			<?php } ?>
		</div>
		<?php
	}
	protected function content_template() {}
	public function render_plain_content( $instance = [] ) {}
}
Plugin::instance()->widgets_manager->register_widget_type( new Widget_Weather_Elementor_Thing() );