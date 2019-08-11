<?php

/**
 * Plugin Name: Enviromental Data
 * Description: Zawiera wszystkie niezbędne dane do wizualizacji danych z stacji meteo
 * Plugin URI: 
 * Version: 1.0.1
 * Author: Impact IT
 * Author URI: http://impact-it.pl
 * Text Domain: enviromental-data
 * Author; Andrzej 'Tuinel' Siwek
 */

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly 

/**
 * DEFINE TABLES NAMES
 */

define('METEO_TABLE_ALL', 'env_meteo_data_all'); //dane meteorlogiczne src
define('PM_TABLE_ALL', 'env_pm_data_all'); // dane PM src
define('DUST_TABLE_ALL', 'env_dust_data_all'); // dane zapylenia (chemiczne) src
define('SOUND_TABLE_ALL', 'env_sound_data_all'); // dane hałasu src
define('ATMO_TABLE_ALL', 'env_atmo_data_all'); // dane lzo src
define('SHOW_TABLE_ALL', 'env_show_data_all'); // dane po przeliczeniu do wyswietlenia
define('SENSOR_CORECTION_TABLE', 'env_sensor_corection'); // korekcja sensorów
define('DATA_CLAMP_TABLE', 'env_data_clamp_table'); // ogranicznie wartości


/**
 * conts array with correction table values
 */
define('DEFAULT_DATA_TABLES', array('temperature', 'pressure', 'rain', 'wind_speed', 'wind_dir', 'humidity', 'pm1', 'pm25', 'pm10', 'ch20_ppb', 'ch20_ug_m3', 'leq', 'lzo', 'lzo'));


/**
 * **********************************************************************
 * Create tables for this module
 * 
 * @return void
 * **********************************************************************
 */
function tablesInstall()
{
    global $wpdb;
    include_once ABSPATH . 'wp-admin/includes/upgrade.php';


    $table_name1 = $wpdb->prefix . METEO_TABLE_ALL;
    $table_name2 = $wpdb->prefix . PM_TABLE_ALL;
    $table_name3 = $wpdb->prefix . DUST_TABLE_ALL;
    $table_name4 = $wpdb->prefix . SOUND_TABLE_ALL;
    $table_name5 = $wpdb->prefix . ATMO_TABLE_ALL;
    $table_name6 = $wpdb->prefix . SHOW_TABLE_ALL;
    $table_name7 = $wpdb->prefix . SENSOR_CORECTION_TABLE;
    $table_name8 = $wpdb->prefix . DATA_CLAMP_TABLE;

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name1 (
        id int(11) NOT NULL AUTO_INCREMENT,
        sync_date datetime NOT NULL,
        device_id int(11) NULL,
        temperature float	NULL,
        pressure float NULL,
        rain float NULL,
        wind_speed float NULL,
        wind_dir float NULL,
        humidity float NULL,
        UNIQUE KEY id (id)
    ) $charset_collate;";

    dbDelta($sql);

    $sql = "CREATE TABLE $table_name2 (
            id int(11) NOT NULL AUTO_INCREMENT, 
            sync_date datetime NOT NULL,
            device_id int(11) NULL,
            pm1	float NULL,
            pm25 float NULL,
            pm10 float NULL,
            UNIQUE KEY id (id)
        ) $charset_collate;";

    dbDelta($sql);

    $sql = "CREATE TABLE $table_name3 (
            id int(11) NOT NULL AUTO_INCREMENT, 
            sync_date datetime NOT NULL,
            device_id int(11) NULL,
            ch20_ppb double NULL,
            ch20_ug_m3 double NULL,
            UNIQUE KEY id (id)
        ) $charset_collate;";

    dbDelta($sql);


    $sql = "CREATE TABLE $table_name4 (
            id int(11) NOT NULL AUTO_INCREMENT, 
            sync_date datetime NOT NULL,
            device_id int(11) NULL,
            leq float NULL,
            UNIQUE KEY id (id)
        ) $charset_collate;";

    dbDelta($sql);

    $sql = "CREATE TABLE $table_name5 (
            id int(11) NOT NULL AUTO_INCREMENT,
            sync_date datetime NOT NULL,
            device_id int(11) NULL,
            lzo double NULL,
            UNIQUE KEY id (id)
        ) $charset_collate;";

    dbDelta($sql);

    $sql = "CREATE TABLE $table_name6 (
			id int(11) NOT NULL AUTO_INCREMENT,
			sync_date datetime NOT NULL,
			device_id int(11) NULL,
			temperature float NULL,
			temperature_correction float NULL,
			pressure float NULL,
			pressure_correction float NULL,
			rain float NULL,
			rain_correction float NULL,
			wind_speed float NULL,
			wind_speed_correction float NULL,
			wind_dir float NULL,
			wind_dir_correction float NULL,
			humidity float NULL,
			humidity_correction float NULL,
            pm1 float NULL,
            pm1_correction float NULL,
			pm25 float NULL,
			pm25_correction float NULL,
			pm10 float NULL,
			pm10_correction float NULL,
			ch20_ppb double NULL,
			ch20_ppb_correction double NULL,
			ch20_ug_m3 double NULL,
			ch20_ug_m3_correction double NULL,
			leq float NULL,
			leq_correction float NULL,
			lzo double NULL,
			lzo_correction double NULL,
			UNIQUE KEY id (id)
		) $charset_collate;";

    dbDelta($sql);

    $sql = "CREATE TABLE $table_name7 (
        id int(11) NOT NULL AUTO_INCREMENT,
        device_id int(11) NULL,
        sensor_type varchar(255) NOT NULL,
        correction float NOT NULL,
        UNIQUE KEY id (id)
    ) $charset_collate;";

    dbDelta($sql);

    $sql = "CREATE TABLE $table_name8 (
        id int(11) NOT NULL AUTO_INCREMENT,
        device_id int(11) NULL,
        sensor_type varchar(255) NOT NULL,
        clamp_down float NOT NULL,
        clamp_up float NOT NULL,
        UNIQUE KEY id (id)
    ) $charset_collate;";

    dbDelta($sql);


    $sql = "CREATE INDEX my_index ON {$table_name6} (sync_date)";

    $wpdb->query($sql);
}

register_activation_hook(__FILE__, 'tablesInstall');

/**
 * Add tables to admin menu
 * 
 * @return void
 */
function enviroAdminMenu()
{
    add_menu_page('Tabele Administration', 'Tabele', 'manage_options', 'tables-admin-page.php', 'myplguin_admin_page', 'dashicons-grid-view', 26);
}
add_action('admin_menu', 'enviroAdminMenu');

require 'meteodata.php';
require 'pmdata.php';
require 'dustdata.php';
require 'sounddata.php';
require 'atmodata.php';
require 'weatherdata.php';
require 'table_repository.php';
require 'sensor-correction.php';
require 'sensor-clamp.php';

function myplguin_admin_page()
{

    ?>
    <style>
        .importBtn {
            margin-left: 10px;
        }

        .saveMsg {
            margin: 10px 0;
            padding: 10px;
            background-color: lightgreen;
        }

        .importForm {
            margin-bottom: 10px;
            display: <?php echo get_devices() != '' && get_devices() != null ? 'block' : 'none' ?>
        }
    </style>
    <?php


    $rc = new MeteoData();
    $rb = new PMData();
    $ds = new DustData();
    $sd = new SoundData();
    $atm = new AtmoData();
    $wt = new WeatherData();
    $sc = new SensorCorrection();
    $scla = new SensorClamp();

    ?>

    <div class="wrap">
        <h2><?php _e('Tabele', 'impactit') ?></h2>

        <?php
        $tabstrip = new \Kendo\UI\TabStrip('tabstrip');

        // set items
        $wtItem = new \Kendo\UI\TabStripItem();

        $wtItem->text("Dane Pogodowe")
            ->selected(true)
            ->startContent();
        ?>
        <?php $wt->get_grid(); ?>
        <?php
        $wtItem->endContent();

        $rcItem2 = new \Kendo\UI\TabStripItem();

        $rcItem2->text("Wszystkie dane pogodowe")
            ->startContent();
        ?>
        <?php $rc->get_grid_all(); ?>
        <?php
        $rcItem2->endContent();


        $rbItem2 = new \Kendo\UI\TabStripItem();
        $rbItem2->text("Wszystkie dane jakości powietrza")
            ->startContent();
        ?>
        <?php $rb->get_grid_all(); ?>
        <?php
        $rbItem2->endContent();

        $dsItem2 = new \Kendo\UI\TabStripItem();
        $dsItem2->text("Wszystkie dane pyłów")
            ->startContent();
        ?>
        <?php $ds->get_grid_all(); ?>
        <?php
        $dsItem2->endContent();

        $sdItem2 = new \Kendo\UI\TabStripItem();
        $sdItem2->text("Wszystkie dane dźwięków")
            ->startContent();
        ?>
        <?php $sd->get_grid_all(); ?>
        <?php
        $sdItem2->endContent();

        $atmoItem2 = new \Kendo\UI\TabStripItem();
        $atmoItem2->text("Wszystkie dane atmosfery")
            ->startContent();
        ?>
        <?php $atm->get_grid_all(); ?>
        <?php
        $atmoItem2->endContent();

        $corItem2 = new \Kendo\UI\TabStripItem();
        $corItem2->text("Korekcja czujników")
            ->startContent();
        ?>
        <?php $sc->get_grid_all(); ?>
        <?php
        $corItem2->endContent();

        $clampItem2 = new \Kendo\UI\TabStripItem();
        $clampItem2->text("Przycinanie wartości")
            ->startContent();
        ?>
        <?php $scla->get_grid_all(); ?>
        <?php
        $clampItem2->endContent();

        $optionItem = new \Kendo\UI\TabStripItem();
        $optionItem->text("Opcje")
            ->startContent();
        ?>
        <form>
            <p>
                <span><?php _e('Device Ids:', 'impactit') ?></span>
                <input type="text" name="devices" value="<?php echo get_devices() ?>"></input>
            </p>
        </form>
        <p id="savemessage" class="saveMsg" style="display:none"></p>
        <?php

        $textButton = new \Kendo\UI\Button('optionsBtn');
        $textButton->attr('type', 'button')
            ->attr('class', 'saveBtn')
            ->content(__('Save', 'impactit'))
            ->click(
                'function(e) { 
						jQuery.ajax({
						  type: "POST",
						  url: "' . admin_url('admin-ajax.php') . '",
						  data: { 
							   action: "save_weather_options",
							   formdata:jQuery( "form" ).serialize()
						   }
						}).done(function( msg ) {
							jQuery("#savemessage").html(msg);
							jQuery("#savemessage").show();
							if(jQuery("[name=devices]").val()!=""){
								jQuery(".importForm").show();
							}else{
								jQuery(".importForm").hide();
							}
							  setTimeout(function() {
								jQuery("#savemessage").hide();
							}, 3000);
						}).fail(function() {
							jQuery("#savemessage").html("error");
						});
				}'
            );

        echo $textButton->render();

        $optionItem->endContent();

        $tabstrip->addItem($wtItem, $rcItem2, $rbItem2, $dsItem2, $sdItem2, $atmoItem2, $corItem2, $clampItem2, $optionItem);

        // set animation
        $animation = new \Kendo\UI\TabStripAnimation();
        $openAnimation = new \Kendo\UI\TabStripAnimationOpen();
        $openAnimation->effects("fadeIn");
        $animation->open($openAnimation);

        $tabstrip->animation($animation);

        echo $tabstrip->render();
        ?>
    </div>
<?php
}

function get_devices()
{
    return get_option('weather_device');
}

function save_weather_options()
{
    try {
        parse_str($_POST['formdata'], $formdata); //This will convert the string to array
        $devices = $formdata['devices'];
        update_option('weather_device', $devices);
        die("Saved");
    } catch (Exception $e) {
        die($e->message);
    }
}
add_action('wp_ajax_save_weather_options', 'save_weather_options');

/**
 * Import data from src databse
 * 
 * @return void
 */

function group_by_cron($filed, $old_arr, $return_data)
{
    $result = array();
    foreach ($old_arr as $data) {
        $id = $return_data($data[$filed]);
        if (isset($result[$id])) {
            $result[$id][] = $data;
        } else {
            $result[$id] = array($data);
        }
    }
    return $result;
}

// Full import
function imporMeteotData()
{
    global $wpdb;
    $meteoDb = $wpdb->prefix . METEO_TABLE_ALL;
    try {
        $mysqli = new mysqli('83.12.141.66', 'mielec', 'alamakota', 'mielec_data');
        if ($mysqli->connect_error) {
            die('Wystąpił błąd z połączeniem: ' . $mysqli->connect_error);
            return;
        }
        $sql = "SELECT date_format( measurement_time, '%Y-%m-%d %H:00:00' )  + INTERVAL 1 HOUR  as sync_date,device_id, ROUND(AVG(temperature),1) as temperature,ROUND( AVG(pressure),0) as pressure, ROUND(AVG(rain),1) as rain,ROUND(AVG(wind_speed),1) as wind_speed, ROUND(AVG(wind_dir),0) as wind_dir, ROUND(AVG(humidity),0)as humidity  FROM meteo_data GROUP BY date_format( measurement_time, '%Y%m%d%H' ),device_id";
        $result = $mysqli->query($sql);

        if ($result->num_rows > 0) {
            $wpdb->query("TRUNCATE TABLE `$meteoDb`");
            while ($row = $result->fetch_assoc()) {
                $wpdb->insert($meteoDb, $row);
                echo $wpdb->last_error;
            }
        }

        $mysqli->close();
        die('Import finished!');
    } catch (Exception $e) {    // Database Error
        die('Wystąpił błąd: ' . $e->getMessage());
    }
}
add_action('wp_ajax_import_meteo', 'imporMeteotData');

function importPMData()
{
    global $wpdb;
    $pmDb = $wpdb->prefix . PM_TABLE_ALL;
    try {
        $mysqli = new mysqli('83.12.141.66', 'mielec', 'alamakota', 'mielec_data');
        if ($mysqli->connect_error) {
            die('Wystąpił błąd z połączeniem: ' . $mysqli->connect_error);
            return;
        }

        $sql = "SELECT  date_format( measurement_time, '%Y-%m-%d %H:00:00' ) + INTERVAL 1 HOUR as sync_date,device_id, ROUND(AVG(pm1),6) as pm1,ROUND( AVG(pm25),6) as pm25, ROUND(AVG(pm10),6) as pm10 FROM fardata_pm_raw_data GROUP BY date_format( measurement_time, '%Y%m%d%H' ),device_id";
        $result = $mysqli->query($sql);
        if ($result->num_rows > 0) {
            $wpdb->query("TRUNCATE TABLE `$pmDb`");

            while ($row = $result->fetch_assoc()) {
                //    echo print_r($row).'<br>';
                $wpdb->insert($pmDb, $row);
                echo $wpdb->last_error;
            }
        }

        $mysqli->close();
        die('Import finished!');
    } catch (Exception $e) {    // Database Error
        die('Wystąpił błąd: ' . $e->getMessage());
    }
}
add_action('wp_ajax_import_pm', 'importPMData');

function importDustData()
{
    global $wpdb;
    $pmDb = $wpdb->prefix . DUST_TABLE_ALL;
    try {
        $mysqli = new mysqli('83.12.141.66', 'mielec', 'alamakota', 'mielec_data');
        if ($mysqli->connect_error) {
            die('Wystąpił błąd z połączeniem: ' . $mysqli->connect_error);
            return;
        }

        $sql = "SELECT  date_format( measurement_time, '%Y-%m-%d %H:00:00' )  + INTERVAL 1 HOUR as sync_date,device_id, ROUND(AVG(ch20_ppb),6) as ch20_ppb,ROUND( AVG(ch20_ug_m3),6) as ch20_ug_m3 FROM CH2O_sensor GROUP BY date_format( measurement_time, '%Y%m%d%H' ),device_id";
        $result = $mysqli->query($sql);
        if ($result->num_rows > 0) {
            $wpdb->query("TRUNCATE TABLE `$pmDb`");

            while ($row = $result->fetch_assoc()) {
                //    echo print_r($row).'<br>';
                $wpdb->insert($pmDb, $row);
                echo $wpdb->last_error;
            }
        }

        $mysqli->close();
        die('Import finished!');
    } catch (Exception $e) {    // Database Error
        die('Wystąpił błąd: ' . $e->getMessage());
    }
}
add_action('wp_ajax_import_dust', 'importDustData');

function importSoundData()
{
    global $wpdb;
    $pmDb = $wpdb->prefix . SOUND_TABLE_ALL;
    try {
        $mysqli = new mysqli('83.12.141.66', 'mielec', 'alamakota', 'mielec_data');
        if ($mysqli->connect_error) {
            die('Wystąpił błąd z połączeniem: ' . $mysqli->connect_error);
            return;
        }

        $sql = "SELECT   measurement_time as sync_date, leq FROM dzwiek";
        $result = $mysqli->query($sql);
        if ($result->num_rows > 0) {
            $wpdb->query("TRUNCATE TABLE `$pmDb`");

            while ($row = $result->fetch_assoc()) {
                //    echo print_r($row).'<br>';
                $wpdb->insert($pmDb, $row);
                echo $wpdb->last_error;
            }
        }

        $mysqli->close();
        die('Import finished!');
    } catch (Exception $e) {    // Database Error
        die('Wystąpił błąd: ' . $e->getMessage());
    }
}
add_action('wp_ajax_import_sound', 'importSoundData');

function importAtmoData()
{
    global $wpdb;
    $meteoDb = $wpdb->prefix . ATMO_TABLE_ALL;
    try {
        $mysqli = new mysqli('83.12.141.66', 'mielec', 'alamakota', 'mielec_data');
        if ($mysqli->connect_error) {
            die('Wystąpił błąd z połączeniem: ' . $mysqli->connect_error);
            return;
        }
        $sql = "SELECT date_format( measurement_time, '%Y-%m-%d %H:00:00' )  + INTERVAL 1 HOUR  as sync_date,device_id, LZO FROM atmo_data GROUP BY date_format( measurement_time, '%Y%m%d%H' ),device_id";
        $result = $mysqli->query($sql);

        if ($result->num_rows > 0) {
            $wpdb->query("TRUNCATE TABLE `$meteoDb`");
            while ($row = $result->fetch_assoc()) {
                $wpdb->insert($meteoDb, $row);
                echo $wpdb->last_error;
            }
        }

        $mysqli->close();
        die('Import finished!');
    } catch (Exception $e) {    // Database Error
        die('Wystąpił błąd: ' . $e->getMessage());
    }
}
add_action('wp_ajax_import_atmo', 'importAtmoData');



function my_cron_schedules($schedules)
{
    if (!isset($schedules["5min"])) {
        $schedules["5min"] = array(
            'interval' => 5 * 60,
            'display' => __('Once every 5 minutes')
        );
    }
    if (!isset($schedules["30min"])) {
        $schedules["30min"] = array(
            'interval' => 30 * 60,
            'display' => __('Once every 30 minutes')
        );
    }
    return $schedules;
}
add_filter('cron_schedules', 'my_cron_schedules');

//Schedule an action if it's not already scheduled
if (!wp_next_scheduled('myprefix_cron_hook')) {
    wp_schedule_event(time(), '5min', 'myprefix_cron_hook');
}

///Hook into that action that'll fire every six hours
add_action('myprefix_cron_hook', 'myprefix_cron_function');

//create your function, that runs on cron
function myprefix_cron_function()
{
    //your function...
    cronImport();
    //wp_mail( 'kontakt@tuinel.eu', 'Automatic email from test', 'Automatic scheduled email from WordPress.');
}

/**
 * Import data by Wordpress cron
 * 
 * @return void
 */
function cronImport()
{
    imporMeteotDataAll(true);
    importPMDataAll(true);
    importDustDataAll(true);
    importSoundDataAll(true);
    importAtmoDataAll(true);

    imporWeatherData(true);
}

/**
 * IMPORT: WSZYSTKIE DANE POGODOWE PO DACIE
 * 
 * @return void
 */
function imporMeteotDataAll($isCron = false)
{
    try {

        global $wpdb;
        $meteoDb = $wpdb->prefix . METEO_TABLE_ALL;
        $ids = get_devices();

        if (!$isCron) {
            $startDate = $_POST['start'];
            $date = new DateTime($startDate);
            $startDate = $date->format('YmdHis');

            $endDate = $_POST['end'];
            $date = new DateTime($endDate);
            $endDate = $date->format('YmdHis');
        } else {
            $startDate = $wpdb->get_var("SELECT sync_date FROM $meteoDb ORDER BY sync_date DESC LIMIT 1");
            $date = new DateTime($startDate);
            $startDate = $date->format('YmdHis');

            //date_default_timezone_set('Europe/Warsaw');
            //$date = current_time('mysql');
            $date = new DateTime(current_time('mysql'));
            $endDate = $date->format('YmdHis');
        }


        try {
            $mysqli = new mysqli('83.12.141.66', 'mielec', 'alamakota', 'mielec_data');
            if ($mysqli->connect_error) {
                die('Wystąpił błąd z połączeniem: ' . $mysqli->connect_error);
                return;
            }
            // pobieranie danych z tabeli src
            $sql = "SELECT measurement_time as sync_date,device_id, temperature, pressure, rain,wind_speed,wind_dir,humidity FROM meteo_data WHERE date_format( measurement_time, '%Y%m%d%H%i%s' ) >= $startDate AND  date_format( measurement_time, '%Y%m%d%H%i%s' ) < $endDate AND device_id IN (" . $ids . ")";
            $result = $mysqli->query($sql);

            //var_dump($result);
            //echo $sql;
            //echo $result->num_rows;

            if (!$result) {
                die('Wystąpił błąd ' . $mysqli->error);
                return;
            }
            if ($result->num_rows > 0) {
                //kasowanie danych w zcelu zastapienia nowymi
                $wpdb->query("DELETE FROM `$meteoDb` WHERE date_format( sync_date, '%Y%m%d%H%i%s' ) >= $startDate AND  date_format( sync_date, '%Y%m%d%H%i%s' ) < $endDate");

                //dodawanie danych do tabeli                
                while ($row = $result->fetch_assoc()) {
                    $wpdb->insert($meteoDb, $row);
                    echo $wpdb->last_error;
                }
            }

            $mysqli->close();
            return;
            //die('Import zakończony!');
        } catch (Exception $e) {    // Database Error
            die('Wystąpił błąd: ' . $e->getMessage());
        }
    } catch (Exception $e) {    // Database Error
        $wpDb->rollback();
        file_put_contents($myFile, "\xEF\xBB\xBF Error inserting pm: " . $wpdb->error, FILE_APPEND);
    }
}
add_action('wp_ajax_import_meteo_all', 'imporMeteotDataAll');

function importPMDataAll($isCron = false)
{
    try {

        global $wpdb;
        $ids =  get_devices();
        $meteoDb = $wpdb->prefix . PM_TABLE_ALL;

        //$startDate = $_POST['start'];
        //$date = new DateTime($startDate);
        //$startDate = $date->format('YmdHis');

        //$endDate = $_POST['end'];
        //$date = new DateTime($endDate);
        //$endDate = $date->format('YmdHis');

        if (!$isCron) {
            $startDate = $_POST['start'];
            $date = new DateTime($startDate);
            $startDate = $date->format('YmdHis');

            $endDate = $_POST['end'];
            $date = new DateTime($endDate);
            $endDate = $date->format('YmdHis');
        } else {
            $startDate = $wpdb->get_var("SELECT sync_date FROM $meteoDb ORDER BY sync_date DESC LIMIT 1");
            $date = new DateTime($startDate);
            $startDate = $date->format('YmdHis');

            //date_default_timezone_set('Europe/Warsaw');
            //$date = current_time('mysql');
            $date = new DateTime(current_time('mysql'));
            $endDate = $date->format('YmdHis');
        }


        try {
            $mysqli = new mysqli('83.12.141.66', 'mielec', 'alamakota', 'mielec_data');
            if ($mysqli->connect_error) {
                die('Wystąpił błąd z połączeniem: ' . $mysqli->connect_error);
                return;
            }
            $sql = "SELECT measurement_time as sync_date,device_id, pm1, pm25, pm10 FROM fardata_pm_raw_data  WHERE date_format( measurement_time, '%Y%m%d%H%i%s' ) >= $startDate AND  date_format( measurement_time, '%Y%m%d%H%i%s' ) < $endDate AND device_id IN (" . $ids . ")";
            $result = $mysqli->query($sql);
            if (!$result) {
                die('Wystąpił błąd ' . $mysqli->error);
                return;
            }
            if ($result->num_rows > 0) {
                $wpdb->query("DELETE FROM `$meteoDb` WHERE date_format( sync_date, '%Y%m%d%H%i%s' ) >= $startDate AND  date_format( sync_date, '%Y%m%d%H%i%s' ) < $endDate");
                while ($row = $result->fetch_assoc()) {
                    $wpdb->insert($meteoDb, $row);
                    echo $wpdb->last_error;
                }
            }

            $mysqli->close();
            return;
            //die('Import zakończony!');
        } catch (Exception $e) {    // Database Error
            die('Wystąpił błąd: ' . $e->getMessage());
        }
    } catch (Exception $e) {    // Database Error
        $wpDb->rollback();
        echo  "\xEF\xBB\xBF Error inserting pm: " . $wpdb->error;
    }
}
add_action('wp_ajax_import_pm_all', 'importPMDataAll');

function importDustDataAll($isCron = false)
{

    try {
        global $wpdb;
        $ids =  get_devices();
        $meteoDb = $wpdb->prefix . DUST_TABLE_ALL;

        if (!$isCron) {
            $startDate = $_POST['start'];
            $date = new DateTime($startDate);
            $startDate = $date->format('YmdHis');

            $endDate = $_POST['end'];
            $date = new DateTime($endDate);
            $endDate = $date->format('YmdHis');
        } else {
            $startDate = $wpdb->get_var("SELECT sync_date FROM $meteoDb ORDER BY sync_date DESC LIMIT 1");
            $date = new DateTime($startDate);
            $startDate = $date->format('YmdHis');

            //date_default_timezone_set('Europe/Warsaw');
            //$date = current_time('mysql');
            $date = new DateTime(current_time('mysql'));
            $endDate = $date->format('YmdHis');
        }


        try {
            $mysqli = new mysqli('83.12.141.66', 'mielec', 'alamakota', 'mielec_data');
            if ($mysqli->connect_error) {
                die('Wystąpił błąd z połączeniem: ' . $mysqli->connect_error);
                return;
            }
            $sql = "SELECT measurement_time as sync_date,device_id, ch20_ppb, ch20_ug_m3 FROM CH2O_sensor WHERE date_format( measurement_time, '%Y%m%d%H%i%s' ) >= $startDate AND  date_format( measurement_time, '%Y%m%d%H%i%s' ) < $endDate AND device_id IN (" . $ids . ")";
            $result = $mysqli->query($sql);
            if (!$result) {
                die('Wystąpił błąd ' . $mysqli->error);
                return;
            }
            if ($result->num_rows > 0) {
                $wpdb->query("DELETE FROM `$meteoDb` WHERE date_format( sync_date, '%Y%m%d%H%i%s' ) >= $startDate AND  date_format( sync_date, '%Y%m%d%H%i%s' ) < $endDate");
                while ($row = $result->fetch_assoc()) {
                    $wpdb->insert($meteoDb, $row);
                    echo $wpdb->last_error;
                }
            }

            $mysqli->close();
            return;
            //die('Import zakończony!');
        } catch (Exception $e) {    // Database Error
            die('Wystąpił błąd: ' . $e->getMessage());
        }
    } catch (Exception $e) {    // Database Error
        $wpDb->rollback();
        file_put_contents($myFile, "\xEF\xBB\xBF Error inserting dust: " . $wpdb->error, FILE_APPEND);
    }
}
add_action('wp_ajax_import_dust_all', 'importDustDataAll');

function importSoundDataAll($isCron = false)
{
    try {
        global $wpdb;
        $ids =  get_devices();
        $meteoDb = $wpdb->prefix . SOUND_TABLE_ALL;

        if (!$isCron) {
            $startDate = $_POST['start'];
            $date = new DateTime($startDate);
            $startDate = $date->format('YmdHis');

            $endDate = $_POST['end'];
            $date = new DateTime($endDate);
            $endDate = $date->format('YmdHis');
        } else {
            $startDate = $wpdb->get_var("SELECT sync_date FROM $meteoDb ORDER BY sync_date DESC LIMIT 1");
            $date = new DateTime($startDate);
            $startDate = $date->format('YmdHis');

            //date_default_timezone_set('Europe/Warsaw');
            //$date = current_time('mysql');
            $date = new DateTime(current_time('mysql'));
            $endDate = $date->format('YmdHis');
        }


        try {
            $mysqli = new mysqli('83.12.141.66', 'mielec', 'alamakota', 'mielec_data');
            if ($mysqli->connect_error) {
                die('Wystąpił błąd z połączeniem: ' . $mysqli->connect_error);
                return;
            }
            $sql = "SELECT measurement_time as sync_date, device_id, leq FROM dzwiek WHERE date_format( measurement_time, '%Y%m%d%H%i%s' ) >= $startDate AND  date_format( measurement_time, '%Y%m%d%H%i%s' ) < $endDate AND device_id IN (" . $ids . ")";
            $result = $mysqli->query($sql);
            if (!$result) {
                die('Wystąpił błąd ' . $mysqli->error);
                return;
            }
            if ($result->num_rows > 0) {
                $wpdb->query("DELETE FROM `$meteoDb` WHERE date_format( sync_date, '%Y%m%d%H%i%s' ) >= $startDate AND  date_format( sync_date, '%Y%m%d%H%i%s' ) < $endDate");
                while ($row = $result->fetch_assoc()) {
                    $wpdb->insert($meteoDb, $row);
                    echo $wpdb->last_error;
                }
            }

            $mysqli->close();
            return;
            //die('Import zakończony!');
        } catch (Exception $e) {    // Database Error
            die('Wystąpił błąd: ' . $e->getMessage());
        }
    } catch (Exception $e) {    // Database Error
        $wpDb->rollback();
        die("Error inserting sound: " . $wpdb->error);
    }
}
add_action('wp_ajax_import_sound_all', 'importSoundDataAll');

function importAtmoDataAll($isCron = false)
{
    try {
        global $wpdb;
        $ids =  get_devices();
        $meteoDb = $wpdb->prefix . ATMO_TABLE_ALL;

        if (!$isCron) {
            $startDate = $_POST['start'];
            $date = new DateTime($startDate);
            $startDate = $date->format('YmdHis');

            $endDate = $_POST['end'];
            $date = new DateTime($endDate);
            $endDate = $date->format('YmdHis');
        } else {
            $startDate = $wpdb->get_var("SELECT sync_date FROM $meteoDb ORDER BY sync_date DESC LIMIT 1");
            $date = new DateTime($startDate);
            $startDate = $date->format('YmdHis');

            //date_default_timezone_set('Europe/Warsaw');
            //$date = current_time('mysql');
            $date = new DateTime(current_time('mysql'));
            $endDate = $date->format('YmdHis');
        }


        try {
            $mysqli = new mysqli('83.12.141.66', 'mielec', 'alamakota', 'mielec_data');
            if ($mysqli->connect_error) {
                die('Wystąpił błąd z połączeniem: ' . $mysqli->connect_error);
                return;
            }
            $sql = "SELECT measurement_time as sync_date,device_id, LZO FROM atmo_data  WHERE date_format( measurement_time, '%Y%m%d%H%i%s' ) >= $startDate AND  date_format( measurement_time, '%Y%m%d%H%i%s' ) < $endDate AND device_id IN (" . $ids . ")";
            $result = $mysqli->query($sql);
            if (!$result) {
                die('Wystąpił błąd ' . $mysqli->error);
                return;
            }
            if ($result->num_rows > 0) {
                $wpdb->query("DELETE FROM `$meteoDb` WHERE date_format( sync_date, '%Y%m%d%H%i%s' ) >= $startDate AND  date_format( sync_date, '%Y%m%d%H%i%s' ) < $endDate");
                while ($row = $result->fetch_assoc()) {
                    $wpdb->insert($meteoDb, $row);
                    echo $wpdb->last_error;
                }
            }

            $mysqli->close();
            return;
            //die('Import zakończony!');
        } catch (Exception $e) {    // Database Error
            die('Wystąpił błąd: ' . $e->getMessage());
        }
    } catch (Exception $e) {    // Database Error
        $wpDb->rollback();
        echo  "\xEF\xBB\xBF Error inserting pm: " . $wpdb->error;
    }
}
add_action('wp_ajax_import_atmo_all', 'importAtmoDataAll');

/**
 * Import and flat/ correct/ data for user
 */
function imporWeatherData($isCron = false)
{
    global $wpdb;

    try {
        $table_name = $wpdb->prefix . SHOW_TABLE_ALL;
        $met = $wpdb->prefix . METEO_TABLE_ALL;
        $pm = $wpdb->prefix . PM_TABLE_ALL;
        $dus = $wpdb->prefix . DUST_TABLE_ALL;
        $sou = $wpdb->prefix . SOUND_TABLE_ALL;
        $atmo = $wpdb->prefix . ATMO_TABLE_ALL;
        $cor = $wpdb->prefix . SENSOR_CORECTION_TABLE;

        if (!$isCron) {
            $startDate = $_POST['start'];
            $date = new DateTime($startDate);
            $startDate = $date->format('YmdHis');

            $endDate = $_POST['end'];
            $date = new DateTime($endDate);
            $endDate = $date->format('YmdHis');
        } else {
            $startDate = $wpdb->get_var("SELECT sync_date FROM $table_name WHERE sync_date >= DATE_ADD(CURDATE(),INTERVAL -7 DAY) ORDER BY sync_date LIMIT 1");
            $date = new DateTime($startDate);
            $startDate = $date->format('YmdHis');

            //date_default_timezone_set('Europe/Warsaw');
            //$date = current_time('mysql');
            $date = new DateTime(current_time('mysql'));
            $endDate = $date->format('YmdHis');
            $deleteEndDate = date("YmdHis", strtotime('+1 hours', strtotime(current_time('mysql'))));
        }


        $sql0 = "SELECT id, sensor_type, correction, device_id FROM $cor WHERE 1 = 1";

        //date_format( sync_date, '%Y-%m-%d %H:00:00' )  + INTERVAL 1 HOUR as sync_dat
        //meteo
        $sql1 = "SELECT date_format( sync_date, '%Y-%m-%d %H:00:00' ) + INTERVAL 1 HOUR  as sync_date,device_id, ROUND(AVG(temperature),1) as temperature,ROUND( AVG(pressure),0) as pressure, ROUND(AVG(rain),1) as rain,ROUND(AVG(wind_speed),1) as wind_speed, ROUND(AVG(wind_dir),0) as wind_dir, ROUND(AVG(humidity),0)as humidity  FROM $met WHERE date_format( sync_date, '%Y%m%d%H%i%s' ) >= $startDate AND  date_format( sync_date, '%Y%m%d%H%i%s' ) < $endDate GROUP BY date_format( sync_date, '%Y%m%d%H' ),device_id";

        //pm
        $sql2 = "SELECT date_format( sync_date, '%Y-%m-%d %H:00:00' ) + INTERVAL 1 HOUR  as sync_date,device_id, ROUND(AVG(pm1),0) as pm1,ROUND( AVG(pm25),0) as pm25, ROUND(AVG(pm10),0) as pm10 FROM $pm WHERE date_format( sync_date, '%Y%m%d%H%i%s' ) >= $startDate AND  date_format( sync_date, '%Y%m%d%H%i%s' ) < $endDate GROUP BY date_format( sync_date, '%Y%m%d%H' ),device_id";

        //dust
        $sql3 = "SELECT date_format( sync_date, '%Y-%m-%d %H:00:00' ) + INTERVAL 1 HOUR  as sync_date,device_id, ROUND(AVG(ch20_ppb),0) as ch20_ppb,ROUND( AVG(ch20_ug_m3),0) as ch20_ug_m3 FROM $dus WHERE date_format( sync_date, '%Y%m%d%H%i%s' ) >= $startDate AND  date_format( sync_date, '%Y%m%d%H%i%s' ) < $endDate GROUP BY date_format( sync_date, '%Y%m%d%H' ),device_id";

        //sound
        $sql4 = "SELECT sync_date as sync_date, device_id, leq FROM $sou WHERE date_format( sync_date, '%Y%m%d%H%i%s' ) >= $startDate AND  date_format( sync_date, '%Y%m%d%H%i%s' ) < $endDate";

        //atmo
        $sql5 = "SELECT date_format( sync_date, '%Y-%m-%d %H:00:00' ) + INTERVAL 1 HOUR  as sync_date,device_id,  ROUND(AVG(LZO),0) as lzo FROM $atmo WHERE date_format( sync_date, '%Y%m%d%H%i%s' ) >= $startDate AND  date_format( sync_date, '%Y%m%d%H%i%s' ) < $endDate GROUP BY date_format( sync_date, '%Y%m%d%H' ),device_id";

        // get corrections
        $result00 = $wpdb->get_results($sql0);

//        $temperatureCor = 1;
//        $pressure float NULL,
//        $rain float NULL,
//        $wind_speed float NULL,
//        $wind_dir float NULL,
//        $humidity float NULL,
//        $pm1 float NULL,
//        $pm25 float NULL,
//        $pm10 float NULL,
//        $ch20_ppb double NULL,
//        $ch20_ug_m3 double NULL,
//        $leq float NULL,
//        $lzo double NULL,

        $correctionTable = array();
        foreach ($result00 as $row) {
            array_push($correctionTable, (array)$row);
        }

        $result11 = $wpdb->get_results($sql1);
        $result12 = $wpdb->get_results($sql2);
        $result13 = $wpdb->get_results($sql3);
        $result14 = $wpdb->get_results($sql4);
        $result15 = $wpdb->get_results($sql5);
        $table = array();

        foreach ($result11 as $row) {
            array_push($table, (array) $row);
        };
        foreach ($result12 as $row) {
            array_push($table, (array) $row);
        };
        foreach ($result13 as $row) {
            array_push($table, (array) $row);
        };
        foreach ($result14 as $row) {
            array_push($table, (array) $row);
        };
        foreach ($result15 as $row) {
            array_push($table, (array) $row);
        };


        $table = merge_table_cron(
            $table,
            function ($item1, $item2) {
                return $item1['sync_date'] == $item2['sync_date'] && $item1['device_id'] == $item2['device_id'];
            }
        ); //$obj ['device_id']

        // delete data
        $wpdb->query('START TRANSACTION');
        $deleteSql = "DELETE FROM `$table_name` WHERE date_format( sync_date, '%Y%m%d%H%i%s' ) >= $startDate AND  date_format( sync_date, '%Y%m%d%H%i%s' ) < $deleteEndDate";
        $wpdb->query($deleteSql);


        // INSERT CORECTION DATA
        var_dump($table);
        var_dump("-------------------------------------");

        foreach ($table as &$keyTable) {
            //$thisCorrection = searchForCorrectionValue($key, 408, $correctionTable);
            //var_dump($keyTable);
            $keyStore = $keyTable['device_id'];
            foreach($keyTable as $key => &$val)
            {
                if($key !== 'sync_date' && $key !== 'device_id')
                {
                    $thisCorrection = searchForCorrectionValue($key, $keyStore, $correctionTable);
//                    var_dump($key, $thisCorrection, $val, $thisCorrection * $val );
//                    var_dump("-------------------------------------");
                    $val = $val * $thisCorrection;
                }
            }
        }
        var_dump($table);
        var_dump("-------------------------------------");

        // INSERT NEW DATA
        foreach ($table as $row) {
            $wpdb->insert($table_name, $row);
        }
        $wpdb->query('COMMIT');
        return;
        
        //die('Kalkulacja zakończona!');
    } catch (Exception $e) {    // Database Error
        $wpdb->query("ROLLBACK");
        die("Error inserting weather: " . $wpdb->error);
    }
}
add_action('wp_ajax_import_weather', 'imporWeatherData');


function searchForCorrectionValue($id, $deviceId, $array) {
    foreach ($array as $key => $val) {

        //var_dump($val['sensor_type'],$val['device_id']);
        //var_dump("---------------------------------------------");

        if ($val['sensor_type'] === $id && $val['device_id'] === $deviceId) {
            return $val['correction'];
        }
    }
    return 1;
 }



/**
 * Cron methods (depricated)
 */
function cron()
{
    $device_ids = get_devices();

    $myFile = 'cron_log.txt';
    file_put_contents($myFile, "\xEF\xBB\xBF ---\n" . date('d-m-Y H:i:s') . " Runing update", FILE_APPEND);
    $time_pre = microtime(true);

    try {
        file_put_contents($myFile, "\xEF\xBB\xBF Connecting db", FILE_APPEND);

        $mysqli = new mysqli('83.12.141.66', 'mielec', 'alamakota', 'mielec_data');
        if ($mysqli->connect_error) {
            file_put_contents($myFile, "\xEF\xBB\xBF Db connect error: " . mysqli_error . " - " . $mysqli->connect_error, FILE_APPEND);
            //mysqli_error();
            die();
        }

        file_put_contents($myFile, "\xEF\xBB\xBF Connecting wordpress db", FILE_APPEND);
        //$wpDb = new mysqli('sql.impactit.nazwa.pl','impactit_1','I(DYQJy9i3u','impactit_1');
            $wpDb = new mysqli('sql.impactit.nazwa.pl','impactit','Qoqjqi95q','impactit');
        if ($wpDb->connect_error) {
            file_put_contents($myFile, "\xEF\xBB\xBF Db wordpress connect error: " . mysqli_error . " - " . $wpDb->connect_error, FILE_APPEND);
            // /mysqli_error()
            die();
        }
        //$prefix ='stwair_';
        $prefix = 'mielec_';
        getWeather($prefix, $myFile, $wpDb, $mysqli, $device_ids);
        getMeteo($prefix, $myFile, $wpDb, $mysqli, $device_ids);
        getPM($prefix, $myFile, $wpDb, $mysqli, $device_ids);
        getDust($prefix, $myFile, $wpDb, $mysqli, $device_ids);
        getSound($prefix, $myFile, $wpDb, $mysqli, $device_ids);
        getAtmo($prefix, $myFile, $wpDb, $mysqli, $device_ids);

        $mysqli->close();
        $wpDb->close();
    } catch (Exception $e) {    // Database Error            
        file_put_contents($myFile, "\xEF\xBB\xBF Error" . $e->getMessage(), FILE_APPEND);
    }

    $time_post = microtime(true);
    $exec_time = $time_post - $time_pre;
    file_put_contents($myFile, "\xEF\xBB\xBF Finish in " . $exec_time, FILE_APPEND);
}

function getWeather($prefix, $myFile, $wpDb, $mysqli, $device_ids)
{
    try {
        $table_name = $prefix . METEO_TABLE_ALL;

        $sql2 = "SELECT date_format( sync_date, '%Y%m%d%H' ) as sync_date  FROM $table_name ORDER BY sync_date DESC LIMIT 1";
        $result2 = $wpDb->query($sql2);
        $lastdate = $result2->fetch_assoc()['sync_date'] ?? date("YmdH", strtotime("-1 hour"));
        if (!$result2) {
            file_put_contents($myFile, "\xEF\xBB\xBF Error getting date: " . $mysqli->error, FILE_APPEND);
            die($mysqli->error);
        }
        file_put_contents($myFile, "\xEF\xBB\xBF \nGetting weather data from " . $lastdate . " date sync: " . $result2->fetch_assoc()['sync_date'], FILE_APPEND);
        $ids = implode(',', $device_ids);

        //meteo
        $sql1 = "SELECT date_format( measurement_time, '%Y-%m-%d %H:00:00' )  + INTERVAL 1 HOUR as sync_date,device_id, ROUND(AVG(temperature),1) as temperature,ROUND( AVG(pressure),0) as pressure, ROUND(AVG(rain),1) as rain,ROUND(AVG(wind_speed),1) as wind_speed, ROUND(AVG(wind_dir),0) as wind_dir, ROUND(AVG(humidity),0)as humidity  FROM meteo_data WHERE date_format( measurement_time, '%Y%m%d%H' ) >= $lastdate AND device_id IN (" . $ids . ") GROUP BY date_format( measurement_time, '%Y%m%d%H' ),device_id";

        //pm
        $sql2 = "SELECT date_format( measurement_time, '%Y-%m-%d %H:00:00' )  + INTERVAL 1 HOUR as sync_date,device_id, ROUND(AVG(pm1),6) as pm1,ROUND( AVG(pm25),6) as pm25, ROUND(AVG(pm10),6) as pm10 FROM fardata_pm_raw_data WHERE date_format( measurement_time, '%Y%m%d%H' ) >= $lastdate AND device_id IN (" . $ids . ") GROUP BY date_format( measurement_time, '%Y%m%d%H' ),device_id";

        //dust
        $sql3 = "SELECT date_format( measurement_time, '%Y-%m-%d %H:00:00' )  + INTERVAL 1 HOUR as sync_date,device_id, ROUND(AVG(ch20_ppb),6) as ch20_ppb,ROUND( AVG(ch20_ug_m3),6) as ch20_ug_m3 FROM CH2O_sensor WHERE date_format( measurement_time, '%Y%m%d%H' ) >= $lastdate AND device_id IN (" . $ids . ") GROUP BY date_format( measurement_time, '%Y%m%d%H' ),device_id";

        //sound
        $sql4 = "SELECT measurement_time as sync_date, device_id, leq FROM dzwiek WHERE date_format( measurement_time, '%Y%m%d%H' ) > $lastdate AND device_id IN (" . $ids . ")";

        //atmo
        $sql5 = "SELECT date_format( measurement_time, '%Y-%m-%d %H:00:00' )  + INTERVAL 1 HOUR as sync_date,device_id, LZO FROM atmo_data WHERE date_format( measurement_time, '%Y%m%d%H' ) >= $lastdate AND device_id IN (" . $ids . ") GROUP BY date_format( measurement_time, '%Y%m%d%H' ),device_id";

        $result11 = $mysqli->query($sql1);
        $result12 = $mysqli->query($sql2);
        $result13 = $mysqli->query($sql3);
        $result14 = $mysqli->query($sql4);
        $result15 = $mysqli->query($sql5);
        $table = array();

        while ($row = $result11->fetch_assoc()) {
            array_push($table, $row);
        };
        while ($row = $result12->fetch_assoc()) {
            array_push($table, $row);
        };
        while ($row = $result13->fetch_assoc()) {
            array_push($table, $row);
        };
        while ($row = $result14->fetch_assoc()) {
            array_push($table, $row);
        };
        while ($row = $result15->fetch_assoc()) {
            array_push($table, $row);
        };

        //$table = array_filter($table, function($item) use($device_ids){
        //    return in_array($item['device_id'] ,$device_ids);
        //});
        //echo 'device data: '.count($table);

        //file_put_contents($myFile, "\xEF\xBB\xBF Get rows: ".print_r($row1),FILE_APPEND );
        $table = merge_table_cron(
            $table,
            function ($item1, $item2) {
                return $item1['sync_date'] == $item2['sync_date'] && $item1['device_id'] == $item2['device_id'];
            }
        ); //$obj ['device_id']

        file_put_contents($myFile, "\xEF\xBB\xBF Adding to db rows: " . count($table), FILE_APPEND);

        $wpDb->begin_transaction();

        $query = "INSERT INTO $table_name (`sync_date`,`device_id`,`temperature`,`pressure`,`rain`,`wind_speed`,`wind_dir`,`humidity`,`pm1`,`pm25`,`pm10`,`ch20_ppb`,`ch20_ug_m3`,`leq`,`lzo`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $wpDb->prepare($query);
        $stmt->bind_param('siddddddddddddd', $date, $dev, $c1, $c2, $c3, $c4, $c5, $c6, $c7, $c8, $c9, $c10, $c11, $c12, $c13);

        foreach ($table as $row) {

            $date = isset($row['sync_date']) ? $row['sync_date'] : null;
            $dev = isset($row['device_id']) ? $row['device_id'] : null;
            $c1 = isset($row['temperature']) ? $row['temperature'] : null;
            $c2 = isset($row['pressure']) ? $row['pressure'] : null;
            $c3 = isset($row['rain']) ? $row['rain'] : null;
            $c4 = isset($row['wind_speed']) ? $row['wind_speed'] : null;
            $c5 = isset($row['wind_dir']) ? $row['wind_dir'] : null;
            $c6 = isset($row['humidity']) ? $row['humidity'] : null;
            $c7 = isset($row['pm1']) ? $row['pm1'] : null;
            $c8 = isset($row['pm25']) ? $row['pm25'] : null;
            $c9 = isset($row['pm10']) ? $row['pm10'] : null;
            $c10 = isset($row['ch20_ppb']) ? $row['ch20_ppb'] : null;
            $c11 = isset($row['ch20_ug_m3']) ? $row['ch20_ug_m3'] : null;
            $c12 = isset($row['leq']) ? $row['leq'] : null;
            $c13 = isset($row['lzo']) ? $row['lzo'] : null;
            $stmt->execute();
            if (isset($stmt->error)) {
                file_put_contents($myFile, "\xEF\xBB\xBF Error inserting: " . $stmt->error, FILE_APPEND);
            }
        }
        $stmt->close();
        if (!$wpDb->commit()) {
            file_put_contents($myFile, "\xEF\xBB\xBF Error inserting: " . $wpDb->error, FILE_APPEND);
        }
    } catch (Exception $e) {    // Database Error
        $wpDb->rollback();
        file_put_contents($myFile, "\xEF\xBB\xBF Error inserting weather: " . $wpdb->error, FILE_APPEND);
    }
}

function getWeather2()
{
    try {

        $wpDb = new mysqli('sql.impactit.nazwa.pl','impactit','Qoqjqi95q','impactit');
        if ($wpDb->connect_error) {
            //mysqli_error()
            die();
        }
        $prefix = 'mielec_';
        $table_name = $prefix . SHOW_TABLE_ALL;
        $table_namem = $prefix . METEO_TABLE_ALL;
        $table_namep = $prefix . PM_TABLE_ALL;
        $table_named = $prefix . DUST_TABLE_ALL;
        $table_names = $prefix . SOUND_TABLE_ALL;
        $table_namea = $prefix . ATMO_TABLE_ALL;
        $ids =  get_devices();

        //get all data;
        $sql = "SELECT id, date_format( sync_date, '%Y%m%d%H' ) as sync_date, device_id, temperature, pm1,ch20_ppb,leq, lzo FROM $table_name ORDER BY sync_date DESC";
        $result = $wpDb->query($sql);
        if (!$result) {
            echo  "\xEF\xBB\xBF Error getting weather date: " . $wpDb->error;
            die($wpDb->error);
        }
        $rows = array();
        //save to array
        while ($row = $result->fetch_assoc()) {
            array_push($rows, (array) $row);
        }

        /*
        //merge all data to single objects by date and device
        $table = merge_table_cron($all,function($item1,$item2){
        return $item1['sync_date'] == $item2['sync_date'] && $item1['device_id']==$item2['device_id'];
        });//$obj ['device_id']
        echo  "\xEF\xBB\xBF New records: ".print_r($table);
        //get to update
        $to_update = array_filter($table,function($item) use($rows){
        foreach($rows as $row)
        {
        if($item['sync_date']==$row['sync_date'] && $item['device_id']==$row['device_id']){
        $item['id'] = $row['id'];
        return true;
        }
        }
        return false;
        });
        */
        //meteo

        $lastdateMeteo =    get_last_date($rows, 'temperature', 'sync_date') ?? '2017010100';

        $lastdatePm =    get_last_date($rows, 'pm1', 'sync_date') ?? '2017010100';

        $lastdateDust =    get_last_date($rows, 'ch20_ppb', 'sync_date') ?? '2017010100';

        $lastdateSound =    get_last_date($rows, 'leq', 'sync_date') ?? '2017010100';

        $lastdateAtmo =    get_last_date($rows, 'lzo', 'sync_date') ?? '2017010100';


        $sql1 = "SELECT date_format( sync_date, '%Y-%m-%d %H:00:00' )  + INTERVAL 1 HOUR as sync_date,device_id, ROUND(AVG(temperature),1) as temperature,ROUND( AVG(pressure),0) as pressure, ROUND(AVG(rain),1) as rain,ROUND(AVG(wind_speed),1) as wind_speed, ROUND(AVG(wind_dir),0) as wind_dir, ROUND(AVG(humidity),0)as humidity  FROM $table_namem WHERE date_format( sync_date, '%Y%m%d%H' ) >= $lastdateMeteo AND device_id IN (" . $ids . ") GROUP BY date_format( sync_date, '%Y%m%d%H' ),device_id";

        //pm
        $sql2 = "SELECT date_format( sync_date, '%Y-%m-%d %H:00:00' )  + INTERVAL 1 HOUR as sync_date,device_id, ROUND(AVG(pm1),6) as pm1,ROUND( AVG(pm25),6) as pm25, ROUND(AVG(pm10),6) as pm10 FROM $table_namep WHERE date_format( sync_date, '%Y%m%d%H' ) >= $lastdatePm AND device_id IN (" . $ids . ") GROUP BY date_format( sync_date, '%Y%m%d%H' ),device_id";

        //dust
        $sql3 = "SELECT date_format( sync_date, '%Y-%m-%d %H:00:00' )  + INTERVAL 1 HOUR as sync_date,device_id, ROUND(AVG(ch20_ppb),6) as ch20_ppb,ROUND( AVG(ch20_ug_m3),6) as ch20_ug_m3 FROM $table_named WHERE date_format( sync_date, '%Y%m%d%H' ) >= $lastdateDust AND device_id IN (" . $ids . ") GROUP BY date_format( sync_date, '%Y%m%d%H' ),device_id";

        //sound
        $sql4 = "SELECT sync_date as sync_date, device_id, leq FROM $table_names WHERE date_format( sync_date, '%Y%m%d%H' ) > $lastdateSound AND device_id IN (" . $ids . ")";

        //atmo
        $sql5 = "SELECT date_format( sync_date, '%Y-%m-%d %H:00:00' )  + INTERVAL 1 HOUR as sync_date,device_id, ROUND(AVG(lzo),6) as lzo FROM $table_namea WHERE date_format( sync_date, '%Y%m%d%H' ) >= $lastdateAtmo AND device_id IN (" . $ids . ") GROUP BY date_format( sync_date, '%Y%m%d%H' ),device_id";

        $result11 = $wpDb->query($sql1);
        echo  "\xEF\xBB\xBF \nGetting meteo data from " . $lastdateMeteo;
        $result12 = $wpDb->query($sql2);
        echo  "\xEF\xBB\xBF \nGetting pm data from " . $lastdatePm;
        $result13 = $wpDb->query($sql3);
        echo  "\xEF\xBB\xBF \nGetting dust data from " . $lastdateDust;
        $result14 = $wpDb->query($sql4);
        echo  "\xEF\xBB\xBF \nGetting sound data from " . $lastdateSound;
        $result15 = $wpDb->query($sql5);
        echo  "\xEF\xBB\xBF \nGetting atmo data from " . $lastdateAtmo;
        $table = array();

        while ($row = $result11->fetch_assoc()) {
            array_push($table, (array) $row);
        };
        while ($row = $result12->fetch_assoc()) {
            array_push($table, (array) $row);
        };
        while ($row = $result13->fetch_assoc()) {
            array_push($table, (array) $row);
        };
        while ($row = $result14->fetch_assoc()) {
            array_push($table, (array) $row);
        };
        while ($row = $result15->fetch_assoc()) {
            array_push($table, (array) $row);
        };

        echo  "\xEF\xBB\xBF All records: " . count($table);

        $table = merge_table_cron(
            $table,
            function ($item1, $item2) {
                return $item1['sync_date'] == $item2['sync_date'] && $item1['device_id'] == $item2['device_id'];
            }
        );

        echo  "\xEF\xBB\xBF Adding to db rows: " . count($table);
        $table = array_map(
            function ($item) use ($rows) {
                foreach ($rows as $row) {
                    $t = strtotime($item['sync_date']);
                    $time = date('YmdH', $t);
                    if ($time == $row['sync_date'] && $item['device_id'] == $row['device_id']) {
                        $item['id'] = $row['id'];
                        break;
                    }
                }
                return $item;
            },
            $table
        );
        echo  "\xEF\xBB\xBF To update: " . count($table);
        //$table = array_filter($table, function($item) use($device_ids){
        //    return in_array($item['device_id'] ,$device_ids);
        //});
        //echo 'device data: '.count($table);

        //echo  "\xEF\xBB\xBF Get rows: ".print_r($row1);


        $wpDb->begin_transaction();
        $query = "INSERT INTO $table_name (`id`,`sync_date`,`device_id`,`temperature`,`pressure`,`rain`,`wind_speed`,`wind_dir`,`humidity`,`pm1`,`pm25`,`pm10`,`ch20_ppb`,`ch20_ug_m3`,`leq`,`lzo`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE temperature = IFNULL(VALUES(temperature),temperature),pressure = IFNULL(VALUES(pressure),pressure),rain = IFNULL(VALUES(rain),rain),wind_speed = IFNULL(VALUES(wind_speed),wind_speed),wind_dir = IFNULL(VALUES(wind_dir),wind_dir),humidity = IFNULL(VALUES(humidity),humidity),pm1 =IFNULL(VALUES(pm1),pm1),pm25 = IFNULL(VALUES(pm25),pm25),pm10 = IFNULL(VALUES(pm10),pm10),ch20_ppb =IFNULL(VALUES(ch20_ppb),ch20_ppb),ch20_ug_m3 = IFNULL(VALUES(ch20_ug_m3),ch20_ug_m3),leq =IFNULL(VALUES(leq),leq),lzo = IFNULL(VALUES(lzo),lzo)";

        //$query = "INSERT INTO $table_name (`id`,`sync_date`,`device_id`,`temperature`,`pressure`,`rain`,`wind_speed`,`wind_dir`,`humidity`,`pm1`,`pm25`,`pm10`,`ch20_ppb`,`ch20_ug_m3`,`leq`,`lzo`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE temperature = VALUES(temperature),pressure = VALUES(pressure),rain = VALUES(rain),wind_speed = VALUES(wind_speed),wind_dir = VALUES(wind_dir),humidity = VALUES(humidity),pm1 = VALUES(pm1),pm25 = VALUES(pm25),pm10 = VALUES(pm10),ch20_ppb = VALUES(ch20_ppb),ch20_ug_m3 = VALUES(ch20_ug_m3),leq = VALUES(leq),lzo = VALUES(lzo)";
        $stmt = $wpDb->prepare($query);
        $stmt->bind_param('isiddddddddddddd', $id, $date, $dev, $c1, $c2, $c3, $c4, $c5, $c6, $c7, $c8, $c9, $c10, $c11, $c12, $c13);

        //$query_update = "UPDATE $table_name SET `temperature`=?,`pressure`=?,`rain`=?,`wind_speed`=?,`wind_dir`=?,`humidity`=?,`pm1`=?,`pm25`=?,`pm10`=?,`ch20_ppb`=?,`ch20_ug_m3`=?,`leq`=?,`lzo`=?) WHERE sync_date = ? and device_id = ?";
        //$stmt_update = $wpDb->prepare($query_update);
        //$stmt_update ->bind_param('dddddddddddddsi', $c1,$c2,$c3,$c4,$c5,$c6,$c7,$c8,$c9,$c10,$c11,$c12,$c13,$date,$dev);

        foreach ($table as $row) {
            $id = isset($row['id']) ? $row['id'] : null;
            $date = isset($row['sync_date']) ? $row['sync_date'] : null;
            $dev = isset($row['device_id']) ? $row['device_id'] : null;
            $c1 = isset($row['temperature']) ? $row['temperature'] : null;
            $c2 = isset($row['pressure']) ? $row['pressure'] : null;
            $c3 = isset($row['rain']) ? $row['rain'] : null;
            $c4 = isset($row['wind_speed']) ? $row['wind_speed'] : null;
            $c5 = isset($row['wind_dir']) ? $row['wind_dir'] : null;
            $c6 = isset($row['humidity']) ? $row['humidity'] : null;
            $c7 = isset($row['pm1']) ? $row['pm1'] : null;
            $c8 = isset($row['pm25']) ? $row['pm25'] : null;
            $c9 = isset($row['pm10']) ? $row['pm10'] : null;
            $c10 = isset($row['ch20_ppb']) ? $row['ch20_ppb'] : null;
            $c11 = isset($row['ch20_ug_m3']) ? $row['ch20_ug_m3'] : null;
            $c12 = isset($row['leq']) ? $row['leq'] : null;
            $c13 = isset($row['lzo']) ? $row['lzo'] : null;
            //if update
            $stmt->execute();
            //else
            //$stmt_update->execute();

            if (!empty($stmt->error)) {
                echo  "\xEF\xBB\xBF Error inserting: " . $stmt->error;
            }
        }
        $stmt->close();
        if (!$wpDb->commit()) {
            echo  "\xEF\xBB\xBF Error inserting: " . $wpDb->error;
        }
    } catch (Exception $e) {    // Database Error
        $wpDb->rollback();
        echo  "\xEF\xBB\xBF Error inserting weather: " . $wpdb->error;
    }
}
function get_last_date($table, $param, $ret)
{
    $tt = array_filter(
        $table,
        function ($item) use ($param) {
            return $item[$param] != null;
        }
    );
    return !isset($tt) ? false : current($tt)[$ret];
}

function getMeteo($prefix, $myFile, $wpDb, $mysqli, $device_ids)
{
    try {
        $table_name = $prefix . METEO_TABLE_ALL;

        $sql2 = "SELECT date_format( sync_date, '%Y%m%d%H' ) as sync_date  FROM $table_name ORDER BY sync_date DESC LIMIT 1";
        $result2 = $wpDb->query($sql2);
        $lastdate = $result2->fetch_assoc()['sync_date'] ?? date("YmdH", strtotime("-1 hour"));
        if (!$result2) {
            file_put_contents($myFile, "\xEF\xBB\xBF Error getting date: " . $mysqli->error, FILE_APPEND);
            die($mysqli->error);
        }
        file_put_contents($myFile, "\xEF\xBB\xBF \nGetting meteo data from " . $lastdate, FILE_APPEND);
        $ids = implode(',', $device_ids);
        $sql = "SELECT measurement_time as sync_date,device_id, temperature, pressure, rain,wind_speed,wind_dir,humidity FROM meteo_data WHERE date_format( measurement_time, '%Y%m%d%H' ) > $lastdate AND device_id IN (" . $ids . ")";

        $result = $mysqli->query($sql);
        if (!$result) {
            file_put_contents($myFile, "\xEF\xBB\xBF Error selecting data: " . $mysqli->error, FILE_APPEND);
            die($mysqli->error);
        }
        if ($result->num_rows > 0) {

            file_put_contents($myFile, "\xEF\xBB\xBF Adding to db rows: " . $result->num_rows, FILE_APPEND);
            $wpDb->begin_transaction();
            $query = "INSERT INTO $table_name (`sync_date`,`device_id`,`temperature`,`pressure`,`rain`,`wind_speed`,`wind_dir`,`humidity`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $wpDb->prepare($query);
            $stmt->bind_param('sidddddd', $date, $dev, $c1, $c2, $c3, $c4, $c5, $c6);

            while ($row = $result->fetch_assoc()) {
                if (!in_array($row['device_id'], $device_ids)) {
                    continue;
                }
                $date = $row['sync_date'];
                $dev = $row['device_id'];
                $c1 = $row['temperature'];
                $c2 = $row['pressure'];
                $c3 = $row['rain'];
                $c4 = $row['wind_speed'];
                $c5 = $row['wind_dir'];
                $c6 = $row['humidity'];

                $stmt->execute();
                if (isset($stmt->error)) {
                    file_put_contents($myFile, "\xEF\xBB\xBF Error inserting: " . $stmt->error, FILE_APPEND);
                }
            }
            $stmt->close();
            if (!$wpDb->commit()) {
                file_put_contents($myFile, "\xEF\xBB\xBF Error inserting: " . $wpDb->error, FILE_APPEND);
            }
        } else {
            file_put_contents($myFile, "\xEF\xBB\xBF 0 results for meteo data", FILE_APPEND);
        }
    } catch (Exception $e) {    // Database Error
        $wpDb->rollback();
        file_put_contents($myFile, "\xEF\xBB\xBF Error inserting pm: " . $wpdb->error, FILE_APPEND);
    }
}
function getPM($prefix, $myFile, $wpDb, $mysqli, $device_ids)
{
    try {
        $table_name = $prefix . PM_TABLE_ALL;
        $sql2 = "SELECT date_format( sync_date, '%Y%m%d%H' ) as sync_date  FROM $table_name ORDER BY sync_date DESC LIMIT 1";
        $result2 = $wpDb->query($sql2);
        $lastdate = $result2->fetch_assoc()['sync_date'] ?? date("YmdH", strtotime("-1 hour"));
        if (!$result2) {
            file_put_contents($myFile, "\xEF\xBB\xBF Error getting date: " . $mysqli->error, FILE_APPEND);
            die($mysqli->error);
        }
        file_put_contents($myFile, "\xEF\xBB\xBF \nGetting pm data from " . $lastdate, FILE_APPEND);
        $ids = implode(',', $device_ids);
        $sql3 = "SELECT measurement_time as sync_date,device_id, pm1, pm25, pm10 FROM fardata_pm_raw_data WHERE date_format( measurement_time, '%Y%m%d%H' ) > $lastdate AND device_id IN (" . $ids . ") ";
        $result3 = $mysqli->query($sql3);
        if (!$result3) {
            file_put_contents($myFile, "\xEF\xBB\xBF Error selecting data: " . $mysqli->error, FILE_APPEND);
            die($mysqli->error);
        }
        if ($result3->num_rows > 0) {
            $wpDb->begin_transaction();
            $query = "INSERT INTO $table_name (`sync_date`,`device_id`,`pm1`,`pm25`,`pm10`) VALUES (?, ?, ?, ?, ?)";
            $stmt = $wpDb->prepare($query);
            $stmt->bind_param('siddd', $date, $dev, $c1, $c2, $c3);

            while ($row = $result3->fetch_assoc()) {
                if (!in_array($row['device_id'], $device_ids)) {
                    continue;
                }

                $date = $row['sync_date'];
                $dev = $row['device_id'];
                $c1 = $row['pm1'];
                $c2 = $row['pm25'];
                $c3 = $row['pm10'];

                $stmt->execute();
                if (isset($stmt->error)) {
                    file_put_contents($myFile, "\xEF\xBB\xBF Error inserting: " . $stmt->error, FILE_APPEND);
                }
            }
            $stmt->close();
            if (!$wpDb->commit()) {
                file_put_contents($myFile, "\xEF\xBB\xBF Error inserting: " . $wpDb->error, FILE_APPEND);
            }
        }
    } catch (Exception $e) {    // Database Error
        $wpDb->rollback();
        file_put_contents($myFile, "\xEF\xBB\xBF Error inserting pm: " . $wpdb->error, FILE_APPEND);
    }
}
function getDust($prefix, $myFile, $wpDb, $mysqli, $device_ids)
{
    try {
        $table_name = $prefix . DUST_TABLE_ALL;
        $sql2 = "SELECT date_format( sync_date, '%Y%m%d%H' ) as sync_date FROM $table_name ORDER BY sync_date DESC LIMIT 1";
        $result2 = $wpDb->query($sql2);
        $lastdate = $result2->fetch_assoc()['sync_date'] ?? date("YmdH", strtotime("-1 hour"));
        if (!$result2) {
            file_put_contents($myFile, "\xEF\xBB\xBF Error getting date: " . $mysqli->error, FILE_APPEND);
            die($mysqli->error);
        }
        file_put_contents($myFile, "\xEF\xBB\xBF \nGetting dust data from " . $lastdate, FILE_APPEND);
        $ids = implode(',', $device_ids);
        $sql3 = "SELECT measurement_time as sync_date,device_id, ch20_ppb, ch20_ug_m3 FROM CH2O_sensor WHERE date_format( measurement_time, '%Y%m%d%H' ) > $lastdate AND device_id IN (" . $ids . ") ";
        $result3 = $mysqli->query($sql3);
        if (!$result3) {
            file_put_contents($myFile, "\xEF\xBB\xBF Error selecting data: " . $mysqli->error, FILE_APPEND);
            die($mysqli->error);
        }
        if ($result3->num_rows > 0) {
            $wpDb->begin_transaction();
            $query = "INSERT INTO $table_name (`sync_date`,`device_id`,`ch20_ppb`,`ch20_ug_m3`) VALUES (?, ?, ?, ?)";
            $stmt = $wpDb->prepare($query);
            $stmt->bind_param('sidd', $date, $dev, $c1, $c2);

            while ($row = $result3->fetch_assoc()) {
                if (!in_array($row['device_id'], $device_ids)) {
                    continue;
                }

                $date = $row['sync_date'];
                $dev = $row['device_id'];
                $c1 = $row['ch20_ppb'];
                $c2 = $row['ch20_ug_m3'];

                $stmt->execute();
                if (isset($stmt->error)) {
                    file_put_contents($myFile, "\xEF\xBB\xBF Error inserting: " . $stmt->error, FILE_APPEND);
                }
            }
            $stmt->close();
            if (!$wpDb->commit()) {
                file_put_contents($myFile, "\xEF\xBB\xBF Error inserting: " . $wpDb->error, FILE_APPEND);
            }
        }
    } catch (Exception $e) {    // Database Error
        $wpDb->rollback();
        file_put_contents($myFile, "\xEF\xBB\xBF Error inserting dust: " . $wpdb->error, FILE_APPEND);
    }
}
function getSound($prefix, $myFile, $wpDb, $mysqli, $device_ids)
{
    try {
        $table_name = $prefix . SOUND_TABLE_ALL;
        $sql2 = "SELECT date_format( sync_date, '%Y%m%d%H' ) as sync_date  FROM $table_name ORDER BY sync_date DESC LIMIT 1";
        $result2 = $wpDb->query($sql2);
        $lastdate = $result2->fetch_assoc()['sync_date'] ?? date("YmdH", strtotime("-1 hour"));
        if (!$result2) {
            file_put_contents($myFile, "\xEF\xBB\xBF Error getting date: " . $mysqli->error, FILE_APPEND);
            die($mysqli->error);
        }
        file_put_contents($myFile, "\xEF\xBB\xBF \nGetting sound data from " . $lastdate, FILE_APPEND);

        $ids = implode(',', $device_ids);
        $sql = "SELECT measurement_time as sync_date, device_id, leq FROM dzwiek WHERE date_format( measurement_time, '%Y%m%d%H' ) > $lastdate";
        $result = $mysqli->query($sql);
        if (!$result) {
            file_put_contents($myFile, "\xEF\xBB\xBF Error selecting data: " . $mysqli->error, FILE_APPEND);
            die($mysqli->error);
        }
        if ($result->num_rows > 0) {

            file_put_contents($myFile, "\xEF\xBB\xBF Adding to db rows: " . $result->num_rows, FILE_APPEND);
            $wpDb->begin_transaction();
            $query = "INSERT INTO $table_name (`sync_date`,`device_id`,`leq`) VALUES (?, ?, ?)";
            $stmt = $wpDb->prepare($query);
            $stmt->bind_param('sid', $date, $dev, $c1);

            while ($row = $result->fetch_assoc()) {
                if (!in_array($row['device_id'], $device_ids)) {
                    continue;
                }

                $date = $row['sync_date'];
                $dev = $row['device_id'];
                $leq =  $row['leq'];

                $stmt->execute();
                if (isset($stmt->error)) {
                    file_put_contents($myFile, "\xEF\xBB\xBF Error inserting: " . $stmt->error, FILE_APPEND);
                }
            }
            $stmt->close();
            if (!$wpDb->commit()) {
                file_put_contents($myFile, "\xEF\xBB\xBF Error inserting: " . $wpDb->error, FILE_APPEND);
            }
        } else {
            file_put_contents($myFile, "\xEF\xBB\xBF 0 results for sound", FILE_APPEND);
        }
    } catch (Exception $e) {    // Database Error
        $wpDb->rollback();
        file_put_contents($myFile, "\xEF\xBB\xBF Error inserting dust: " . $wpdb->error, FILE_APPEND);
    }
}
function getAtmo($prefix, $myFile, $wpDb, $mysqli, $device_ids)
{
    try {
        $table_name = $prefix . ATMO_TABLE_ALL;

        $sql2 = "SELECT date_format( sync_date, '%Y%m%d%H' ) as sync_date  FROM $table_name ORDER BY sync_date DESC LIMIT 1";
        $result2 = $wpDb->query($sql2);
        $lastdate = $result2->fetch_assoc()['sync_date'] ?? date("YmdH", strtotime("-1 hour"));
        if (!$result2) {
            file_put_contents($myFile, "\xEF\xBB\xBF Error getting date: " . $mysqli->error, FILE_APPEND);
            die($mysqli->error);
        }
        file_put_contents($myFile, "\xEF\xBB\xBF \nGetting atmo data from " . $lastdate, FILE_APPEND);
        $ids = implode(',', $device_ids);
        $sql3 = "SELECT measurement_time as sync_date,device_id, LZO FROM atmo_data WHERE date_format( measurement_time, '%Y%m%d%H' ) > $lastdate AND device_id IN (" . $ids . ") ";
        $result3 = $mysqli->query($sql3);
        if (!$result3) {
            file_put_contents($myFile, "\xEF\xBB\xBF Error selecting data: " . $mysqli->error, FILE_APPEND);
            die($mysqli->error);
        }
        if ($result3->num_rows > 0) {
            $wpDb->begin_transaction();
            $query = "INSERT INTO $table_name (`sync_date`,`device_id`,`lzo`) VALUES (?, ?, ?)";
            $stmt = $wpDb->prepare($query);
            $stmt->bind_param('sid', $date, $dev, $c1);

            while ($row = $result3->fetch_assoc()) {
                if (!in_array($row['device_id'], $device_ids)) {
                    continue;
                }

                $date = $row['sync_date'];
                $dev = $row['device_id'];
                $c1 = $row['LZO'];

                $stmt->execute();
                if (isset($stmt->error)) {
                    file_put_contents($myFile, "\xEF\xBB\xBF Error inserting: " . $stmt->error, FILE_APPEND);
                }
            }
            $stmt->close();
            if (!$wpDb->commit()) {
                file_put_contents($myFile, "\xEF\xBB\xBF Error inserting: " . $wpDb->error, FILE_APPEND);
            }
        }
    } catch (Exception $e) {    // Database Error
        $wpDb->rollback();
        file_put_contents($myFile, "\xEF\xBB\xBF Error inserting pm: " . $wpdb->error, FILE_APPEND);
    }
}

//***********************************************************
// get data from mielec_data
//***********************************************************

function read_db()
{
    try {
        include_once  ABSPATH . 'wp-content/plugins/wp-kendo/lib/DataSourceResult.php';
        include_once  ABSPATH . 'wp-content/plugins/wp-kendo/lib/Kendo/Autoload.php';

        $request = json_decode(file_get_contents('php://input'));

        $result = new DataSourceResult('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);

        //return;
        $type = $_GET['type'];
        $all = isset($_GET['all']) ? $_GET['all'] : false;
        $table = $_GET['db'];
        switch ($table) {
            case 'atmo':
                $model = new AtmoData();
                break;
            case 'md':
                $model = new MeteoData();
                break;
            case 'pm':
                $model = new PMData();
                break;
            case 'dust':
                $model = new DustData();
                break;
            case 'sound':
                $model = new SoundData();
                break;
            case 'wd':
                $model = new WeatherData();
                break;
            case 'scd':
                $model = new SensorCorrection();
                break;
            case 'clampData':
                $model = new SensorClamp();
                break;
        }

        switch ($type) {
                /*   case 'create':
          $result = $result->create($model->table_name(),$model->columns, $request->models, 'id');
          break;
        */
            case 'read':
                $result = $result->read($model->table_name($all), $model->columns, $request);
                break;
                /* case 'update':
          $result = $result->update($model->table_name(),$model->columns, $request->models, 'id');
          break;
         case 'destroy':
          $result = $result->destroy($model->table_name(), $request->models, 'id');
          break;
          */
        }

        echo json_encode($result, JSON_NUMERIC_CHECK);
    } catch (Exception $e) {    // Database Error
        echo $e->getMessage();
    }
    exit;
}
add_action('wp_ajax_read_db', 'read_db');


//***********************************************************
// Set api
//***********************************************************
add_action(
    'rest_api_init',
    function () {
        register_rest_route(
            'wp/v2',
            '/chart_data/(?<data>\S+)',
            array(
                'methods' => 'GET',
                'callback' => 'get_chart_weather',
                'args'            => array(
                    'top' => array(
                        'default' => 10,
                        'required' => false,
                    ),
                    'datefilter' => array(
                        'default' => '',
                        'required' => false,
                    ),
                ),
            )
        );
        register_rest_route(
            'wp/v2',
            '/table_data/(?<data>\S+)',
            array(
                'methods' => 'GET',
                'callback' => 'get_table_weather',

            )
        );
    }
);

function get_chart_data($data)
{

    $dateStart = $data['datefilterFrom'] ?? '';
    $dateEnd = $data['datefilterTo'] ?? '';
    $take = $data['top'] ?? 10;
    $tables =  explode("+", explode('&', $data['data'])[0]);
    $result = array();
    $i = 1;

    $where = '';
    if (!empty($dateStart)) {
        $take = null;
        $where = ' sync_date >= \'' . $dateStart . '\'';
    }
    if (!empty($dateEnd)) {
        $take = null;
        if (!empty($dateStart)) {
            $where .= ' AND ';
        }
        $where .= ' sync_date <= \'' . $dateEnd . '\'';
    }
    $sort = array();
    foreach ($tables as $item) {
        $table =  explode("-", $item);
        array_push($sort, array('id' => $i++, 'table' => $table[0], 'prop' => $table[1]));
    }
    $res = group_by('table', $sort);
    $result = array();
    foreach ($res as $item) {
        $select =  implode(", ", array_column($item, 'prop'));
        $repo = new \Table_Repository($item[0]['table'] . '_data');

        $value = $repo->get_data($select . ', sync_date as date', $take, 0, $where, 'sync_date desc')['data']; //($table[1],'','sync_date desc');

        foreach ($value as $item) {
            array_push($result, $item);
        }
    }
    return new WP_REST_Response($result, 200);
}
function get_chart_weather($data)
{
    $cat = $data['cat_param'];
    $dateStart = $data['datefilterFrom'] ?? '';
    $dateEnd = $data['datefilterTo'] ?? '';
    $take = $data['top'] ?? 10;
    $columns =  explode("+", explode('&', $data['data'])[0]);
    array_push($columns, $cat);
    $result = array();
    $i = 1;

    $where = '';
    if (!empty($dateStart)) {
        $take = null;
        $where = ' sync_date >= \'' . $dateStart . '\'';
    }
    if (!empty($dateEnd)) {
        $take = null;
        if (!empty($dateStart)) {
            $where .= ' AND ';
        }
        $where .= ' sync_date <= \'' . $dateEnd . '\'';
    }

    if (count($columns) > 0) {
        if (!empty($dateEnd) || !empty($dateStart)) {
            $where .= ' and ';
        }
        $where .=  implode(
            " || ",
            array_filter(
                $columns,
                function ($value) {
                    return $value != 'date';
                }
            )
        ) . ' IS NOT NULL';
        //$where = str_replace("date","",$where);
    }
    $result = array();

    $select =  implode(", ", $columns);
    $select = str_replace("date", "sync_date as date", $select);
    $repo = new WeatherData();

    $value = array_slice($repo->get_data($select . ", sync_date as date", $where, 'sync_date desc'), 0, $take);
    $value = array_map(
        function ($item) {
            foreach ($item as $key => $value) {
                if (is_numeric($value)) {
                    $item->$key = floatval($value);
                }
            }
            return $item;
        },
        $value
    );
    return new WP_REST_Response($value, 200);
}
function get_table_data($data)
{

    $take = $data['take'];
    $skip =  $data['skip'];
    $sort =   $data['sort'];
    $filter =  $data['filter'];
    $tables =  explode("+", explode('&', $data['data'])[0]);

    //$repo = new \Table_Repository($table.'_data');

    //$result = $repo->get_data('*');//,null,0,null,'sync_date desc');

    $groups = array();
    $i = 0;
    foreach ($tables as $item) {
        array_push($groups, array('id' => $i++, 'table' => get_table($item), 'prop' => $item));
    }
    $res = group_by('table', $groups);
    $result = array();
    $ww = array();
    $errors = array();
    foreach ($res as $item) {
        if (empty($item[0]['table'])) {
            continue;
        }

        $select =  implode(", ", array_column($item, 'prop'));
        $repo = new \Table_Repository($item[0]['table'] . '_data');
        $select = str_replace("date", "sync_date as date", $select);
        $value = $repo->get_data($select . ", sync_date as date");
        foreach ($value['data'] as $item1) {

            /*    foreach($item1 as $key => $prop)
            if(is_numeric($prop))
            {
            $item1->$key = floatval($prop);
            }*/
            array_push($ww, $item1);
        }
        if (!empty($value['errors'])) {
            array_push($errors, $value['errors']);
        }
    }

    $last_array = array();
    foreach ($ww as $item1) {
        $add =  true;
        foreach ($last_array as $key => $item2) {
            if ($item1->date == $item2->date) {
                $add = false;
                $last_array[$key] = (object) array_merge((array) $item2, (array) $item1);
            }
        }
        if ($add) {
            array_push($last_array, $item1);
        }
    }
    $last_array =    sort_data($last_array, $sort);
    $last_array =    filters_data($last_array, $filter);
    return new WP_REST_Response(array('data' => array_slice($last_array, $skip, $take), 'total' => count($last_array)), 200);
}

function get_table_weather($data)
{

    $take = $data['take'];
    $skip =  $data['skip'];
    $sort =   $data['sort'];
    $filter =  $data['filter'];
    $columns =  explode("+", explode('&', $data['data'])[0]);

    //$repo = new \Table_Repository($table.'_data');

    //$result = $repo->get_data('*');//,null,0,null,'sync_date desc');

    $result = array();
    $where = '';
    if (count($columns) > 0) {
        unset($columns->date);
        $where =  implode(
            " || ",
            array_filter(
                $columns,
                function ($value) {
                    return $value != 'date';
                }
            )
        ) . ' IS NOT NULL';
        //$where = str_replace("date","",$where);
    }
    $select =  implode(", ", $columns);
    //$notNull = implode (" NOT NULL ",$columns);
    $select = str_replace("date", "sync_date as date", $select);
    $repo = new WeatherData();
    $last_array = $repo->get_data($select . ", sync_date as date", $where);

    $last_array =    sort_data($last_array, $sort);
    $last_array =    filters_data($last_array, $filter);
    return new WP_REST_Response(array('data' => array_slice($last_array, $skip, $take), 'total' => count($last_array)), 200);
}

function merge_table_cron($table, $condition)
{

    $last_array = array();
    foreach ($table as $item1) {
        $add =  true;
        foreach ($last_array as $key => $item2) {
            if ($condition($item1, $item2)) {
                $add = false;
                $last_array[$key] = (array) array_merge((array) $item2, (array) $item1);
            }
        }
        if ($add) {
            array_push($last_array, $item1);
        }
    }
    return $last_array;
}
function merge_table($table, $condition)
{
    $last_array = array();
    foreach ($table as $item1) {
        $item1->device_id =  get_devices();
        $add =  true;
        foreach ($last_array as $key => $item2) {
            $item2->device_id =  get_devices();
            if ($condition($item1, $item2)) {
                $add = false;
                $last_array[$key] = (object) array_merge((array) $item2, (array) $item1);
            }
        }
        if ($add) {
            array_push($last_array, $item1);
        }
    }
    return $last_array;
}


function group_by($filed, $old_arr)
{

    $result = array();
    foreach ($old_arr as $data) {
        $id = $data[$filed];
        if (isset($result[$id])) {
            $result[$id][] = $data;
        } else {
            $result[$id] = array($data);
        }
    }
    return $result;
}
function get_table($option)
{
    switch ($option) {
        case 'temperature':
        case 'pressure':
        case 'rain':
        case 'wind_speed':
        case 'wind_dir':
        case 'humidity':
            return 'meteo';

        case 'leq':
            return 'sound';
        case 'lzo':
            return 'atmo';
        case 'pm1':
        case 'pm25':
        case 'pm10':
            return 'pm';

        case 'ch20_ppb':
        case 'ch20_ug_m3':
            return 'dust';
        default:
            return '';
    }
}

function sort_data($data, $sort)
{
    if ($sort != null) {
        for ($i = 0; $i < count($sort); $i++) {
            $usort = $sort[$i];
            usort(
                $data,
                function ($a, $b) use ($usort) {

                    $filed = $usort['field'];
                    $dir = $usort['dir'];

                    if (!isset($a->$filed) || !isset($b->$filed)) {
                        return -1;
                    }

                    if ($a->$filed == $b->$filed) {
                        return 0;
                    }

                    if ($dir == 'desc') {
                        return $a->$filed < $b->$filed ? -1 : 1;
                    }

                    return $a->$filed > $b->$filed ? -1 : 1;
                }
            );
        }
    }
    return $data;
}

function filters_data($data, $filters, $str = '')
{
    $str .= ' filter ';
    $results = $data;
    if (isset($filters['filters'])) {
        $logic = $filters['logic'];
        $i = 0;
        $str .= ' logic: ' . $logic;
        foreach ($filters['filters'] as $filter) {
            $str .= ' set field: ' . isset($filter['field']);
            $str .= ' set: ' . isset($filter['filters']);
            if (isset($filter['filters'])) {
                $results = filters_data($results, $filter, $str);
                $str .= ' found: ' . count($results);
            } else {
                if ($logic == 'or') {
                    //die(print_r($results));
                    $results =  array_filter(
                        $data,
                        function ($item) use ($filter) {
                            return filter_one_data($item, $filter);
                        }
                    ) + $results;
                    $str .= ' or ';
                } else {
                    $results = array_filter(
                        $results,
                        function ($item) use ($filter) {
                            return filter_one_data($item, $filter);
                        }
                    );
                    $str .= ' and ';
                }
                $str .= ' get: ' . count($results);
            }
        }
    }

    return $results;
}

function filter_one_data($item, $filter)
{
    if (isset($filter['field'])) {
        $field = $filter['field'];

        if (!isset($item->$field)) {
            return false;
        }
        $find = $item->$field;

        if (is_numeric($find)) {
            $find = floatval($find);
        }
        //parse date
        else if ((bool) strtotime($find)) {

            $find =    strtotime($find);
            $filter['value'] = strtotime($filter['value']);
        }


        switch ($filter['operator']) {
            case 'startswith':
                $length = strlen($filter['value']);
                return (substr($find, 0, $length) == $filter['value']);
                break;
            case 'contains':
                return strpos($find, $filter['value']) !== false;
                break;
            case 'doesnotcontain':
                return strpos($find, $filter['value']) == false;
                break;
            case 'endswith':
                $length = strlen($filter['value']);
                return $length === 0 || (substr($find, -$length) == $filter['value']);
                break;
            case 'eq':
                return $find == $filter['value'];
                break;
            case 'gt':
                return $find > $filter['value'];
                break;
            case 'lt':
                return $find < $filter['value'];
                break;
            case 'gte':
                return $find >= $filter['value'];
                break;
            case 'lte':
                return $find <= $filter['value'];
                break;
            case 'neq':
                return $find <> $filter['value'];
                break;
            case 'isnotnull':
                return $find != null;
                break;
            case 'isnull':
                return $find == null;
                break;
            case 'isempty':
                return empty($find);
                break;
            case 'isnotempty':
                return !empty($find);
                break;
        }
    }
    return false;
}
//***********************************************************
// Set elementor widget
//***********************************************************

add_action(
    'elementor/widgets/widgets_registered',
    function ($widgets_manager) {
        include plugin_dir_path(__FILE__) . 'my-widget.php';
        include plugin_dir_path(__FILE__) . 'my-weather.php';
        include plugin_dir_path(__FILE__) . 'table-widget.php';
        include plugin_dir_path(__FILE__) . 'chart-widget.php';
        include plugin_dir_path(__FILE__) . 'icon-heading.php';
    }
);
