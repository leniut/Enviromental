<?php
class SensorCorrection
{
	public $table = SENSOR_CORECTION_TABLE;
	public $columns = array('id', 'device_id', 'sensor_type', 'correction');
    public $name ="Korekcja czujników";
	public $import_btn = false;
	public $import_all_btn = false;


	function table_name($all=false)
	{
		global $wpdb;
		$tname= $this->table;
//		if($all=="true")
//			$tname=$this->table.'_all';
		$finalTableName =$wpdb->prefix .$tname; 
		return $finalTableName;
	}

	function get_grid(){
	if($this->import_btn){

	$dateTimePicker = new \Kendo\UI\DateTimePicker('startSensorCorrection');
	$dateTimePicker->value(new DateTime('now', new DateTimeZone('UTC')))
          //->parseFormats(array('MM/dd/yyyy'))
			->change('function(e){
			  var startDate = $("#startSensorCorrection").data("kendoDateTimePicker").value();
			 
				if (startDate) {
					startDate = new Date(startDate);
					startDate.setDate(startDate.getDate() + 1);
					$("#endSensorCorrection").data("kendoDateTimePicker").min(startDate);
				}
			}')
           ->attr('title', 'datetimepicker')
           ->dateInput(true);
echo $dateTimePicker->render();

$dateTimePicker = new \Kendo\UI\DateTimePicker('endSensorCorrection');
$dateTimePicker->value(new DateTime('now', new DateTimeZone('UTC')))
           //->parseFormats(array('MM/dd/yyyy'))
			->change('function(e){
				 var endDate =  $("#endSensorCorrection").data("kendoDateTimePicker").value();

				if (endDate) {
					endDate = new Date(endDate);
					endDate.setDate(endDate.getDate() - 1);
					$("#startSensorCorrection").data("kendoDateTimePicker").max(endDate);
				}
			}')
           ->attr('title', 'datetimepicker')
           ->dateInput(true);
echo $dateTimePicker->render();

	$textButton = new \Kendo\UI\Button('SensorCorrectionBtn');
		$textButton->attr('type', 'button')
			  ->content(__('Import','impactit'))
              ->click('function(e) { 
						var element = jQuery("#SensorCorrectionGrid");
						var dateStart = jQuery("#startSensorCorrection").val();
						var dateend = jQuery("#endSensorCorrection").val();
						kendo.ui.progress(element, true);
						jQuery.ajax({
						  type: "POST",
						  url: "'.admin_url( 'admin-ajax.php' ) .'?action=import_SensorCorrection&start="+dateStart+"&end="+dateend,
						}).done(function( msg ) {
							alert( msg );						    
						}).fail(function() {
							alert( "error" );
						  }).always(function() {
							kendo.ui.progress(element, false);
							element.data("kendoGrid").dataSource.read();
							element.data("kendoGrid").refresh();
						  });
				}');

		echo $textButton->render();
		}
		//show grid
		$transport = new \Kendo\Data\DataSourceTransport();
		
		$read = new \Kendo\Data\DataSourceTransportRead();

		$read->url(admin_url( 'admin-ajax.php' ).'?action=read_db&type=read&db=md')
		->contentType('application/json')
		->dataType('json')
		->type('POST');
				
		$transport
		->read($read)
		->parameterMap('function(data) {
		return kendo.stringify(data);
		}');

		$model = new \Kendo\Data\DataSourceSchemaModel();

		$id = new \Kendo\Data\DataSourceSchemaModelField('id');
		$id->type('number')
		->editable(false);
  
		$dev = new \Kendo\Data\DataSourceSchemaModelField('device_id');
        $dev->type('number');
        
        $snesorType = new \Kendo\Data\DataSourceSchemaModelField('sensor_type');
		$snesorType->type('string');

		$correction = new \Kendo\Data\DataSourceSchemaModelField('correction');
        $correction->type('number');

		$model->id('id')
		->addField($id)
		->addField($dev)
		->addField($snesorType)
		->addField($correction);


		$schema = new \Kendo\Data\DataSourceSchema();
		$schema->data('data')
		->model($model)
		->errors('errors')
		->total('total');

		$dataSource = new \Kendo\Data\DataSource();

		$dataSource->transport($transport)
		->batch(true)
		->pageSize(20)
		->schema($schema)
        ->serverPaging(true)
        ->serverSorting(true)
        ->serverFiltering(true);


        $dev = new \Kendo\UI\GridColumn();$dev->field('device_id') ->title('Urządzenie');
        $snesorType = new \Kendo\UI\GridColumn();$snesorType->field('sensor_type') ->title('Czujnik');
        $correction = new \Kendo\UI\GridColumn();$correction->field('correction') ->title('Korekta');
        
   
		$pageable = new \Kendo\UI\GridPageable();
			$pageable//->input(true)
		->numeric(true)
		->pageSizes([5,10,20,30,50,70,999999]);
        		
		$gridRC = new \Kendo\UI\Grid('SensorCorrectionGrid');

		$gridRC->addColumn($dev, $snesorType, $correction)
		->dataSource($dataSource)
		->height(550)
		->sortable(true)
		->editable('inline')
		->filterable(true)
		->mobile(true)
		->pageable($pageable);

		echo $gridRC->render();
	}

	/**
	 * WSZYSTKIE DANE Z SENSORÓW (TAB)
	 * 
	 * @return void
	 */
	function get_grid_all(){
	//import button
	if($this->import_all_btn){
	echo '<div class="SensorCorrectionForm">';
	echo '<span style="margin:5px">'.__('Od: ','impactit').'</span>';
		$dateTimePicker = new \Kendo\UI\DateTimePicker('startSensorCorrection');
		$dateTimePicker->value((new DateTime('now', new DateTimeZone('UTC'))))
		->interval(60)
					->change('function(e){		
					  var startDate = jQuery("#startSensorCorrection").data("kendoDateTimePicker").value();			 
						if (startDate) {
							startDate = new Date(startDate);
							//startDate.setHours(startDate.getHours() + 1);
							jQuery("#endSensorCorrection").data("kendoDateTimePicker").min(startDate);
						}
					}')
					->max(new DateTime('now', new DateTimeZone('UTC')))
				   ->attr('title', 'datetimepicker')
				   ->dateInput(true);
		echo $dateTimePicker->render();
		
	echo '<span  style="margin:5px">'.__('Do: ','impactit').'</span>';
		$dateTimePicker = new \Kendo\UI\DateTimePicker('endSensorCorrection');
		$dateTimePicker->value(new DateTime('now', new DateTimeZone('UTC')))
		->interval(60)
					->change('function(e){
						 var endDate =  jQuery("#endSensorCorrection").data("kendoDateTimePicker").value();
						if (endDate) {
							endDate = new Date(endDate);
							endDate.setHours(endDate.getHours() - 1);
							jQuery("#startSensorCorrection").data("kendoDateTimePicker").max(endDate);
						}
					}')
					//->min(new DateTime('now', new DateTimeZone('UTC')))
					->max(new DateTime('now', new DateTimeZone('UTC')))
				   ->attr('title', 'datetimepicker')
				   ->dateInput(true);
		echo $dateTimePicker->render();
		
		$textButton = new \Kendo\UI\Button('SensorCorrectionButton');
		$textButton->attr('type', 'button')
			->attr('class', 'importBtn')
				   ->content(__('Import','impactit'))
              ->click('function(e) { 
						var element = jQuery("#SensorCorrectionGridAll");
						var dateStart = jQuery("#startSensorCorrection").val();
						var dateEnd = jQuery("#endSensorCorrection").val();
						kendo.ui.progress(element, true);
						jQuery.ajax({
						  type: "POST",
						  url: "'.admin_url( 'admin-ajax.php' ) .'",
						   data: { 
						   action: "import_SensorCorrection_all",
						   start: dateStart,
							end: dateEnd }
						}).done(function( msg ) {
							alert( msg );						    
						}).fail(function() {
							alert( "error" );
						  }).always(function() {
							kendo.ui.progress(element, false);
							element.data("kendoGrid").dataSource.read();
							element.data("kendoGrid").refresh();
						  });
				}');

		echo $textButton->render();

		?>
		<script>
			jQuery(document).ready(function(){
			jQuery("#startSensorCorrection").data('kendoDateTimePicker').trigger('change');
			});
		</script>
		</div>
		<?php

		}
		//show grid
		$transport = new \Kendo\Data\DataSourceTransport();
		
		$read = new \Kendo\Data\DataSourceTransportRead();

		$read->url(admin_url( 'admin-ajax.php' ).'?action=read_db&type=read&db=scd&all=true')
		->contentType('application/json')
		->dataType('json')
		->type('POST');

		$transport
		->read($read)
		->parameterMap('function(data) {
		return kendo.stringify(data);
		}');

		$model = new \Kendo\Data\DataSourceSchemaModel();

		$id = new \Kendo\Data\DataSourceSchemaModelField('id');
		$id->type('number')
		->editable(false);
 
		$dev = new \Kendo\Data\DataSourceSchemaModelField('device_id');
        $dev->type('number');
        
        $snesorType = new \Kendo\Data\DataSourceSchemaModelField('sensor_type');
		$snesorType->type('string');

		$correction = new \Kendo\Data\DataSourceSchemaModelField('correction');
        $correction->type('number');

		$model->id('id')
        ->addField($id)
        ->addField($dev)
		->addField($snesorType)
		->addField($correction);

		$schema = new \Kendo\Data\DataSourceSchema();
		$schema->data('data')
		->model($model)
		->errors('errors')
		->total('total');

		$dataSource = new \Kendo\Data\DataSource();

		$dataSource->transport($transport)
		->batch(true)
		->pageSize(20)
		->schema($schema)
        ->serverPaging(true)
        ->serverSorting(true)
        ->serverFiltering(true);

		$dev = new \Kendo\UI\GridColumn();$dev->field('device_id') ->title('Urządzenie');
		$snesorType = new \Kendo\UI\GridColumn();$snesorType->field('sensor_type') ->title('Czujnik');
		$correction = new \Kendo\UI\GridColumn();$correction->field('correction') ->title('Korekta');

    
		$pageable = new \Kendo\UI\GridPageable();
		$pageable//->input(true)
		->numeric(true)
		->pageSizes([5,10,20,30,50,70,999999]);
        		
		$gridRC = new \Kendo\UI\Grid('SensorCorrectionGridAll');

		$gridRC->addColumn($dev, $snesorType, $correction)
		->dataSource($dataSource)
		->height(550)
		->sortable(true)
		->editable('inline')
		->filterable(true)
		->mobile(true)
		->pageable($pageable);

		echo $gridRC->render();
	}
	function get_last_data(){
		
		$repo = new Table_Repository($this->table);
		return $repo->get_single( "*",  '', 'sensor_type desc');   

	}
}?>