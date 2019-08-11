<?php
class WeatherData
{
	public $table =SHOW_TABLE_ALL;
	public $columns = array('id',  'sync_date', 'device_id','temperature','pressure','rain','wind_speed', 'wind_dir','humidity','pm1','pm25','pm10','ch20_ppb','ch20_ug_m3','leq','lzo');
    public $name ="Dane Pogodowe";
	public $import_btn = true;
	
	function table_name($all=false){
		global $wpdb;
		return $wpdb->prefix .$this->table;
	}

	function get_grid(){
	if($this->import_btn){
	echo '<div class="importForm">';
		echo '<span style="margin:5px">'.__('Od: ','impactit').'</span>';
		$dateTimePicker = new \Kendo\UI\DateTimePicker('startWearther');
		$dateTimePicker->value((new DateTime('now', new DateTimeZone('UTC'))))
		->interval(30)
					->change('function(e){
					  var startDate = jQuery("#startWearther").data("kendoDateTimePicker").value();			 
						if (startDate) {
							startDate = new Date(startDate);
							//startDate.setHours(startDate.getHours() + 1);
							jQuery("#endWeather").data("kendoDateTimePicker").min(startDate);
						}
					}')
					->max(new DateTime('now', new DateTimeZone('UTC')))
				   ->attr('title', 'datetimepicker')
				   ->dateInput(true);
		echo $dateTimePicker->render();
		
	echo '<span  style="margin:5px">'.__('Do: ','impactit').'</span>';
		$dateTimePicker = new \Kendo\UI\DateTimePicker('endWeather');
		$dateTimePicker->value(new DateTime('now', new DateTimeZone('UTC')))
		->interval(30)
					->change('function(e){
						 var endDate =  jQuery("#endWeather").data("kendoDateTimePicker").value();
						if (endDate) {
							endDate = new Date(endDate);
							endDate.setHours(endDate.getHours() - 1);
							jQuery("#startWearther").data("kendoDateTimePicker").max(endDate);
						}
					}')
					//->min(new DateTime('now', new DateTimeZone('UTC')))
					->max(new DateTime('now', new DateTimeZone('UTC')))
				   ->attr('title', 'datetimepicker')
				   ->dateInput(true);
		echo $dateTimePicker->render();
		
	$textButton = new \Kendo\UI\Button('weatherBtn');
		$textButton->attr('type', 'button')
			->attr('class', 'importBtn')
			  ->content(__('Przelicz','impactit'))
              ->click('function(e) { 
						var element = jQuery("#weatherGrid");
							var dateStart = jQuery("#startWearther").val();
						var dateEnd = jQuery("#endWeather").val();
						kendo.ui.progress(element, true);
						jQuery.ajax({
						  type: "POST",
						  url: "'.admin_url( 'admin-ajax.php' ) .'",
						  data: { 
						   action: "import_weather",
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
			jQuery("#startWearther").data('kendoDateTimePicker').trigger('change');
			});
		</script>
		</div>
		<?php
		}
		//show grid
		$transport = new \Kendo\Data\DataSourceTransport();
		
		$read = new \Kendo\Data\DataSourceTransportRead();

		$read->url(admin_url( 'admin-ajax.php' ).'?action=read_db&type=read&db=wd')
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
		$pm1 = new \Kendo\Data\DataSourceSchemaModelField(	'pm1');
		$pm1->type('number');
		$pm2 = new \Kendo\Data\DataSourceSchemaModelField(	'pm25');
		$pm2->type('number');
		$pm10 = new \Kendo\Data\DataSourceSchemaModelField(	'pm10');
		$pm10->type('number');

		$ch1 = new \Kendo\Data\DataSourceSchemaModelField(	'ch20_ppb');
		$ch1->type('number');
		$ch2 = new \Kendo\Data\DataSourceSchemaModelField(	'ch20_ug_m3');
		$ch2->type('number');

		$leq = new \Kendo\Data\DataSourceSchemaModelField(	'leq');
		$leq->type('number');
		
		$lzo = new \Kendo\Data\DataSourceSchemaModelField(	'lzo');
		$lzo->type('number');
		
		$model->id('id')
		->addField($id)
		->addField($date)
		->addField($dev)
		->addField($temp)
		->addField($pres)
		->addField($rain)
		->addField($wind_speed)
		->addField($wind_dir)
		->addField($humidity)
		->addField($pm1)
		->addField($pm2)
		->addField($pm10)
		->addField($ch1)
		->addField($ch2)
		->addField($leq)
		->addField($lzo);
		
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
		$pm1 = new \Kendo\UI\GridColumn();$pm1->field('pm1') ->title('PM1');
		$pm2 = new \Kendo\UI\GridColumn();$pm2->field('pm25') ->title('PM2,5');
		$pm10 = new \Kendo\UI\GridColumn();$pm10->field('pm10') ->title('PM10');
		$ch1 = new \Kendo\UI\GridColumn();$ch1->field('ch20_ppb') ->title('ch20_ppb');
		$ch2 = new \Kendo\UI\GridColumn();$ch2->field('ch20_ug_m3') ->title('ch20_ug_m3');
		$leq = new \Kendo\UI\GridColumn();$leq->field('leq') ->title('leq');
		$lzo = new \Kendo\UI\GridColumn();$lzo->field('lzo') ->title('lzo');
    
		$pageable = new \Kendo\UI\GridPageable();
			$pageable//->input(true)
		->numeric(true)
		->pageSizes([5,10,20,30,50,70,999999]);
        		
		$gridRC = new \Kendo\UI\Grid('weatherGrid');

		$gridRC->addColumn($date,$dev,$temp,$pres,$rain,$wind_speed,$wind_dir,$humidity,$pm1,$pm2,$pm10,$ch1,$ch2,$leq,$lzo)
		->dataSource($dataSource)
		->height(550)
		->sortable(true)
		->editable('inline')
		->filterable(true)
		->mobile(true)
		->pageable($pageable);

		echo $gridRC->render();
	}
	
	function get_data($select,$where='',$sort=''){
		 try {
			 global $wpdb;
            $wpdb->show_errors();
            if($where!=''){
                $where = "WHERE ".$where;}

            if($sort!=''){
                $sort = "ORDER BY ".$sort;}

            $data = $wpdb->get_results( "SELECT $select FROM ".$wpdb->prefix.$this->table." $where $sort" );
          
            return $data;
        }
        catch (Exception $e) {
            return array('Caught exception: ',  $e->getMessage(), "\n");
        }
	}
	function get_last_data(){
		
		$repo = new Table_Repository($this->table);
		return $repo->get_single( "*",  '', 'sync_date desc');   

	}
}?>