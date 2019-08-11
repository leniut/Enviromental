<?php
class MeteoData
{
	public $table = METEO_TABLE_ALL;
	public $columns = array('id',  'sync_date', 'device_id','temperature','pressure','rain','wind_speed', 'wind_dir','humidity');
    public $name ="Dane Pogodowe !";
	public $import_btn = false;
	public $import_all_btn = true;


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

	$dateTimePicker = new \Kendo\UI\DateTimePicker('startMeteo');
	$dateTimePicker->value(new DateTime('now', new DateTimeZone('UTC')))
          //->parseFormats(array('MM/dd/yyyy'))
			->change('function(e){
			  var startDate = $("#startMeteo").data("kendoDateTimePicker").value();
			 
				if (startDate) {
					startDate = new Date(startDate);
					startDate.setDate(startDate.getDate() + 1);
					$("#endMeteo").data("kendoDateTimePicker").min(startDate);
				}
			}')
           ->attr('title', 'datetimepicker')
           ->dateInput(true);
echo $dateTimePicker->render();

$dateTimePicker = new \Kendo\UI\DateTimePicker('endMeteo');
$dateTimePicker->value(new DateTime('now', new DateTimeZone('UTC')))
           //->parseFormats(array('MM/dd/yyyy'))
			->change('function(e){
				 var endDate =  $("#endMeteo").data("kendoDateTimePicker").value();

				if (endDate) {
					endDate = new Date(endDate);
					endDate.setDate(endDate.getDate() - 1);
					$("#startMeteo").data("kendoDateTimePicker").max(endDate);
				}
			}')
           ->attr('title', 'datetimepicker')
           ->dateInput(true);
echo $dateTimePicker->render();

	$textButton = new \Kendo\UI\Button('meteoBtn');
		$textButton->attr('type', 'button')
			  ->content(__('Import','impactit'))
              ->click('function(e) { 
						var element = jQuery("#meteoGrid");
						var dateStart = jQuery("#startMeteo").val();
						var dateend = jQuery("#endMeteo").val();
						kendo.ui.progress(element, true);
						jQuery.ajax({
						  type: "POST",
						  url: "'.admin_url( 'admin-ajax.php' ) .'?action=import_meteo&start="+dateStart+"&end="+dateend,
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
 
		$date = new \Kendo\Data\DataSourceSchemaModelField(	'sync_date');
		$date->type('date');
		$dev = new \Kendo\Data\DataSourceSchemaModelField(	'device_id');
		$dev->type('number');
		$temp = new \Kendo\Data\DataSourceSchemaModelField(	'temperature');
		$temp->type('number');
		$pres = new \Kendo\Data\DataSourceSchemaModelField(	'pressure');
		$pres->type('number');
		$rain = new \Kendo\Data\DataSourceSchemaModelField(	'rain');
		$rain->type('number');
		$wind_speed = new \Kendo\Data\DataSourceSchemaModelField(	'wind_speed');
		$wind_speed->type('number');
		$wind_dir = new \Kendo\Data\DataSourceSchemaModelField(	'wind_dir');
		$wind_dir->type('number');
		$humidity = new \Kendo\Data\DataSourceSchemaModelField(	'humidity');
		$humidity->type('number');

		$model->id('id')
		->addField($id)
		->addField($date)
		->addField($dev)
		->addField($temp)
		->addField($pres)
		->addField($rain)
		->addField($wind_speed)
		->addField($wind_dir)
		->addField($humidity);

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

		$date = new \Kendo\UI\GridColumn();$date->field('sync_date') ->title('Date');$date->format('{0:dd-MM-yyyy HH:mm:ss}');
		$dev = new \Kendo\UI\GridColumn();$dev->field('device_id') ->title('Device');
		$temp = new \Kendo\UI\GridColumn();$temp->field('temperature') ->title('Temperature');
		$pres = new \Kendo\UI\GridColumn();$pres->field('pressure') ->title('Pressure');
		$rain = new \Kendo\UI\GridColumn();$rain->field('rain') ->title('Rain');
		$wind_speed = new \Kendo\UI\GridColumn();$wind_speed->field('wind_speed') ->title('Wind speed');
		$wind_dir = new \Kendo\UI\GridColumn();$wind_dir->field('wind_dir') ->title('Wind dir');
		$humidity = new \Kendo\UI\GridColumn();$humidity->field('humidity') ->title('Humidity');
    
		$pageable = new \Kendo\UI\GridPageable();
			$pageable//->input(true)
		->numeric(true)
		->pageSizes([5,10,20,30,50,70,999999]);
        		
		$gridRC = new \Kendo\UI\Grid('meteoGrid');

		$gridRC->addColumn($date,$dev,$temp,$pres,$rain,$wind_speed,$wind_dir,$humidity)
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
	 * WSZYSTKIE DANE POGODOWE
	 * 
	 * @return void
	 */
	function get_grid_all(){
	//import button
	if($this->import_all_btn){
	echo '<div class="importForm">';
	echo '<span style="margin:5px">'.__('Od: ','impactit').'</span>';
		$dateTimePicker = new \Kendo\UI\DateTimePicker('startMeteo');
		$dateTimePicker->value((new DateTime('now', new DateTimeZone('UTC'))))
		->interval(60)
					->change('function(e){		
					  var startDate = jQuery("#startMeteo").data("kendoDateTimePicker").value();			 
						if (startDate) {
							startDate = new Date(startDate);
							//startDate.setHours(startDate.getHours() + 1);
							jQuery("#endMeteo").data("kendoDateTimePicker").min(startDate);
						}
					}')
					->max(new DateTime('now', new DateTimeZone('UTC')))
				   ->attr('title', 'datetimepicker')
				   ->dateInput(true);
		echo $dateTimePicker->render();
		
	echo '<span  style="margin:5px">'.__('Do: ','impactit').'</span>';
		$dateTimePicker = new \Kendo\UI\DateTimePicker('endMeteo');
		$dateTimePicker->value(new DateTime('now', new DateTimeZone('UTC')))
		->interval(60)
					->change('function(e){
						 var endDate =  jQuery("#endMeteo").data("kendoDateTimePicker").value();
						if (endDate) {
							endDate = new Date(endDate);
							endDate.setHours(endDate.getHours() - 1);
							jQuery("#startMeteo").data("kendoDateTimePicker").max(endDate);
						}
					}')
					//->min(new DateTime('now', new DateTimeZone('UTC')))
					->max(new DateTime('now', new DateTimeZone('UTC')))
				   ->attr('title', 'datetimepicker')
				   ->dateInput(true);
		echo $dateTimePicker->render();
		
		$textButton = new \Kendo\UI\Button('meteoButton');
		$textButton->attr('type', 'button')
			->attr('class', 'importBtn')
				   ->content(__('Import','impactit'))
              ->click('function(e) { 
						var element = jQuery("#meteoGridAll");
						var dateStart = jQuery("#startMeteo").val();
						var dateEnd = jQuery("#endMeteo").val();
						kendo.ui.progress(element, true);
						jQuery.ajax({
						  type: "POST",
						  url: "'.admin_url( 'admin-ajax.php' ) .'",
						   data: { 
						   action: "import_meteo_all",
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
			jQuery("#startMeteo").data('kendoDateTimePicker').trigger('change');
			});
		</script>
		</div>
		<?php

		}
		//show grid
		$transport = new \Kendo\Data\DataSourceTransport();
		
		$read = new \Kendo\Data\DataSourceTransportRead();

		$read->url(admin_url( 'admin-ajax.php' ).'?action=read_db&type=read&db=md&all=true')
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
 
		$date = new \Kendo\Data\DataSourceSchemaModelField(	'sync_date');
		$date->type('date');
		$dev = new \Kendo\Data\DataSourceSchemaModelField(	'device_id');
		$dev->type('number');
		$temp = new \Kendo\Data\DataSourceSchemaModelField(	'temperature');
		$temp->type('number');
		$pres = new \Kendo\Data\DataSourceSchemaModelField(	'pressure');
		$pres->type('number');
		$rain = new \Kendo\Data\DataSourceSchemaModelField(	'rain');
		$rain->type('number');
		$wind_speed = new \Kendo\Data\DataSourceSchemaModelField(	'wind_speed');
		$wind_speed->type('number');
		$wind_dir = new \Kendo\Data\DataSourceSchemaModelField(	'wind_dir');
		$wind_dir->type('number');
		$humidity = new \Kendo\Data\DataSourceSchemaModelField(	'humidity');
		$humidity->type('number');

		$model->id('id')
		->addField($id)
		->addField($date)
		->addField($dev)
		->addField($temp)
		->addField($pres)
		->addField($rain)
		->addField($wind_speed)
		->addField($wind_dir)
		->addField($humidity);

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

		$date = new \Kendo\UI\GridColumn();$date->field('sync_date') ->title('Date');$date->format('{0:dd-MM-yyyy HH:mm:ss}');
		$dev = new \Kendo\UI\GridColumn();$dev->field('device_id') ->title('Device');
		$temp = new \Kendo\UI\GridColumn();$temp->field('temperature') ->title('Temperature');
		$pres = new \Kendo\UI\GridColumn();$pres->field('pressure') ->title('Pressure');
		$rain = new \Kendo\UI\GridColumn();$rain->field('rain') ->title('Rain');
		$wind_speed = new \Kendo\UI\GridColumn();$wind_speed->field('wind_speed') ->title('Wind speed');
		$wind_dir = new \Kendo\UI\GridColumn();$wind_dir->field('wind_dir') ->title('Wind dir');
		$humidity = new \Kendo\UI\GridColumn();$humidity->field('humidity') ->title('Humidity');
    
		$pageable = new \Kendo\UI\GridPageable();
		$pageable//->input(true)
		->numeric(true)
		->pageSizes([5,10,20,30,50,70,999999]);
        		
		$gridRC = new \Kendo\UI\Grid('meteoGridAll');

		$gridRC->addColumn($date,$dev,$temp,$pres,$rain,$wind_speed,$wind_dir,$humidity)
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
		return $repo->get_single( "*",  '', 'sync_date desc');   

	}
}?>