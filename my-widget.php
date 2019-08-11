<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class Widget_My_Custom_Elementor_Thing extends Widget_Base {

	public function get_name() {
		return 'my-weather-data';
	}
	public function get_title() {
		return __( 'Dane Pogodowe ', 'elementor' );
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
				'label' => __( 'WyĹ›wietl Dane', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'temperature' => __( 'Temperatura', 'elementor' ),
					'pressure' => __( 'CiĹ›nienie', 'elementor' ),
					'rain' => __( 'Opady', 'elementor' ),
					'wind_speed' => __( 'PrÄ™dkoĹ›Ä‡ wiatru', 'elementor' ),
					'wind_dir' => __( 'Kierunek wiatru', 'elementor' ),
					'humidity' => __( 'WilgotnoĹ›Ä‡', 'elementor' ),
					'leq' => __( 'DĹşwiÄ™k', 'elementor' ),
					'pm1' => __( 'PM 1', 'elementor' ),
					'pm25' => __( 'PM 2.5', 'elementor' ),
					'pm10' => __( 'PM 10', 'elementor' ),
					'ch20_ppb' => __( 'PyĹ‚: ch20_ppb', 'elementor' ),
					'ch20_ug_m3' => __( 'PyĹ‚: ch20_ug_m3', 'elementor' ),	
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
                'label' => __( 'Wskaźnik', 'elementor' ),
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
				
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_date',
			[
				'label' => esc_html__( 'Data', 'elementor' ),
			]
		);

		$this->add_control(
            'icon_show_date',
            [
                'label' => __( 'Show', 'elementor' ),
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
				'label' => __( 'Title', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
			]
		);
		
		$this->add_control(
			'icon_date_format',
			[
				'label' => __( 'Format (d-m-Y H:i:s)', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
			]
		);
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_weather_icon',
			[
				'label' => esc_html__( 'Ikona', 'elementor' ),
			]
		);

		$this->add_control(
			'icon_heading',
			[
				'label' => __( 'Ikona', 'elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);
		$this->add_control(
			'icons_structure',
			[
				'label' => '',
				'type' => Controls_Manager::REPEATER,
				'prevent_empty' => false,
				'fields' => [	
					[
						'name' => 'icon_enable',
						'label' => __( 'Enable', 'elementor' ),
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
						'default' => 'true',
						'toggle' => false,
					],
					[
						'name' => 'icon_operator',
						'label' => __( 'Operacja', 'elementor' ),
						'type' => Controls_Manager::SELECT,
						'options' => [
							'lt' => __( 'Mniejsza', 'elementor' ),
							'le' => __( 'Mniejsza lub równa', 'elementor' ),
							'gt' => __( 'Większa', 'elementor' ),
							'ge' => __( 'Większa lub równa', 'elementor' ),
							'equals' => __( 'Równa', 'elementor' ),
							'ne' => __( 'Różna', 'elementor' ),
						],
						'default' => 'lt',
						
					],
					[
						'name' => 'icon_value',
						'label' => __( 'Niż wartość', 'elementor' ),
						'type' => Controls_Manager::NUMBER,
						'default' => '0',						
					],					
					[
						'name' => 'icon_icon',
						'label' => __( 'Choose Icon', 'elementor' ),
						'type' => Controls_Manager::ICON,
						'default' => '',
					],
					[
						'name'=>'icon_color',
						'label' => __( 'Color', 'elementor' ),
						'type' => Controls_Manager::COLOR,
						'scheme' => [
							'type' => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_1,
						],						
					],
				],
				'title_field' =>'<div style="background-color:{{icon_color}};width:10px;height:10px;display: inline-block;margin:0 5px"></div><span>&{{icon_operator}};{{icon_value}}</span>',
			]
		);
		

		$this->add_control(
			'indicator_heading',
			[
				'label' => __( 'Wskaźnik', 'elementor' ),
				'type' => Controls_Manager::HEADING,
				'condition'=>['icon_swith'=>'true']
			]
		);
		$this->add_control(
			'indicator_structure',
			[
				'show_label' => 'false',
				'prevent_empty' => false,
				'type' => Controls_Manager::REPEATER,
				'default'=>[
					[
					'indicator_enable'=>'true',
					'indicator_operator'=>'lt',
					'indicator_icon'=>'fa fa-caret-down',
					'indicator_color'=>'rgba(207,55,33,0.90)'
					],
					[
					'indicator_enable'=>'true',
					'indicator_operator'=>'gt',
					'indicator_icon'=>'fa fa-caret-up',
					'indicator_color'=>'rgba(35,164,85,0.9)'
					],
					[
					'indicator_enable'=>'true',
					'indicator_operator'=>'equals',
					'indicator_icon'=>'fa fa-minus',
					'indicator_color'=>'rgba(48,73,181,0.9)'
					]
				],
				'fields' => [	
					[
						'name' => 'indicator_enable',
						'label' => __( 'Enable', 'elementor' ),
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
						'default' => 'true',
						'toggle' => false,
					],
					[
						'name' => 'indicator_operator',
						'label' => __( 'Operacja', 'elementor' ),
						'type' => Controls_Manager::SELECT,
						'options' => [
							'lt' => __( 'Mniejsza', 'elementor' ),
							'le' => __( 'Mniejsza lub równa', 'elementor' ),
							'gt' => __( 'Większa', 'elementor' ),
							'ge' => __( 'Większa lub równa', 'elementor' ),
							'equals' => __( 'Równa', 'elementor' ),
							'ne' => __( 'Różna', 'elementor' ),
						],
						'default' => 'lt',
						
					],			
					[
						'name' => 'indicator_icon',
						'label' => __( 'Choose Icon', 'elementor' ),
						'type' => Controls_Manager::ICON,
						'default' => 'fa fa-minus',
					],
					[
						'name'=>'indicator_color',
						'label' => __( 'Color', 'elementor' ),
						'type' => Controls_Manager::COLOR,
						'scheme' => [
							'type' => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_1,
						],
						'default' => '#1e1e1e',
					],
				],
				'title_field' =>'<span>&{{indicator_operator}};</span> <i class="{{indicator_icon}}" style="color:{{indicator_color}};"></i>',
				
				'condition'=>['icon_swith'=>'true'],
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
				'label' => __( 'OdstÄ™p tytuĹ‚u', 'elementor' ),
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
				'label' => __( 'Color StrzaĹ‚ki', 'elementor' ),
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
		
		$this->add_render_attribute( 'i', 'style', 'font-family:Fontawesome;' );
		$repo = new \Table_Repository(SHOW_TABLE_ALL);
		$value = $repo->get_single_var($settings['data_combo'],$settings['data_combo'].' IS NOT NULL','sync_date desc')??0;
		$date = '';

		$icon_setted = false;
		if(!empty($settings['icons_structure'])){
		//icons_structure
		foreach($settings['icons_structure'] as $icon){	
			if($icon['icon_enable'] == 'false')
				continue;
			switch($icon['icon_operator']){
				case 'lt':
					if($value<$icon['icon_value']){
						$this->add_render_attribute( 'i', 'class', $icon['icon_icon']!=''?$icon['icon_icon']: $settings['icon'] );
						if($icon['icon_color']!='')
						$this->add_render_attribute( 'i', 'style', 'color:'.$icon['icon_color'] );
						$icon_setted=true;
					}
				break;
				case 'le':
					if($value<=$icon['icon_value']){
						$this->add_render_attribute( 'i', 'class', $icon['icon_icon']!=''?$icon['icon_icon']: $settings['icon'] );
						if($icon['icon_color']!='')
						$this->add_render_attribute( 'i', 'style', 'color:'.$icon['icon_color'] );
						$icon_setted=true;
					}
				break;
				case 'gt':
					if($value>$icon['icon_value']){
						$this->add_render_attribute( 'i', 'class', $icon['icon_icon']!=''?$icon['icon_icon']: $settings['icon'] );
						if($icon['icon_color']!='')
						$this->add_render_attribute( 'i', 'style', 'color:'.$icon['icon_color'] );
						$icon_setted=true;
					}
				break;
				case 'ge':
					if($value>=$icon['icon_value']){
						$this->add_render_attribute( 'i', 'class', $icon['icon_icon']!=''?$icon['icon_icon']: $settings['icon'] );
						if($icon['icon_color']!='')
						$this->add_render_attribute( 'i', 'style', 'color:'.$icon['icon_color'] );
						$icon_setted=true;
					}
				break;
				case 'equals':
					if($value==$icon['icon_value']){
						$this->add_render_attribute( 'i', 'class', $icon['icon_icon']!=''?$icon['icon_icon']: $settings['icon'] );
						if($icon['icon_color']!='')
						$this->add_render_attribute( 'i', 'style', 'color:'.$icon['icon_color'] );
						$icon_setted=true;
					}
				break;
				case 'ne':
					if($value!=$icon['icon_value']){
						$this->add_render_attribute( 'i', 'class', $icon['icon_icon']!=''?$icon['icon_icon']: $settings['icon'] );
						if($icon['icon_color']!='')
						$this->add_render_attribute( 'i', 'style', 'color:'.$icon['icon_color'] );
						$icon_setted=true;
					}
				break;
			}
			if($icon_setted==true)
				break;
		}
		}
		
		if($icon_setted==false){
			$this->add_render_attribute( 'i', 'class', $settings['icon'] );
		}
		
		if($settings['icon_show_date']=='true')
		{			
			date_default_timezone_set('Europe/Warsaw');

					
			$date = $repo->get_single_var('sync_date',$settings['data_combo'].' IS NOT NULL','sync_date desc')??0;
			if(isset($date) && !empty($settings['icon_date_format'])){
				$datewp = current_time( 'mysql' );
				$dateNow = date($settings['icon_date_format'],strtotime($datewp));
				$date = date($settings['icon_date_format'],strtotime('-1 hour',strtotime($date)));
				//$date = date($settings['icon_date_format'],strtotime($datewp));	

				$showDate = date($settings['icon_date_format']);
				
				if($dateNow > $date )
				{					
					$showDate = $date;
				}
			}
		}
		
		$switch='';
		if($settings['icon_swith']!='false'){
		//indicator_operator
			$valuelast = $repo->get_single_var($settings['data_combo'],'','sync_date desc',1)??0;
		
						$this->add_render_attribute( 'switch', 'class','wearther-value' );
						$this->add_render_attribute( 'switch', 'style','display:inline-block; font-family:Fontawesome;' );
		$indicator_setted = false;
		
		if(!empty($settings['indicator_structure'])){
		foreach($settings['indicator_structure'] as $indicator){	
			if($indicator['indicator_enable'] == 'false')
				continue;
			switch($indicator['indicator_operator']){
				case 'lt':
					if($value<$valuelast){
						$this->add_render_attribute( 'switch', 'class', $indicator['indicator_icon'] );
						if($indicator['indicator_color']!='')
						$this->add_render_attribute( 'switch', 'style', 'color:'.$indicator['indicator_color'] );
						$indicator_setted=true;
					}
				break;
				case 'le':
					if($value<=$valuelast){
						$this->add_render_attribute( 'switch', 'class', $indicator['indicator_icon'] );
						if($indicator['indicator_color']!='')
						$this->add_render_attribute( 'switch', 'style', 'color:'.$indicator['indicator_color'] );
						$indicator_setted=true;
					}
				break;
				case 'gt':
					if($value>$valuelast){
						$this->add_render_attribute( 'switch', 'class', $indicator['indicator_icon']);
						if($indicator['indicator_color']!='')
						$this->add_render_attribute( 'switch', 'style', 'color:'.$indicator['indicator_color'] );
						$indicator_setted=true;
					}
				break;
				case 'ge':
					if($value>=$valuelast){
						$this->add_render_attribute( 'switch', 'class', $indicator['indicator_icon'] );
						if($indicator['indicator_color']!='')
						$this->add_render_attribute( 'switch', 'style', 'color:'.$indicator['indicator_color'] );
						$indicator_setted=true;
					}
				break;
				case 'equals':
					if($value==$valuelast){
						$this->add_render_attribute( 'switch', 'class', $indicator['indicator_icon'] );
						if($indicator['indicator_color']!='')
						$this->add_render_attribute( 'switch', 'style', 'color:'.$indicator['indicator_color'] );
						$indicator_setted=true;
					}
				break;
				case 'ne':
					if($value!=$valuelast){
						$this->add_render_attribute( 'switch', 'class', $indicator['indicator_icon'] );
						if($indicator['indicator_color']!='')
						$this->add_render_attribute( 'switch', 'style', 'color:'.$indicator['indicator_color'] );
						$indicator_setted=true;
					}
				break;
			}
			if($indicator_setted==true)
				break;
		}
		}
			if($indicator_setted==false){
				if($value<$valuelast){
						$this->add_render_attribute( 'switch', 'class','fa fa-caret-down' );
					//$switch = '<i class="wearther-value fa fa-caret-down" style="display:inline-block; font-family:Fontawesome"></i>';
				}
				else if($value>$valuelast){		
						$this->add_render_attribute( 'switch', 'class','fa fa-caret-up' );
					//$switch = '<i class="wearther-value fa fa-caret-up" style="display:inline-block;font-family:Fontawesome"></i>';
				}
				else{
						$this->add_render_attribute( 'switch', 'class','fa fa-caret-minus' );
					//$switch = '<i class="wearther-value fa fa-minus" style="display:inline-block;font-family:Fontawesome"></i>';		
				}			
			}
		}
		?>
		<div class="elementor-weather-control">
			<?php if($settings['icon']!='' || $icon_setted): ?>
				<i <?php echo $this->get_render_attribute_string( 'i' ); ?>></i>
			<?php endif; ?>
			<div class="elementor-weather-data" style="display: inline-block;vertical-align: middle;">
				<div class="elementor-weather-text">
					<span><?php echo $settings['title_text']; ?></span>
					<span><?php echo $value; ?></span>
					<span><?php echo $settings['unit_text']; ?></span>
				</div>	
				<?php if($settings['icon_show_date']=="true"){ ?>
					<div class="elementor-weather-date">
						<span><?php echo $settings['date_text']; ?></span>
						<span ><?php echo $showDate; ?>
					</div>
				<?php }?>
			</div>
			<?php if($settings['icon_swith']!='false'): ?>
				<i <?php echo $this->get_render_attribute_string( 'switch' ); ?>></i>
			<?php endif; ?>
			<?php //echo $switch; ?> 
		</div>
		<?php

	}
	protected function content_template() {}
	public function render_plain_content( $instance = [] ) {}
}
Plugin::instance()->widgets_manager->register_widget_type( new Widget_My_Custom_Elementor_Thing() );