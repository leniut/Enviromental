<?php
namespace Elementor;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'ELEMENTOR_ADDON_PATH', plugin_dir_path( __FILE__ ) );

class Widget_Chart_Elementor_Thing extends Widget_Base {

	public function get_name() {
		return 'my-chart';
	}
	public function get_title() {
		return __( 'wykres', 'elementor' );
	}
	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
		return 'fa fa-bar-chart';
	}
	public function get_categories() {
		return [ 'general' ];
	}
	protected function _register_controls() {
	
		$this->start_controls_section(
			'section_chart',
			[
				'label' => esc_html__( 'Wykres', 'elementor' ),
			]
		);
		
		$this->add_control(
			'title_text',
			[
				'label' => __( 'Tytuł', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
			]
		);
				
		$this->add_control(
            'chart_stock',
            [
                'label' => __( 'Stock', 'elementor' ),
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
			'chart_type_combo',
			[
				'label' => __( 'Typ', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'line' => __( 'Liniowy', 'elementor' ),
					'column' => __( 'Kolumny pionowe', 'elementor' ),
					'bar' => __( 'Kolumny poziome', 'elementor' ),
					'area' => __( 'Area', 'elementor' ),
					'pie' => __( 'Koło', 'elementor' ),
					'bullet' => __( 'bullet', 'elementor' ),
				],
				'default' => 'line',
			]
		);
		
		$this->add_control(
			'chart_legend_position',
			[
				'label' => __( 'Położenie legendy', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'bottom' => __( 'Na dole', 'elementor' ),
					'top' => __( 'Na górze', 'elementor' ),
					'right' => __( 'Po prawej', 'elementor' ),
					'left' => __( 'Po lewej', 'elementor' ),
				],
				'default' => 'bottom',
			]
		);
		
		$this->add_control(
            'chart-justify',
            [
                'label' => __( 'Rozciagnij Oś', 'elementor' ),
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
				'condition' => [
					'chart_stock' => 'false',
				],
            ]
        );
		
		$this->add_control(
			'chart_gap',
			[
				'label' => __( 'Odległość między kolumnami z zewnątrz (Gap)', 'elementor' ),
				'type' => Controls_Manager::NUMBER,				
				'default' => '1',
				'condition' => [
					'chart_type_combo' => ['column','bar'],
				],
			]
		);
		
		$this->add_control(
			'chart_spacing',
			[
				'label' => __( 'Odległość między kolumnami wewnątrz (Spacing)', 'elementor' ),
				'type' => Controls_Manager::NUMBER,				
				'default' => '1',
				'condition' => [
					'chart_type_combo' => ['column','bar'],
				],
			]
		);
					
		$this->add_control(
			'chart_height',
			[
				'label' => __( 'Height', 'elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => '400',
			]
		);

		$options = array(	'temperature' => __( 'Temperatura', 'elementor' ),
							'pressure' => __( 'Ciśnienie', 'elementor' ),
							'rain' => __( 'Opady', 'elementor' ),
							'wind_speed' => __( 'Prędkość wiatru', 'elementor' ),
							'wind_dir' => __( 'Kierunek wiatru', 'elementor' ),
							'humidity' => __( 'Wilgotność', 'elementor' ),
							'leq' => __( 'Dźwięk', 'elementor' ),
							'pm1' => __( 'PM 1', 'elementor' ),
							'pm25' => __( 'PM 2.5', 'elementor' ),
							'pm10' => __( 'PM 10', 'elementor' ),
							'ch20_ppb' => __( 'Pył: CH20 PPB', 'elementor' ),
							'ch20_ug_m3' => __( 'Pył: CH20 UG M3', 'elementor' ),
							'lzo' => __( 'LZO', 'elementor' ),
							'date' => __( 'Data', 'elementor' )
						);
							
		$this->add_control(
			'chart_list',
			[
				'label' => '',
				'type' => Controls_Manager::REPEATER,		
				'default'=>[
					'group'=>'temperature',
					'color'=>'red',
					'chart_line_type_single'=>'',
					'chart_line_style_single'=>'',
					'value_axis_name'=>'base',
					'show_labels'=>'false',
					'labels_template'=>''
				],
				'fields' => [
					[
						'name' => 'axis_label',
						'label' => __( 'Nazwa', 'elementor' ),
						'label_block' => true,
						'type' =>Controls_Manager::TEXT,
						'default' => '#= value #',
					],
					[
						'name' => 'axis_name',
						'label' => __( 'Nazwa parametru', 'elementor' ),
						'label_block' => true,
						//'type' => Controls_Manager::TEXT, <----------------------for global
						'type' => Controls_Manager::HIDDEN,
						'default' => 'temperature',
					],
					[
						'name' => 'value_axis_name',
						'label' => __( 'Axis name', 'elementor' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'placeholder' => __( 'enter base name', 'elementor' ),
						'default' => __( 'base', 'elementor' ),
					],					
					[
						'name' => 'group',//<----------------------to delete for global
						'label' => __( 'Nazwa parametru', 'elementor' ),
						'type' => Controls_Manager::SELECT,
						'options' => $options,
						'default' => 'temperature',
						'label_block' => true,
					],	
					[
						'name' => 'color',
						'label' => __( 'Color', 'elementor' ),
						'type' => Controls_Manager::COLOR,
						'scheme' => [
							'type' => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_1,
						]
					],
					[
						'name' => 'chart_line_type_single',
						'label' => __( 'Typ', 'elementor' ),
						'type' => Controls_Manager::SELECT,
						'options' => [
							'' => __( 'Domyślny', 'elementor' ),
							'line' => __( 'Liniowy', 'elementor' ),
							'column' => __( 'Kolumny pionowe', 'elementor' ),
							'bar' => __( 'Kolumny poziome', 'elementor' ),
							'area' => __( 'Area', 'elementor' ),
							'pie' => __( 'Koło', 'elementor' ),
							'bullet' => __( 'bullet', 'elementor' ),
						],
						'default' => '',
					],	
					[
						'name' => 'chart_line_style_single',
						'label' => __( 'Styl linii', 'elementor' ),
						'type' => Controls_Manager::SELECT,
						'options' => [
							'' => __( 'Domyślny', 'elementor' ),
							'normal' => __( 'Normalne', 'elementor' ),
							'smooth' => __( 'Wygładzone', 'elementor' ),
							'step' => __( 'Skokowe', 'elementor' ),
						],
						'default' => '',
					],
					[
						'name' => 'show_labels',
						'label' => __( 'Pokaż wartości', 'elementor' ),
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
					],
					[
						'name'=>'labels_color',
						'label' => __( 'Kolor etykier', 'elementor' ),
						'type' => Controls_Manager::COLOR,
						'scheme' => [
							'type' => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_1,
						],							
					],
					[
						'name'=>'labels_backgrouns_color',
						'label' => __( 'Kolor tła etykiet', 'elementor' ),
						'type' => Controls_Manager::COLOR,
						'scheme' => [
							'type' => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_1,
						],
					],
					[
						'name' => 'labels_template',
						'label' => __( 'Template', 'elementor' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => false,
						'placeholder' => __( 'enter template', 'elementor' ),
						'default' => __( '#= value #', 'elementor' ),
					],	
					[
						'name'=> 'labels_border',
						'label' => __( 'Border Width', 'elementor' ),
						'type' => Controls_Manager::SLIDER,
						'range' => [
							'px' => [
								'min' => 0,
								'max' =>5,
							],
						],
						//'default' => ['unit'=>'px','size'=>'0'],						
					],
					[
						'name'=> 'labels_border_color',
						'label' => __( 'Border Color', 'elementor' ),
						'type' => Controls_Manager::COLOR,
						'scheme' => [
							'type' => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_1,
						],
						'default'=>'black'
					]
				],
				'title_field' =>'<div style="background-color:{{color}};width:10px;height:10px;display: inline-block;margin:0 5px"></div><span>{{axis_label}}</span>',
			]
		);

	/*	$this->add_control(
			'show_legend',
			[
			'label' => __( 'Show Legend', 'elementor' ),
			'type' => Controls_Manager::BOOLEAN,
			'default' => true,
			]
		);
		*/

		$this->end_controls_section();	
		
		//sekcja nagigatora
		$this->start_controls_section(
			'section_navigator',
			[
				'label' => __( 'Navigator', 'elementor' ),				
				'condition' => [
					'chart_stock!' => 'false',
				],
			]
		);
		

		$this->add_control(
			'chart_navigator_type_combo',
			[
				'label' => __( 'Type', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'line' => __( 'Linear', 'elementor' ),
					'column' => __( 'Vertical columns', 'elementor' ),
					'bar' => __( 'Horizontal columns', 'elementor' ),
					'area' => __( 'Area', 'elementor' ),
					//'pie' => __( 'Pie', 'elementor' ),
				//	'bullet' => __( 'Bullet', 'elementor' ),
				],
				'default' => 'area',
			]
		);

		/*$this->add_control(
			'navigator_axis_name',
			[
				'label' => __( 'Axis name', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => $options,
				'default' => 'temperature',
				'label_block' => true,
			]
		);*/
		$this->add_control(
			'navigator_color',
			[
				
				'label' => __( 'Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				]
			]
		);
		
		$this->add_control(
			'navigator_axis_to_now',
			[
				'label' => __( 'Zaznaczenie do dziś', 'elementor' ),
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
			]
		);


		$this->add_control(
			'navigator_axis_total',
			[
				'label' => __( 'Ilość zaznaczonych', 'elementor' ),
				'type' => Controls_Manager::TEXT, 
				'default' =>'14',
				'condition' => [
					'navigator_axis_to_now!' => 'false',
				],
			]
		);
		
		$this->add_control(
			'navigator_axis_time_type',
			[
				'label' => __( 'Zaznaczonych', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'hours' => __( 'Godzin', 'elementor' ),
					'days' => __( 'Dni', 'elementor' ),
					'months' => __( 'Miesięcy', 'elementor' ),
					'years' => __( 'Lat', 'elementor' ),
				],
				'default' => 'days',
				'condition' => [
					'navigator_axis_to_now!' => 'false',
				],
			]
		);

		$this->add_control(
			'navigator_axis_from',
			[
				'label' => __( 'Zaznaczenie od', 'elementor' ),
				'type' => Controls_Manager::TEXT, 
				'default' =>date("Y/m/d", strtotime("-1 months")),
				'condition' => [
					'navigator_axis_to_now' => 'false',
				],
			]
		);

		$this->add_control(
			'navigator_axis_to',
			[
				'label' => __( 'Zaznaczenie do', 'elementor' ),
				'type' => Controls_Manager::TEXT, 
				'default' =>date("Y/m/d", strtotime("+1 months")),				
				'condition' => [
					'navigator_axis_to_now' => 'false',
				],
			]
		);
		
		$this->add_control( 'show_navigator_labels',
			[
				'label' => __( 'Pokaż etykiety', 'elementor' ),
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
			]			
		);
		
		$this->add_control(
			'navigator_format',
			[
				'label' => __( 'Format', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => '{0:dd-MM-yyyy HH:mm}',
			]
		);
		
		$this->add_control(
			'navigator_lable_padding',
			[
				'label' => __( 'Padding', 'elementor' ),
				'type' => Controls_Manager::NUMBER,			
				'default' => '0',
			]
		);
		
		$this->end_controls_section();	

		// sekcja osi
		$this->start_controls_section(
			'section_value_axis',
			[
				'label' => __( 'Oś wartości', 'elementor' ),
			]
		);

			$this->add_control(
			'axis_controll',
			[
				'label' => '',
				'type' => Controls_Manager::REPEATER,
				'default' => [
					[
						'value_axis_name' => 'base',
						'value_axis_title'=>'',
						'value_axis_format'=>'',
						'value_axis_position'=>'0',
						//'value_axis_plot_enable'=>'false',
						//'value_axis_plot_color'=>'green',
						//'value_axis_plot_from'=>'0',
						//'value_axis_plot_to'=>'0',
						'show_axis_labels'=>'true',
						'value_axis_group'=>'date'
					],

				],
				'fields' => [
					[
						'name' => 'value_axis_name',
						'label' => __( 'Axis name', 'elementor' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'placeholder' => __( 'enter base name', 'elementor' ),
						//'default' => __( 'base', 'elementor' ),
					],
					[
						'name' => 'value_axis_title',
						'label' => __( 'Axis title', 'elementor' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'placeholder' => __( 'enter title or leave empty', 'elementor' ),
						'default' => __( '', 'elementor' ),
					],
					[
						'name' => 'value_axis_format',
						'label' => __( 'Label format', 'elementor' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'placeholder' => __( 'enter format np. {0} C', 'elementor' ),
						'default' => __( '', 'elementor' ),
					],
					[
						'name' => 'show_axis_labels',
						'label' => __( 'Pokaż etykiety', 'elementor' ),
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
						'name' => 'value_axis_position',
						'label' => __( 'Axis position', 'elementor' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'placeholder' => __( 'enter number', 'elementor' ),
						'default' => __( '0', 'elementor' ),
					],
				/*	[
						'name' => 'value_axis_plot_enable',
						'label' => __( 'Plot enable', 'elementor' ),
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
					],
					[
						'name' => 'value_axis_plot_color',
						'label' => __( 'Plot color', 'elementor' ),
						'type' => Controls_Manager::COLOR,
						'scheme' => [
							'type' => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_1,
						]
					],
					[
						'name' => 'value_axis_plot_from',
						'label' => __( 'Plot from', 'elementor' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'placeholder' => __( 'enter number', 'elementor' ),
						'default' => __( '0', 'elementor' ),
					],
					[
						'name' => 'value_axis_plot_to',
						'label' => __( 'Plot to', 'elementor' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'placeholder' => __( 'enter number', 'elementor' ),
						'default' => __( '0', 'elementor' ),
					],*/
				],
				'title_field' => '<div>{{ value_axis_name }}</div>',
			]
		);
		
		
		$this->end_controls_section();	
		
		// sekcja plot
		$this->start_controls_section(
			'section_plots',
			[
				'label' => __( 'Przedziały', 'elementor' ),
			]
		);

								
		$this->add_control(
			'plot_list',
			[
				'label' => '',
				'type' => Controls_Manager::REPEATER,		
				'default'=>[
					'value_axis_plot_enable'=>'false',
					'value_axis_plot_color'=>'red',
					'value_axis_plot_from'=>'0',
					'value_axis_plot_to'=>'50',
					'value_axis_plot_opacity'=>100,
				],
				'fields' => [
						[
						'name' => 'value_axis_plot_enable',
						'label' => __( 'Plot enable', 'elementor' ),
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
						'name' => 'value_axis_plot_from',
						'label' => __( 'Plot from', 'elementor' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'placeholder' => __( 'enter number', 'elementor' ),
						'default' => __( '0', 'elementor' ),
					],
					[
						'name' => 'value_axis_plot_to',
						'label' => __( 'Plot to', 'elementor' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'placeholder' => __( 'enter number', 'elementor' ),
						'default' => __( '0', 'elementor' ),
					],
					[
						'name' => 'value_axis_plot_color',
						'label' => __( 'Plot color', 'elementor' ),
						'type' => Controls_Manager::COLOR,
						'scheme' => [
							'type' => Scheme_Color::get_type(),
							'value' => Scheme_Color::COLOR_1,
						]
					],
					[
						'name' => 'value_axis_plot_opacity',
						'label' => __( 'Plot opaciity', 'elemetor' ),
						'type' => Controls_Manager::SLIDER,
						'range' => [
							'px' => [
								'min' => 0,
								'max' =>100,
							],
						],
						//'label_block' => true,
						//'placeholder' => __( 'enter number', 'elementor' ),
						'default' => ['unit'=>'px','size'=>'100'],
					],
				],
				//'title_field' =>'<div style="background-color:{{color}};width:10px;height:10px;display: inline-block;margin:0 5px"></div><span>{{axis_label}}</span>',
			]
		);
		
		$this->end_controls_section();	
		
		// Specyficzne dla wykresu liniowego - wartości domyślne
		$this->start_controls_section(
			'section_line_chart_controll',
			[
				'label' => __( 'Opcje wykresu liniowego', 'elementor' ),
			]
		);
		
			$this->add_control(
				'chart_line_style',
				[
					'label' => __( 'Styl linii', 'elementor' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'normal' => __( 'Normalne', 'elementor' ),
						'smooth' => __( 'Wygładzone', 'elementor' ),
						'step' => __( 'Skokowe', 'elementor' ),
					],
					'default' => 'normal',
				]
			);
		
			$this->add_control(
				'chart_line_type',
				[
					'label' => __( 'Typ linii', 'elementor' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'solid' => __( 'Pełne', 'elementor' ),
						'dot' => __( 'Kropkowane', 'elementor' ),
						'longdash' => __( 'Długie linie', 'elementor' ),
						'dashdot' => __( 'Linia - kropka', 'elementor' ),
						'longdashdot' => __( 'Długie linie - kropka', 'elementor' ),
						'longdashdotdot' => __( 'Długie linie - kropka - kropka', 'elementor' ),
					],
					'default' => 'solid',
				]
			);
		$this->end_controls_section();	

		// Tooltip
		$this->start_controls_section(
			'section_tooltip',
			[
				'label' => __( 'Tooltip', 'elementor' ),
			]
		);
			$this->add_control(
            'show_tooltip',
            [
                'label' => __( 'Pokaż tooltip', 'elementor' ),
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
            ]
			);

			$this->add_control(
            'show_tooltip_shared',
            [
                'label' => __( 'Grupowy tooltip', 'elementor' ),
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
				'show_tooltip_format',
				[
					'label' => __( 'Format', 'elementor' ),
					'type' => Controls_Manager::TEXT,
					'default' => '{0}',
				]
			);
		
			$this->add_control(
				'show_tooltip_template',
				[
					'label' => __( 'Template', 'elementor' ),
					'type' => Controls_Manager::TEXT,
					'default' => '#= series.name #: #= value #',
				]
			);
			
		$this->end_controls_section();	

		$this->start_controls_section(
			'section_admin',
			[
				'label' => __( 'Kategorie', 'elementor' ),
			]
		);

		/*	$this->add_control(
				'datasource_url',
				[
					'label' => __( 'Url', 'elementor' ),
					'type' => Controls_Manager::TEXT,
					'default' => '{0}',
				]
			);*/
		
		$this->add_control(
			'category_axis',
			[
				'label' => __( 'Kategoria', 'elementor' ),
				'type' => Controls_Manager::TEXT, //<----------------------for global
				//'type' => Controls_Manager::HIDDEN,
				'default' => 'date',
			]
		);
		$this->add_control(
			'category_axis_type',
			[
				'label' => __( 'Typ Kategorii', 'elementor' ),
				'type' => Controls_Manager::SELECT,
						'options' => [
							'string'=>__('String','elementor'),
							'number'=>__('Number','elementor'),
							'date'=>__('Date','elementor'),
						],
				'default' => 'date',
			]
		);
		$this->add_control(
			'category_axis_sort',
			[
				'label' => __( 'Sortowanie', 'elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'asc' => [
						'title' => __( 'ASC', 'elementor' ),
						'icon' => 'fa fa-sort-amount-asc',
					],
					'desc' => [
						'title' => __( 'DESC', 'elementor' ),
						'icon' => 'fa fa-sort-amount-desc'
					]
				],
				'default' => 'asc',
				'toggle' => false,
			]
		);
		$this->add_control(
			'category_top',
			[
				'label' => __( 'Pobrana ilość wyników', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => '24',
			]
		);
		
		
		$this->add_control(
				'category_display',
				[
					'label' => __( 'Widoczność wyników', 'elementor' ),
					'type' => Controls_Manager::TEXT,
					'default' => '6',
				]
			);
			

		$this->add_control( 'show_cat_labels',
			[
				'label' => __( 'Pokaż etykiety kategorii', 'elementor' ),
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
			]			
		);

		$this->add_control(
			'category_format',
			[
				'label' => __( 'Format Kategorii', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => '{0:dd-MM-yyyy HH:mm}',
			]
		);

		$this->add_control( 'category_helper_line',
			[
				'label' => __( 'Linia pomocnicza', 'elementor' ),
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
            'chart_labels_below',
            [
                'label' => __( 'Przykleić Etykiety na spód', 'elementor' ),
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
            ]
        );
		$this->end_controls_section();	
		$this->start_controls_section(
			'section_chart_filters',
			[
				'label' => esc_html__( 'Filters', 'elementor' ),
			]
		);
		$this->add_control(
            'chart_filters',
            [
                'label' => __( 'Pokaż filtry', 'elementor' ),
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
			'filter_format',
			[
				'label' => __( 'Format', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => '{0:dd-MM-yyyy HH:mm}',
			]
		);
		$this->add_control(
			'filter_position',
			[
				'label' => __( 'Pozycja filtra', 'elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'top',
				'options' => [
					'top' => [
						'title' => __( 'Top', 'elementor' ),
						'icon' => 'fa fa-level-up',
					],
					'bottom' => [
						'title' => __( 'Bottom', 'elementor' ),
						'icon' => 'fa fa-level-down',
					],
				],
				'toggle' => false,
			]
		);
		$this->add_control(
            'show_btn',
            [
                'label' => __( 'Pokaż przycisk', 'elementor' ),
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
            ]
        );
		$this->add_control(
			'filter_title_from',
			[
				'label' => __( 'Tytuł od', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
			]
		);
		$this->add_control(
			'filter_title_to',
			[
				'label' => __( 'Tytuł do', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
			]
		);

		$this->add_control(
			'filter_title_color',
			[
				'label' => __( 'Text Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .filter-title' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .filter-title',
			]
		);
		
		$this->add_control(
			'title_position',
			[
				'label' => __( 'Title Position', 'elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'block',
				'options' => [
					'inline-block' => [
						'title' => __( 'Left', 'elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'block' => [
						'title' => __( 'Top', 'elementor' ),
						'icon' => 'fa fa-align-center',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .filter-title' => 'display: {{VALUE}};    vertical-align: middle;',
				],
				'toggle' => false,
			]
		);
		$this->end_controls_section();	
		
	}
	function get_filters($settings){
		$html =  '<div class="chart_filters">
			<div style="display:inline-block">
			<div class="filter-title">'.$settings['filter_title_from'].'</div>
			 <input id="chart-filter-'.$this->get_id().'" title="monthpicker" /> 
			 </div>
			<div style="display:inline-block">
			<div class="filter-title">'.$settings['filter_title_to'].'</div>		
			 <input id="chart-filter-to-'.$this->get_id().'" title="monthpicker" />
			 </div>';
		if($settings['show_btn'] =='true'){ 
			$html .= '<button id="btn-'.$this->get_id().'" class="k-button k-primary" type="button">'. __('Reset','elementor').'</button>';
		}
		$html .=  '</div>';		
		return $html;
	}
	protected function render( ) {
		$settings = $this->get_settings();
		
		if($settings['chart_filters']=='true' && $settings['filter_position']=='top'){
			echo $this->get_filters($settings);
		 } 

		 $addDate = 6;
		 if($settings['navigator_axis_to_now'] == 'true'){
			 $multiplyDate = 1;
			 switch($settings['navigator_axis_time_type']){
		 		 case 'days':
					$multiplyDate = 24;
					break;
				 case 'months':
					$multiplyDate = 24*30;
					break;
				 case 'years':
					$multiplyDate = 24*30*12;
					break;
			 }

			 $addDate =  $settings['navigator_axis_total'] * $multiplyDate;
		 }
		 ?>


		<div id="chart-<?php echo $this->get_id(); ?>" >
			<?php include ELEMENTOR_ADDON_PATH.'helpers/spinner.php';?>
		</div>
		<?php
		if($settings['chart_filters']=='true' && $settings['filter_position']=='bottom'){
			echo $this->get_filters($settings);
		 } ?>
		<script>
        function createChart<?php echo $this->get_id(); ?>() {
            var options ={
				//renderAs: "canvas", // do zbadania
				dataSource: {
                    transport: {
                        read: {
                           // url: "http://mielec.impactit.nazwa.pl/spain-electricity.json",
							//for global uncomment below and remove under it!
						   //url: "<?php //echo $settings['datasource_url'] ?>",
                            url: "/wp-json/wp/v2/chart_data/<?php echo implode ('+',array_column($settings['chart_list'],'group')) ?>",							
                           data:{
							cat_param: "<?php echo $settings['category_axis'] ?>",
							x_param: "<?php echo implode ('+',array_column($settings['chart_list'],'axis_name')) ?>",
							top:<?php echo $settings['category_top'] ?>,
							datefilterTo:'',
							datefilterFrom:'',
						   },
						   dataType: "json"
                        }
                    },
                    sort: {
                        field: "<?php echo $settings['category_axis'] ?>",
                        dir: "<?php echo $settings['category_axis_sort'] ?>",
                    }
                },
                title: {
                    text: "<?php echo $settings['title_text'] ?>" 
                },
                legend: {
                    position: "<?php echo $settings['chart_legend_position'] ?>"
                },
                chartArea: {
                    background: "",
					<?php if(!empty($settings['chart_height'])){ ?>
						height:<?php echo $settings['chart_height'] ?>
					<?php } ?>
                },
                seriesDefaults: {
                    type: "<?php echo $settings['chart_type_combo'] ?>",
                    style: "<?php echo $settings['chart_line_style'] ?>",
					dashType: "<?php echo $settings['chart_line_type'] ?>" ,
					spacing:<?php echo $settings['chart_spacing'] ?>,
					gap:<?php echo $settings['chart_gap'] ?>,
                },
				series: [
				
					<?php	
					if(count($settings['chart_list'])){
						foreach($settings['chart_list'] as $chartlist){
					?>
						{
					//for global uncomment below and remove under it!
							//field: "<?php echo $chartlist['axis_name']; ?>",
							field: "<?php echo $chartlist['group']; ?>",
							name: "<?php echo (!empty($chartlist['axis_label']))?$chartlist['axis_label']:$chartlist['axis_name']; ?>",
							color: "<?php echo $chartlist['color']; ?>",
							axis: "<?php echo $chartlist['value_axis_name']; ?>", 
							<?php if(!empty($chartlist['chart_line_style_single'])) { ?>
								style: "<?php echo $chartlist['chart_line_style_single']; ?>",
							<?php } ?>
							<?php if(!empty($chartlist['chart_line_type_single'])) { ?>
								type: "<?php echo $chartlist['chart_line_type_single']; ?>",
							<?php } ?>
							labels: {
								visible: <?php echo $chartlist['show_labels']??false; ?>,
								template: "<?php echo $chartlist['labels_template']; ?>",
								opacity: 1,
								<?php if(!empty($chartlist['labels_color'])) { ?>
									color: "<?php echo $chartlist['labels_color']; ?>",
								<?php } ?>	
								<?php if(!empty($chartlist['labels_backgrouns_color'])) { ?>
									background: "<?php echo $chartlist['labels_backgrouns_color']; ?>",
								<?php } ?>
								<?php if(!empty($chartlist['labels_border']['size'])) { ?>
									border: {
										width: <?php echo $chartlist['labels_border']['size']; ?>,
										color: "<?php echo $chartlist['labels_border_color']; ?>",
									},
								<?php } ?>	
								
							},
					},
					<?php
						}
					}
					?>
					
				],
				<?php if($settings['chart_stock'] == 'false'){ ?>
				pannable: { // do zrobienia
                    lock: "y"
                },
                zoomable: {
                    mousewheel: {
                        lock: "y"
                    },
                    selection: {
                        lock: "y"
                    }
                },
				<?php } ?>
				valueAxis: [
					<?php	
					if(count($settings['axis_controll'])){
						foreach($settings['axis_controll'] as $axislist){
					?>
					{
						name: "<?php echo $axislist['value_axis_name']; ?>",					
						labels: {
						  visible: <?php echo $axislist['show_axis_labels']; ?>,
						  <?php if(!empty($axislist['value_axis_format'])) { ?>
						  format: "<?php echo $axislist['value_axis_format']; ?>",
						  <?php } ?>
						},
					
					
						// dodanie opisu osi
						<?php if(!empty($axislist['value_axis_title'])) { ?>
						title: {
							text: "<?php echo $axislist['value_axis_title']; ?>"
						},
						<?php } ?>

						// obsuga plotów
						<?php if(count($settings['axis_controll'])>0){ ?>
						plotBands: [
						<?php foreach($settings['plot_list'] as $plotList){ ?>						
							<?php if($plotList['value_axis_plot_enable']!=='false') { ?>		
								{
									from: <?php echo $plotList['value_axis_plot_from'] ??0; ?>,
									to: <?php echo $plotList['value_axis_plot_to']??0; ?>,
									color: "<?php echo $plotList['value_axis_plot_color']??''; ?>",
									opacity: "<?php echo ($plotList['value_axis_plot_opacity']['size']??100)/100; ?>",
								},<?php } } ?>
						],
						axisCrossingValues: [0, -Infinity]
						<?php } ?>					
					},
					<?php
						}
					}
					?>
				],
                categoryAxis: [
				<?php if($settings['chart_labels_below'] != 'false'){ ?>
				{
					name:'notvisible',
					visible: false,
					min:<?php echo $settings['category_top']-$settings['category_display'] ?>,
					max:<?php echo $settings['category_top'] ?>,
					baseUnitStep:"<?php echo $settings['category_display'] ?>",
					crosshair: {
                        visible: false, //category_helper_line
                    },
					<?php	
					if(count($settings['axis_controll'])){ ?>
						axisCrossingValue: [
							<?php foreach($settings['axis_controll'] as $axislist){ ?> <?php echo $axislist['value_axis_position']; ?>, <?php } ?>
						],
					<?php } else {?>
						axisCrossingValues: [0, -Infinity],
					<?php } ?>
				},
				<?php } ?>
				{
                    field: "<?php echo $settings['category_axis'] ?>",
					justified: <?php echo $settings['chart-justify'] ?>, //chart-justify
                    majorGridLines: {
                        visible: true
                    },
                    labels: {
						visible: <?php echo $settings['show_cat_labels']; ?>,
                        rotation: "auto",
						<?php if(!empty($settings['category_format'])) { ?>					
						//	format: "<?php echo $settings['category_format']; ?>",
						//thats for dates format:
						<?php if($settings['category_axis_type']=='date') {?>
						  template: "#= kendo.format('<?php echo $settings['category_format']; ?>', new Date(value)) #",
						  	<?php }else{ ?>
							format: "<?php echo $settings['category_format']; ?>",
							<?php }} ?>
                    },
					min:<?php echo $settings['category_top']-$settings['category_display'] ?>,
					max:<?php echo $settings['category_top'] ?>,
					baseUnitStep:"<?php echo $settings['category_display'] ?>",
					crosshair: {
                        visible: <?php echo $settings['category_helper_line'] ?>, //category_helper_line
                    },
					
					<?php	
					if(count($settings['axis_controll'])){ ?>
						axisCrossingValue: [
							<?php foreach($settings['axis_controll'] as $axislist){ ?> <?php echo $axislist['value_axis_position']; ?>, <?php } ?>
						],
					<?php } else {?>
						axisCrossingValues: [0, -Infinity],
					<?php } ?>
                }],
                tooltip: {
                    visible: <?php echo $settings['show_tooltip'] ?>,
					shared: <?php echo $settings['show_tooltip_shared'] ?>,
                    <?php if(!empty($settings['show_tooltip_format'])) { ?>
						format: "<?php echo $settings['show_tooltip_format'] ?>",
					<?php } ?>
					<?php if(!empty($settings['show_tooltip_template'])) { ?>
						template: "<?php echo $settings['show_tooltip_template'] ?>"
					<?php } ?>
                },
				<?php if($settings['chart_stock'] != 'false'){ ?>
				navigator: {				
                    series: {							
                        type: "<?php echo $settings['chart_navigator_type_combo'] ?>",                        
						field: "<?php echo $settings['chart_list'][0]['group']; ?>",
						<?php if($settings['navigator_color']!=''){ ?>
							color: "<?php echo $settings['navigator_color']??''; ?>",
						<?php } ?>
                    },
                    select: {
						from: "<?php echo $settings['navigator_axis_to_now'] == 'true'? date("Y/m/d H:00", strtotime('-'.$settings['navigator_axis_total'].' '.$settings['navigator_axis_time_type'])): $settings['navigator_axis_from'] ?>",
                      //  to: "<?php echo $settings['navigator_axis_to_now'] == 'true'? current_time('Y/m/d H:i') : $settings['navigator_axis_to'] ?>",
                    },
                    categoryAxis: {						
                        labels: { 
							padding:{
								top: <?php echo $settings['navigator_lable_padding']??0 ?>
							},
							visible: <?php echo $settings['show_navigator_labels']; ?>,
							rotation: "auto",
							<?php if(!empty($settings['navigator_format'])) { ?>					
							//	format: "<?php echo $settings['navigator_format']; ?>",
							//thats for dates format:
								format: "<?php echo $settings['navigator_format']; ?>",
							<?php } ?>
							baseUnit: "hours",
                        },

                    }
                },
				 dataBound: function(e) {
					 var data = e.sender.dataSource.view();
					 if(data[data.length-1]!=undefined){
						 var lastDate =data[data.length-1].date;
						 var second_date = new Date(lastDate);
						 second_date.setHours(second_date.getHours() - <?php echo  $addDate; ?>);
						 //$settings['navigator_axis_total'].' '.$settings['navigator_axis_time_type']   
						  e.sender._navigator.options.select.from =  kendo.toString(second_date,'yyyy-MM-dd HH:mm:ss');
						  e.sender._navigator.options.select.to =  kendo.toString(lastDate,'yyyy-MM-dd HH:mm:ss');
					  }
				  }
				<?php } ?>
            };
			<?php if($settings['chart_stock'] == 'false'){ ?> 
				 jQuery("#chart-<?php echo $this->get_id(); ?>").kendoChart(options);
			<?php } else { ?>
				 jQuery("#chart-<?php echo $this->get_id(); ?>").kendoStockChart(options);
			<?php } ?> 
			
        }
			//		jQuery(document).ready(function() {
			//			jQuery(window).load(function() {
			//				createChart<?php echo $this->get_id(); ?>();
			//			});
			//		});

			function createFilters<?php echo $this->get_id(); ?>(){
				var actualdate24  = new Date().setDate(new Date().getDate() - 1);
				jQuery("#chart-filter-<?php echo $this->get_id(); ?>").kendoDateTimePicker({
                    // display month and year in the input
                    format: "<?php echo $settings['filter_format']; ?>",
					change: function(){
						var filterTo=  kendo.toString(jQuery("#chart-filter-to-<?php echo $this->get_id(); ?>").data("kendoDateTimePicker").value(),'yyyy-MM-dd HH:mm:ss');
						<?php if($settings['chart_stock'] == 'false'){ ?> 
							var chart = jQuery("#chart-<?php echo $this->get_id(); ?>").data("kendoChart");
							if(this.value()!=null){
								chart.setOptions({categoryAxis: {min: 0, max: <?php echo $settings['category_display'] ?>}});
							}
							else
							{	
								chart.setOptions({categoryAxis: {min:<?php echo $settings['category_top']-$settings['category_display'] ?>,max:<?php echo $settings['category_top'] ?>}});							
							}
						<?php } else { ?>
							var chart = jQuery("#chart-<?php echo $this->get_id(); ?>").data("kendoStockChart");
							/*if(this.value()!=null){
							
								var second_date = new Date(lastDate);
								 second_date.setHours(second_date.getHours() - <?php echo  $addDate; ?>);
				
							//	chart._navigator.options.select.to =filterTo;
								chart._navigator.options.select.from = kendo.toString(this.value(),'yyyy-MM-dd HH:mm:ss');
							}
							else
							{
							//	chart._navigator.options.select.to = null;
								chart._navigator.options.select.from ="<?php echo $settings['navigator_axis_to_now'] == 'true'? date("Y/m/d H:00", strtotime('-'.$settings['navigator_axis_total'].' '.$settings['navigator_axis_time_type'])): $settings['navigator_axis_from'] ?>";
							}*/
						<?php } ?> 
					
						chart.dataSource.read({datefilterTo: filterTo,datefilterFrom: kendo.toString(this.value(),'yyyy-MM-dd HH:mm:ss')});
						chart.refresh();
					},
					culture:"<?php echo str_replace('_','-',get_locale()) ?>",
//TODO odkomentować!
					//value: kendo.toString(new Date(actualdate24),'dd-MM-yyyy HH:mm'),
                });
				jQuery("#chart-filter-to-<?php echo $this->get_id(); ?>").kendoDateTimePicker({
                    // display month and year in the input
                    format: "<?php echo $settings['filter_format']; ?>",
					change: function(){
						var filterFrom =  kendo.toString(jQuery("#chart-filter-<?php echo $this->get_id(); ?>").data("kendoDateTimePicker").value(),'yyyy-MM-dd HH:mm:ss');
						<?php if($settings['chart_stock'] == 'false'){ ?> 
							var chart = jQuery("#chart-<?php echo $this->get_id(); ?>").data("kendoChart");
							if(this.value()!=null){
								chart.setOptions({categoryAxis: {min: 0, max: <?php echo $settings['category_display'] ?>}});							
							}
							else
							{	
								chart.setOptions({categoryAxis: {min:<?php echo $settings['category_top']-$settings['category_display'] ?>,max:<?php echo $settings['category_top'] ?>}});
							}

						<?php } else { ?>
							var chart = jQuery("#chart-<?php echo $this->get_id(); ?>").data("kendoStockChart");
							/*if(this.value()!=null){
								chart._navigator.options.select.from = null;//filterFrom;
								chart._navigator.options.select.to = kendo.toString(this.value(),'yyyy-MM-dd HH:mm:ss');
							}
							else
							{
								chart._navigator.options.select.to = null;
								chart._navigator.options.select.from ="<?php echo $settings['navigator_axis_to_now'] == 'true'? date("Y/m/d H:00", strtotime('-'.$settings['navigator_axis_total'].' '.$settings['navigator_axis_time_type'])): $settings['navigator_axis_from'] ?>";
							}*/
						<?php } ?> 
						chart.dataSource.read({datefilterFrom:filterFrom,datefilterTo: kendo.toString(this.value(),'yyyy-MM-dd HH:mm:ss')});
						chart.refresh();
					},
					culture:"<?php echo str_replace('_','-',get_locale()) ?>",
					//value: kendo.toString(new Date(actualdate24),'dd-MM-yyyy HH:mm'),
                });
			}

		//jQuery(window).bind("load", function () {
		//	createChart<?php echo $this->get_id(); ?>();
		//});
		jQuery(document).ready(function(){
		
			<?php if($settings['chart_filters']=='true'){?>
				createFilters<?php echo $this->get_id(); ?>();
			<?php } ?>
			createChart<?php echo $this->get_id(); ?>();
			<?php if($settings['show_btn'] =='true'){ ?>
			
/*	jQuery("#btn-<?php echo $this->get_id(); ?>").click(function(e){
					var actualdate24  = new Date().setDate(new Date().getDate() - 1);
					var filter = jQuery("#chart-filter-<?php echo $this->get_id(); ?>").data("kendoDateTimePicker");
					filter.value('');
//TODO odkomentować!
					//filter.value(kendo.toString(new Date(actualdate24),'dd-MM-yyyy HH:mm'));
					filter.trigger("change");
				});
*/
				jQuery("#btn-<?php echo $this->get_id(); ?>").kendoButton({
				click:function(e){
					var actualdate24  = new Date().setDate(new Date().getDate() - 1);
					var filter = jQuery("#chart-filter-<?php echo $this->get_id(); ?>").data("kendoDateTimePicker");
					var filter2 = jQuery("#chart-filter-to-<?php echo $this->get_id(); ?>").data("kendoDateTimePicker");
					filter.value('');
					filter2.value('');
//TODO odkomentować!
					//filter.value(kendo.toString(new Date(actualdate24),'dd-MM-yyyy HH:mm'));
					filter.trigger("change");
				}
			});
				
			<?php } ?>
		});
        //jQuery(document).bind("kendo:skinChange", createChart);
    </script>
		
		<?php
	}
	
	protected function content_template() {
	
	}
	public function render_plain_content( $instance = [] ) {}
}
Plugin::instance()->widgets_manager->register_widget_type( new Widget_Chart_Elementor_Thing() );