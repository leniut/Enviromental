<?php
class SoundData
{
	public $table =SOUND_TABLE_ALL;
	public $columns = array('id',  'sync_date', 'device_id','leq');
    public $name ="D�wi�k";
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
	$textButton = new \Kendo\UI\Button('soundBtn');
		$textButton->attr('type', 'button')
			  ->content(__('Import','impactit'))
              ->click('function(e) { 
						var element = jQuery("#soundGrid");
						kendo.ui.progress(element, true);
						jQuery.ajax({
						  type: "POST",
						  url: "'.admin_url( 'admin-ajax.php' ) .'?action=import_sound",
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

		$read->url(admin_url( 'admin-ajax.php' ).'?action=read_db&type=read&db=sound')
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
		$temp = new \Kendo\Data\DataSourceSchemaModelField(	'leq');
		$temp->type('number');

		$model->id('id')
		->addField($id)
		->addField($date)
		->addField($dev)
		->addField($temp);

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
		$temp = new \Kendo\UI\GridColumn();$temp->field('leq') ->title('leq');
    
		$pageable = new \Kendo\UI\GridPageable();
			$pageable//->input(true)
		->numeric(true)
		->pageSizes([5,10,20,30,50,70,999999]);

		$gridRC = new \Kendo\UI\Grid('soundGrid');

		$gridRC->addColumn($date,$dev,$temp)
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
		$dateTimePicker = new \Kendo\UI\DateTimePicker('startSound');
		$dateTimePicker->value(new DateTime('now', new DateTimeZone('UTC')))
		->interval(60)
					->change('function(e){		
					  var startDate = jQuery("#startSound").data("kendoDateTimePicker").value();			 
						if (startDate) {
							startDate = new Date(startDate);
							//startDate.setHours(startDate.getHours() + 1);
							jQuery("#endSound").data("kendoDateTimePicker").min(startDate);
						}
					}')
					->max(new DateTime('now', new DateTimeZone('UTC')))
				   ->attr('title', 'datetimepicker')
				   ->dateInput(true);
		echo $dateTimePicker->render();
		
	echo '<span style="margin:5px">'.__('Do: ','impactit').'</span>';
		$dateTimePicker = new \Kendo\UI\DateTimePicker('endSound');
		$dateTimePicker->value(new DateTime('now', new DateTimeZone('UTC')))
		->interval(60)
					->change('function(e){
						 var endDate =  jQuery("#endSound").data("kendoDateTimePicker").value();
						if (endDate) {
							endDate = new Date(endDate);
							endDate.setHours(endDate.getHours() - 1);
							jQuery("#startSound").data("kendoDateTimePicker").max(endDate);
						}
					}')
				//	->min(new DateTime('now', new DateTimeZone('UTC')))
					->max(new DateTime('now', new DateTimeZone('UTC')))
				   ->attr('title', 'datetimepicker')
				   ->dateInput(true);
		echo $dateTimePicker->render();

		$textButton = new \Kendo\UI\Button('soundButton');
		$textButton->attr('type', 'button')
			->attr('class', 'importBtn')
				   ->content(__('Import','impactit'))
              ->click('function(e) { 
						var element = jQuery("#soundGridAll");	
						var dateStart = jQuery("#startSound").val();
						var dateEnd = jQuery("#endSound").val();
						kendo.ui.progress(element, true);
						jQuery.ajax({
						  type: "POST",
						  url: "'.admin_url( 'admin-ajax.php' ) .'",
						   data: { 
						   action: "import_sound_all",
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
			jQuery("#startSound").data('kendoDateTimePicker').trigger('change');
			});
		</script>
		</div>
		<?php

		}
		//show grid
		$transport = new \Kendo\Data\DataSourceTransport();
		
		$read = new \Kendo\Data\DataSourceTransportRead();

		$read->url(admin_url( 'admin-ajax.php' ).'?action=read_db&type=read&db=sound&all=true')
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
		$temp = new \Kendo\Data\DataSourceSchemaModelField(	'leq');
		$temp->type('number');

		$model->id('id')
		->addField($id)
		->addField($date)
		->addField($dev)
		->addField($temp);

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
		$temp = new \Kendo\UI\GridColumn();$temp->field('leq') ->title('leq');
    
		$pageable = new \Kendo\UI\GridPageable();
			$pageable//->input(true)
		->numeric(true)
		->pageSizes([5,10,20,30,50,70,999999]);
        		
		$gridRC = new \Kendo\UI\Grid('soundGridAll');

		$gridRC->addColumn($date,$dev,$temp)
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