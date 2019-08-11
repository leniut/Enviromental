<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class Widget_Table_Elementor_Thing extends Widget_Base {

	public function get_name() {
		return 'my-table';
	}
	public function get_title() {
		return __( 'Table', 'elementor' );
	}
	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
		return 'fa fa-table';
	}
	public function get_categories() {
		return [ 'general' ];
	}

	protected function _register_controls() {
			
		$this->start_controls_section(
			'section_table',
			[
				'label' => esc_html__( 'Columns', 'elementor' ),
			]
		);

			$options = array('temperature' => __( 'Temperatura', 'elementor' ),
							'pressure' => __( 'CiĹ›nienie', 'elementor' ),
							'rain' => __( 'Opady', 'elementor' ),
							'wind_speed' => __( 'PrÄ™dkoĹ›Ä‡ wiatru', 'elementor' ),
							'wind_dir' => __( 'Kierunek wiatru', 'elementor' ),
							'humidity' => __( 'WilgotnoĹ›Ä‡', 'elementor' ),
							'leq' => __( 'DĹşwiÄ™k', 'elementor' ),
							'pm1' => __( 'PM 1', 'elementor' ),
							'pm25' => __( 'PM 2.5', 'elementor' ),
							'pm10' => __( 'PM 10', 'elementor' ),
							'ch20_ppb' => __( 'PyĹ‚: CH20 PPB', 'elementor' ),
							'ch20_ug_m3' => __( 'PyĹ‚: CH20 UG M3', 'elementor' ),
							'lzo' => __( 'LZO', 'elementor' ),
							'date'=>__('Data','Elementor'));
			
			$this->add_control(
			'table_columns',
			[
				'label' => '',
				'type' => Controls_Manager::REPEATER,
				'default'=>[
					'column_label'=>'',
					'column_field'=>'date',
					'column_type'=>'date',
					'column_format'=>'{0:dd-MM-yyyy HH:mm}',
					'column_width'=>'auto',
					'column_lock'=>'false',
					'column_sort'=>''
				],
				'fields' => [
					[
						'name' => '',
						'label' => __( 'Nazwa', 'elementor' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
					],
					[
						'name' => 'column_field',//<----------------------for weather/to delete for global
						'label' => __( 'Nazwa parametru', 'elementor' ),
						'type' => Controls_Manager::SELECT,
						'options' => $options,
						'default' => '',
						'label_block' => true,
					],
					/*[
						'name' => 'column_field',
						'label' => __( 'Nazwa parametru', 'elementor' ),
						//'type' => Controls_Manager::TEXT, <----------------------for global
						'type' => Controls_Manager::HIDDEN,
						'label_block' => true,
					],*/
					[
						'name' => 'column_type',
						'label' => __( 'Type', 'elementor' ),
						'type' => Controls_Manager::SELECT,
						'options' => [
							'string'=>__('String','elementor'),
							'number'=>__('Number','elementor'),
							'date'=>__('Date','elementor'),
						],
						'label_block' => true,
						'default'=>'number'						
					],
					[
						'name' => 'column_format',
						'label' => __( 'Format', 'elementor' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
					],	
					[
						'name' => 'column_width',
						'label' => __( 'Width', 'elementor' ),
						'type' => Controls_Manager::TEXT,
						'default' => 'auto',
						'label_block' => true,
					],
					[
						'name' => 'column_lock',
						'label' => __( 'Zablokuj kolumnÄ™', 'elementor' ),
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
						'name' => 'column_sort',
						'label' => __( 'Sortuj kolumnÄ™', 'elementor' ),
						'type' => Controls_Manager::CHOOSE,
						'options' => [
							'' => [
								'title' => __( 'No sorting', 'elementor' ),
								'icon' => 'fa fa-ban',
							],
							'asc' => [
								'title' => __( 'Ascending', 'elementor' ),
								'icon' => 'fa fa-sort-amount-asc',
							],
							'desc' => [
								'title' => __( 'Descending', 'elementor' ),
								'icon' => 'fa fa-sort-amount-desc',
							]
						],
						'default' => '',
						'toggle' => false,
					],
					[
						'name' =>'column_align',
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
					]							
				],
				'title_field' =>'{{column_label}}',
			]
		);

		$this->add_control(
			'table_pagin_size',
			[
				'label' => __( 'IloĹ›Ä‡ wierszy na stronie', 'elementor' ),
				'type' => Controls_Manager::TEXT,			
				'default' => '20'
			]
		);

		$this->end_controls_section();		

		$this->start_controls_section(
			'section_style_table',
			[
				'label' => __( 'Table', 'elementor' ),
			]
		);
		
		$this->add_control(
			'table_sortable',
			[
				'label' => __( 'Sortowanie', 'elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'true' => [
						'title' => __( 'Yes', 'elementor' ),
						'icon' => 'fa fa-check',
					],
					'false' => [
						'title' => __( 'No', 'elementor' ),
						'icon' => 'fa fa-ban'
					]
				],
				'default' => 'false'
			]
		);
		$this->add_control(
			'table_filterable',
			[
				'label' => __( 'Filtrowanie', 'elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'true' => [
						'title' => __( 'Yes', 'elementor' ),
						'icon' => 'fa fa-check',
					],
					'false' => [
						'title' => __( 'No', 'elementor' ),
						'icon' => 'fa fa-ban'
					]
				],
				'default' => 'false'
			]
		);
		$this->add_control(
			'table_groupable',
			[
				'label' => __( 'Grupowanie', 'elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'true' => [
						'title' => __( 'Yes', 'elementor' ),
						'icon' => 'fa fa-check',
					],
					'false' => [
						'title' => __( 'No', 'elementor' ),
						'icon' => 'fa fa-ban'
					]
				],
				'default' => 'false'
			]
		);
		$this->add_control(
			'table_pageable',
			[
				'label' => __( 'Paginacja', 'elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'true' => [
						'title' => __( 'Yes', 'elementor' ),
						'icon' => 'fa fa-check',
					],
					'false' => [
						'title' => __( 'No', 'elementor' ),
						'icon' => 'fa fa-ban'
					]
				],
				'default' => 'false'
			]
		);
		
		$this->add_control(
			'table_height',
			[
				'label' => __( 'Height', 'elementor' ),
				'type' => Controls_Manager::TEXT,			
				'default' => '550'
			]
		);

		$this->end_controls_section();	

		$this->start_controls_section(
			'section_style_table_export',
			[
				'label' => __( 'Export', 'elementor' ),
			]
		);

		$this->add_control(
			'excel_heading_title',
			[
				'label' => __( 'Excel', 'elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'table_export',
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
						'icon' => 'fa fa-ban'
					]
				],
				'default' => 'false'
			]
		);

		$this->add_control(
			'export_excel_title',
			[
				'label' => __( 'Title', 'elementor' ),
				'type' => Controls_Manager::TEXT,			
				'default' => 'Export'
			]
		);
		$this->add_control(
			'export_excel_file_title',
			[
				'label' => __( 'Nazwa pliku', 'elementor' ),
				'type' => Controls_Manager::TEXT,			
				'default' => 'DataExport'
			]
		);
		
		$this->add_control(
			'table_export_excel_all_pages',
			[
				'label' => __( 'Wszystkie strony', 'elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'true' => [
						'title' => __( 'Yes', 'elementor' ),
						'icon' => 'fa fa-check',
					],
					'false' => [
						'title' => __( 'No', 'elementor' ),
						'icon' => 'fa fa-ban'
					]
				],
				'default' => 'false'
			]
		);
		
		$this->end_controls_section();	

	}
	protected function render( ) {
		$settings = $this->get_settings();
		?>
		<div id="table-<?php echo $this->get_id(); ?>" >		
		</div>
		
		<script>
			
       // function createTable<?php echo $this->get_id(); ?>() {
		
		jQuery(document).ready(function(){
		jQuery("#table-<?php echo $this->get_id(); ?>").kendoGrid({
				 dataSource: {
					 
					 transport: {
						 read: {
							url: "/wp-json/wp/v2/table_data/<?php echo implode ('+',array_column($settings['table_columns'],'column_field')) ?>",						
						 },
						  parameterMap: function (data, type) {	
							  if(data.filter!=undefined){
								  jQuery.each(data.filter['filters'],function(key,val){								
										if(val['filters']!=undefined)
										{
											 jQuery.each(val['filters'],function(key,val2){								  
												 if(val2['value'] instanceof Date){
														val2['value'] = kendo.toString(val2['value'],"dd-MM-yyyy HH:mm:ss");
													}
											  });
										}
										if(val['value'] instanceof Date){
											val['value'] = kendo.toString(val['value'],"dd-MM-yyyy HH:mm:ss");
										}
								  });
							  }
							return data;
						  }
					 },
						
					<?php if(count($settings['table_columns'])){ ?>
						sort:[ 
					<?php foreach($settings['table_columns'] as $sortlist){ 
					if($sortlist['column_sort'] !== ''){
					?>
								{
									field: "<?php echo $sortlist['column_field'] ?>",
									dir: "<?php echo $sortlist['column_sort'] ?>"
								},
					<?php }} ?>
							],
					<?php }?>
					schema: {
						type: "json",
						 data: "data",
						 total:"total",
						 errors:"errors",
						 model:{
						 	 fields:{
							 <?php	
					if(count($settings['table_columns'])){
						foreach($settings['table_columns'] as $axislist){
					?>
								"<?php echo $axislist['column_field'] ?>":{
								<?php if(!empty($axislist['column_type'])) { ?>
									type:"<?php echo $axislist["column_type"] ?>"
								 <?php } ?>
								},
								
					 <?php } }?>
							 }
						 }
					 },
					 serverSorting: true,
					 serverPaging: true,
					 serverFiltering: true,
                     pageSize: "<?php echo $settings['table_pagin_size'] ?>",
				 },
				 columns: [				 
					<?php	
					if(count($settings['table_columns'])){
						foreach($settings['table_columns'] as $axislist){
					?>
					{
						field: "<?php echo $axislist['column_field'] ?>",
						locked: <?php echo $axislist['column_lock'] ?>,
						<?php if(!empty($axislist['column_label'])) { ?>
							title:  "<?php echo $axislist['column_label'] ?>",
						<?php } ?>
						<?php if(!empty($axislist['column_format'])) { ?>
							format:  "<?php echo $axislist['column_format'] ?>",
						 <?php } ?>
						<?php if(!empty($axislist['column_width'])) { ?>
							width:  "<?php echo $axislist['column_width'] ?>",
						 <?php }else {?>
							width: "auto",
						 <?php } ?>
						 <?php if($axislist['column_type']=='date') { ?>
							 filterable: {
                                ui: function(element) {
                                     element.kendoDateTimePicker({
                                          format: "dd-MM-yyyy HH:mm"
                                     })
								   }
								},
						<?php } ?>
						<?php if(!empty($axislist['column_align'])) { ?>
							attributes: {style:"text-align:<?php echo $axislist['column_align']?>;"},
						 <?php } ?>
					},
					<?php } }?>
				 ],
				 <?php if($settings['table_export']=='true') {?>
					toolbar: [  { name: "excel", text: '<?php echo ($settings['export_excel_title']!=''?$settings['export_excel_title']:'Export'); ?>' }],
					excel: {
						fileName: '<?php echo ($settings['export_excel_file_title']!=''?$settings['export_excel_file_title']:'DataExport'); ?>.xlsx',
						filterable: true,
						allPages: <?php echo $settings['table_export_excel_all_pages']??false ?>
					},
				<?php } ?>
				height: "<?php echo $settings['table_height'] ?>",
				pageable: <?php echo $settings['table_pageable'] ?>,
				sortable: <?php echo $settings['table_sortable'] ?>,
				groupable:<?php echo $settings['table_groupable'] ?>,
				filterable:<?php echo $settings['table_filterable'] ?>,				 
			});
			
      /*jQuery("#table-<?php echo $this->get_id(); ?>").kendoGrid({
                        dataSource: {
                            type: "json",
                            transport: {
							 read:{
								url:  "http://mielec.impactit.nazwa.pl/wp-json/wp/v2/table_data/<?php echo $settings['data_combo'] ?>",
								dataType:'json'
								}
                            },
                            schema: {
                                model: {
                                    fields: {
                                        sync_date: { type: "string" },
                                        temperature: { type: "number" },
                                        pressure: { type: "number" },
                                    }
                                }
                            },
                            pageSize: 20,
                           // serverPaging: true,
                           // serverFiltering: true,
                           // serverSorting: true
                        },
                        height: 550,
                       // filterable: true,
                      //  sortable: true,
                      // <?php if( $settings['table_pageable'] =="true"){ ?>
						//	 pageable:<?php echo $settings['table_pageable'] ?>,
						//	 {
						//		refresh: true
						//	  },
						// <?php } ?>
                        columns: [
							"sync_date",
                            "temperature",
							"pressure"
                        ]
                    });			}
			*/	
		
			});
			//jQuery(document).ready(createTable<?php echo $this->get_id(); ?>);
		</script>
		<?php
	}
	protected function content_template() {}
	public function render_plain_content( $instance = [] ) {}
}
Plugin::instance()->widgets_manager->register_widget_type( new Widget_Table_Elementor_Thing() );