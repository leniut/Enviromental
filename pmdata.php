<?php
class PMData
{
	public $table = PM_TABLE_ALL;
	public $columns = array('id',  'sync_date', 'device_id','pm1','pm25','pm10');
    public $name ="Jakość powietrza";
	public $import_btn = false;
	public $import_all_btn = true;
	
	function table_name($all=false){
		global $wpdb;
		$tname= $this->table;
		//if($all=="true")
		//	$tname.='_all';
		return $wpdb->prefix .$tname;
	}

	function get_grid(){
		if($this->import_btn){
	$textButton = new \Kendo\UI\Button('pmBtn');
		$textButton->attr('type', 'button')
			  ->content(__('Import','impactit'))
              ->click('function(e) { 
						var element = jQuery("#pmGrid");
						kendo.ui.progress(element, true);
						jQuery.ajax({
						  type: "POST",
						  url: "'.admin_url( 'admin-ajax.php' ) .'?action=import_pm",
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

		$read->url(admin_url( 'admin-ajax.php' ).'?action=read_db&type=read&db=pm')
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
		$temp = new \Kendo\Data\DataSourceSchemaModelField(	'pm1');
		$temp->type('number');
		$pres = new \Kendo\Data\DataSourceSchemaModelField(	'pm25');
		$pres->type('number');
		$rain = new \Kendo\Data\DataSourceSchemaModelField(	'pm10');
		$rain->type('number');

		$model->id('id')
		->addField($id)
		->addField($date)
		->addField($dev)
		->addField($temp)
		->addField($pres)
		->addField($rain);

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
		$temp = new \Kendo\UI\GridColumn();$temp->field('pm1') ->title('PM1');
		$pres = new \Kendo\UI\GridColumn();$pres->field('pm25') ->title('PM2,5');
		$rain = new \Kendo\UI\GridColumn();$rain->field('pm10') ->title('PM10');
    
		$pageable = new \Kendo\UI\GridPageable();
			$pageable//->input(true)
		->numeric(true)
		->pageSizes([5,10,20,30,50,70,999999]);

		$gridRC = new \Kendo\UI\Grid('pmGrid');

		$gridRC->addColumn($date,$dev,$temp,$pres,$rain)
		->dataSource($dataSource)
		->height(550)
		->sortable(true)
		->editable('inline')
		->filterable(true)
		->mobile(true)
		->pageable($pageable);

		echo $gridRC->render();
	}
	
	function get_grid_all(){
	if($this->import_all_btn){
	
	echo '<div class="importForm">';
	echo '<span style="margin:5px">'.__('Od: ','impactit').'</span>';
		$dateTimePicker = new \Kendo\UI\DateTimePicker('startPM');
		$dateTimePicker->value(new DateTime('now', new DateTimeZone('UTC')))
		->interval(60)
					->change('function(e){		
					  var startDate = jQuery("#startPM").data("kendoDateTimePicker").value();			 
						if (startDate) {
							startDate = new Date(startDate);
							//startDate.setHours(startDate.getHours() + 1);
							jQuery("#endPM").data("kendoDateTimePicker").min(startDate);
						}
					}')
					->max(new DateTime('now', new DateTimeZone('UTC')))
				   ->attr('title', 'datetimepicker')
				   ->dateInput(true);
		echo $dateTimePicker->render();
		
	echo '<span style="margin:5px">'.__('Do: ','impactit').'</span>';
		$dateTimePicker = new \Kendo\UI\DateTimePicker('endPM');
		$dateTimePicker->value(new DateTime('now', new DateTimeZone('UTC')))
		->interval(60)
					->change('function(e){
						 var endDate =  jQuery("#endPM").data("kendoDateTimePicker").value();
						if (endDate) {
							endDate = new Date(endDate);
							endDate.setHours(endDate.getHours() - 1);
							jQuery("#startPM").data("kendoDateTimePicker").max(endDate);
						}
					}')
				//	->min(new DateTime('now', new DateTimeZone('UTC')))
					->max(new DateTime('now', new DateTimeZone('UTC')))
				   ->attr('title', 'datetimepicker')
				   ->dateInput(true);
		echo $dateTimePicker->render();
		
		$textButton = new \Kendo\UI\Button('pmButton');
		$textButton->attr('type', 'button')
			->attr('class', 'importBtn')
				   ->content(__('Import','impactit'))
              ->click('function(e) { 
						var element = jQuery("#pmGridAll");
						var dateStart = jQuery("#startPM").val();
						var dateEnd = jQuery("#endPM").val();
						kendo.ui.progress(element, true);
						jQuery.ajax({
						  type: "POST",
						  url: "'.admin_url( 'admin-ajax.php' ) .'",
						   data: { 
						   action: "import_pm_all",
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
			jQuery("#startPM").data('kendoDateTimePicker').trigger('change');
			});
		</script>
		</div>
		<?php

		}
		//show grid
		$transport = new \Kendo\Data\DataSourceTransport();
		
		$read = new \Kendo\Data\DataSourceTransportRead();

		$read->url(admin_url( 'admin-ajax.php' ).'?action=read_db&type=read&db=pm&all=true')
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
		$temp = new \Kendo\Data\DataSourceSchemaModelField(	'pm1');
		$temp->type('number');
		$pres = new \Kendo\Data\DataSourceSchemaModelField(	'pm25');
		$pres->type('number');
		$rain = new \Kendo\Data\DataSourceSchemaModelField(	'pm10');
		$rain->type('number');

		$model->id('id')
		->addField($id)
		->addField($date)
		->addField($dev)
		->addField($temp)
		->addField($pres)
		->addField($rain);

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
		$temp = new \Kendo\UI\GridColumn();$temp->field('pm1') ->title('PM1');
		$pres = new \Kendo\UI\GridColumn();$pres->field('pm25') ->title('PM2,5');
		$rain = new \Kendo\UI\GridColumn();$rain->field('pm10') ->title('PM10');
    
		$pageable = new \Kendo\UI\GridPageable();
			$pageable//->input(true)
		->numeric(true)
		->pageSizes([5,10,20,30,50,70,999999]);
        		
		$gridRC = new \Kendo\UI\Grid('pmGridAll');

		$gridRC->addColumn($date,$dev,$temp,$pres,$rain)
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
		return $repo->get_single( "*", '', 'sync_date desc');   
}
}?>