<?php 

  //configuracion de la conexion a la base de datos

   include('configuracion.php');
   
   session_start();
   
   $peticion = $_POST['peticion'];
   $parametros = $_POST['parametros'];
   
   switch($peticion)
   {
		//Caso para recuperar los sitios de interes de la base de datos
		case 'recupera-edificios-geojson':
		{
			$sql="SELECT row_to_json(fc)
			 FROM ( SELECT 'FeatureCollection' As type, array_to_json(array_agg(f)) As features
			 FROM (SELECT 'Feature' As type
				, ST_AsGeoJSON(lg.the_geom)::json As geometry
				, row_to_json((SELECT l FROM (SELECT osm_id , name, st_area(st_transform(the_geom,3115))/10000 as area_edif ) As l
				  )) As properties
			   FROM edificios_univalle As lg  where ST_IsValid(the_geom) ) As f )  As fc;";
   
			$query = pg_query($dbcon,$sql);
			$row = pg_fetch_row($query);
			echo $row[0];
			break;
		}
		
   }
    

?>
