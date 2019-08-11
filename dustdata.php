<?php
class DustData
{
	public $table =DUST_TABLE_ALL;
	public $columns = array('id',  'sync_date', 'device_id','ch20_ppb','ch20_ug_m3');
    public $name ="Py�y";
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
		$textButton = new \Kendo\UI\Button('dustBtn');
		$textButton->attr('type', 'button')
			  ->content(__('Import','impactit'))
              ->click('function(e) { 
						var element = jQuery("#dustGrid");
						kendo.ui.progress(element, true);
						jQuery.ajax({
						  type: "POST",
						  url: "'.admin_url( 'admin-ajax.php' ) .'?action=import_dust",
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

		$read->url(admin_url( 'admin-ajax.php' ).'?action=read_db&type=read&db=dust')
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
		$temp = new \Kendo\Data\DataSourceSchemaModelField(	'ch20_ppb');
		$temp->type('number');
		$pres = new \Kendo\Data\DataSourceSchemaModelField(	'ch20_ug_m3');
		$pres->type('number');

		$model->id('id')
		->addField($id)
		->addField($date)
		->addField($dev)
		->addField($temp)
		->addField($pres);

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
		$temp = new \Kendo\UI\GridColumn();$temp->field('ch20_ppb') ->title('ch20_ppb');
		$pres = new \Kendo\UI\GridColumn();$pres->field('ch20_ug_m3') ->title('ch20_ug_m3');
    
		$pageable = new \Kendo\UI\GridPageable();
			$pageable//->input(true)
		->numeric(true)
		->pageSizes([5,10,20,30,50,70,999999]);
        		
		$gridRC = new \Kendo\UI\Grid('dustGrid');

		$gridRC->addColumn($date,$dev,$temp,$pres)
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
		$dateTimePicker = new \Kendo\UI\DateTimePicker('startDust');
		$dateTimePicker->value(new DateTime('now', new DateTimeZone('UTC')))
		->interval(60)
					->change('function(e){		
					  var startDate = jQuery("#startDust").data("kendoDateTimePicker").value();			 
						if (startDate) {
							startDate = new Date(startDate);
							//startDate.setHours(startDate.getHours() + 1);
							jQuery("#endDust").data("kendoDateTimePicker").min(startDate);
						}
					}')
					->max(new DateTime('now', new DateTimeZone('UTC')))
				   ->attr('title', 'datetimepicker')
				   ->dateInput(true);
		echo $dateTimePicker->render();
		
	echo '<span style="margin:5px">'.__('Do: ','impactit').'</span>';
		$dateTimePicker = new \Kendo\UI\DateTimePicker('endDust');
		$dateTimePicker->value(new DateTime('now', new DateTimeZone('UTC')))
		->interval(60)
					->change('function(e){
						 var endDate =  jQuery("#endDust").data("kendoDateTimePicker").value();
						if (endDate) {
							endDate = new Date(endDate);
							endDate.setHours(endDate.getHours() - 1);
							jQuery("#startDust").data("kendoDateTimePicker").max(endDate);
						}
					}')
				//	->min(new DateTime('now', new DateTimeZone('UTC')))
					->max(new DateTime('now', new DateTimeZone('UTC')))
				   ->attr('title', 'datetimepicker')
				   ->dateInput(true);
		echo $dateTimePicker->render();

		$textButton = new \Kendo\UI\Button('dustButton');
		$textButton->attr('type', 'button')
			->attr('class', 'importBtn')
				   ->content(__('Import','impactit'))
              ->click('function(e) { 
						var element = jQuery("#dustGridAll");
						var dateStart = jQuery("#startDust").val();
						var dateEnd = jQuery("#endDust").val();
						kendo.ui.progress(element, true);
						jQuery.ajax({
						  type: "POST",
						  url: "'.admin_url( 'admin-ajax.php' ) .'",
						   data: { 
						   action: "import_dust_all",
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
			jQuery("#startDust").data('kendoDateTimePicker').trigger('change');
			});
		</script>
		</div>
		<?php

		}
		//show grid
		$transport = new \Kendo\Data\DataSourceTransport();
		
		$read = new \Kendo\Data\DataSourceTransportRead();

		$read->url(admin_url( 'admin-ajax.php' ).'?action=read_db&type=read&db=dust&all=true')
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
		$temp = new \Kendo\Data\DataSourceSchemaModelField(	'ch20_ppb');
		$temp->type('number');
		$pres = new \Kendo\Data\DataSourceSchemaModelField(	'ch20_ug_m3');
		$pres->type('number');

		$model->id('id')
		->addField($id)
		->addField($date)
		->addField($dev)
		->addField($temp)
		->addField($pres);

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
		$temp = new \Kendo\UI\GridColumn();$temp->field('ch20_ppb') ->title('ch20_ppb');
		$pres = new \Kendo\UI\GridColumn();$pres->field('ch20_ug_m3') ->title('ch20_ug_m3');
    
		$pageable = new \Kendo\UI\GridPageable();
			$pageable//->input(true)
		->numeric(true)
		->pageSizes([5,10,20,30,50,70,999999]);
        		
		$gridRC = new \Kendo\UI\Grid('dustGridAll');

		$gridRC->addColumn($date,$dev,$temp,$pres)
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