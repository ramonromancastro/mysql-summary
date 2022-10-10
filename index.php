<!DOCTYPE html>
<html lang="es-ES">
	<head>
		<title>MySQL Summary</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="country" content="Spain">
		<meta name="Language" content="es">
		<link rel="stylesheet" media="all" href="css/w3.css">
		<link rel="stylesheet" media="all" href="css/w3-theme-grey.css">
		<link rel="stylesheet" media="all" href="css/theme.css">
		<link rel="shortcut icon" href="favicon.ico">
		<script>
		function graph_donutValue(id_svg,value,config){
			config = config || {};
			color = config.color || '#5DA5DA';

			var svg = document.getElementById(id_svg);

			if (!config.graphSize) config.graphSize = 150;
			if (!config.maxValue) config.maxValue = 100;
			if (!config.percent) config.percent = true;
			
			value = parseInt(value);
			
			strokeDasharray = 0;
			strokeDasharrayMax = Math.PI * 2.0 * config.graphSize * 0.43;
			
			cx=config.graphSize*0.5;
			cy=config.graphSize*0.5;
			rh=config.graphSize*0.5;
			r=config.graphSize*0.43;
			sw=config.graphSize*0.14;
			
			legendTop=0;
			legendLine=15;
			
			boxSize=12;
			boxLeft=20;
			
			textLeft=35;
			textSize=config.graphSize/5;
			textWidth=textSize*7/12;
			textOffset=2;
			textfamily="monospace";
			
			maxWidth=0;
			maxHeight=(config.legendShow)?legendTop:0;
			
			var circle = document.createElementNS("http://www.w3.org/2000/svg", "circle");
			circle.setAttribute("cx",cx);
			circle.setAttribute("cy",cy);
			circle.setAttribute("r",rh);
			circle.setAttribute("fill","transparent");
			svg.appendChild(circle);
			var circle = document.createElementNS("http://www.w3.org/2000/svg", "circle");
			circle.setAttribute("cx",cx);
			circle.setAttribute("cy",cy);
			circle.setAttribute("r",r);
			circle.setAttribute("fill","transparent");
			circle.setAttribute("stroke","#d2d3d4");
			circle.setAttribute("stroke-width",sw);
			svg.appendChild(circle);
			

			strokeDasharray = (value*strokeDasharrayMax)/config.maxValue;
			var circle = document.createElementNS("http://www.w3.org/2000/svg", "circle");
			circle.setAttribute("cx",cx);
			circle.setAttribute("cy",cy);
			circle.setAttribute("r",r);
			circle.setAttribute("fill","transparent");
			circle.setAttribute("stroke",color);
			circle.setAttribute("stroke-width",sw);
			circle.setAttribute("stroke-dasharray",strokeDasharray+" "+strokeDasharrayMax);
			circle.setAttribute("stroke-dashoffset",0);
			svg.appendChild(circle);
			valueText=(config.percent)?value + "%":value;
			var text = document.createElementNS("http://www.w3.org/2000/svg", "text");
			text.setAttribute("x",config.graphSize/2-(valueText.length*textWidth/2));
			text.setAttribute("y",config.graphSize/2+textSize/4);
			text.setAttribute("font-family",textfamily);
			text.setAttribute("font-size",textSize);
			var textNode = document.createTextNode(valueText);
			text.appendChild(textNode);
			svg.appendChild(text);

			realWidth = config.graphSize;
			realHeight = config.graphSize;
			
			svg.setAttribute("width", realWidth);
			svg.setAttribute("height", realHeight);
		}
	</script>
</head>
<body class="w3-padding w3-theme-l5">
<div class="w3-container w3-content">
<?php
	# -------------------------------------------------
	# CONFIGURATION
	# -------------------------------------------------
	
	$config['username'] = $_REQUEST['username'];
	$config['passwd'] = $_REQUEST['passwd'];
	$config['host'] = $_REQUEST['host'];

	# -------------------------------------------------
	# FUNCTIONS
	# -------------------------------------------------

	function formatMBytes($size, $precision = 2){
		if ($size){
			return round($size/1048576,$precision) . ' MiB';
		}
		else
			return '0 MiB';
	}
	
	function write_circle($type='ok',$size=16,$title=null){
		switch(strtolower($type)){
			case 'ok':case 'on':
				$color='#28a745'; break;
			case 'warning':
				$color='#ffc107'; break;
			case 'error':
				$color='#dc3545'; break;
			default:
				$color = '#6c757d'; break;
		}
		return "<span title='".(($title)?$title:$type)."'><svg xmlns='http://www.w3.org/2000/svg' x='0px' y='0px' width='$size' height='$size' viewBox='0 0 50 50' style='fill:$color;'> <path d='M25,48C12.318,48,2,37.682,2,25S12.318,2,25,2s23,10.318,23,23S37.682,48,25,48z'></path></svg></span>";
	}
	
	function get_variable($name){
		global $config;
		return (isset($config[$name]))?$config[$name]:null;
	}
	
	function sort_stats($a, $b){ return strcmp($a["test"], $b["test"]); }					
	
	# -------------------------------------------------
	# MAIN CODE
	# -------------------------------------------------
?>
	<div class="w3-row-padding w3-stretch flex">
		<div class="w3-col l6 m12 s12 flex-item w3-section">
			<div class="w3-card w3-white w3-round w3-padding flex-content">
				<h1>MySQL Summary <span class="w3-small w3-text-grey">v1.0</span></h1>
				<form method="POST" class="rrc-hide-print">
					<label class="w3-small w3-text-grey"><strong>Servidor</strong></label>
					<input class="w3-input w3-border w3-small" type="text" name="host" placeholder="Nombre del servidor">
					<label class="w3-small w3-text-grey"><strong>Usuario</strong></label>
					<input class="w3-input w3-border w3-small" type="text" name="username" placeholder="Nombre de usuario">
					<label class="w3-small w3-text-grey"><strong>Contraseña</strong></label>
					<input class="w3-input w3-border w3-small" type="password" name="passwd" placeholder="Contraseña de acceso">
					<p><button class="w3-button w3-border w3-small" type="submit">Conectar</button></p>
				</form>
				<div class="w3-row-padding w3-stretch">
					<div class="w3-col w3-half w3-tiny"><a href="http://www.rrc2software.com">[www]</a> <a href="mail:ramonromancastro@gmail.com">[email]</a> <a href="https://github.com/ramonromancastro">[github]</a></div>
					<div class="w3-col w3-half"><a href="https://www.mysql.com/" target="_blank"><img class="w3-right" src="images/powered-by-mysql-125x64.png" title="Powered by MySQL" alt="Powered by MySQL"/></a></div>
				</div>
				<p class="w3-tiny w3-text-grey">
					Copyright 2019 by Ramón Román Castro.<br/>
					MySQL and MySQL logo son propiedad de Oracle Corporation.<br/>
					<a href="https://www.w3schools.com/w3css/" target="_blank">Powered by W3.CSS</a>.
				</p>
			</div>
		</div>
<?php
	function mysqlSummary(){
		$now = time();
		
		$host = get_variable('host');
		$username = get_variable('username');
		$passwd = get_variable('passwd');
		
		if (!empty($host)){
			$link = mysqli_connect($host, $username, $passwd);	
			if (mysqli_connect_errno()) {
				?>
		<div class='w3-col l6 m12 s12 flex-item w3-section'>
			<div class='w3-card w3-red w3-round w3-padding flex-content'>
				<h3>Atención</h3><p>Ha ocurrido realizando la conexión al servidor MySQL.</p>
			</div>
		</div>
				<?php
				return;
			}
		
			$status = array();
			$result = mysqli_query($link,"SHOW VARIABLES");
			while ($row = mysqli_fetch_assoc($result)) {
				$status[$row['Variable_name']] = $row['Value'];
			}
			mysqli_free_result($result);
			$result = mysqli_query($link,"SHOW STATUS");
			while ($row = mysqli_fetch_assoc($result)) {
				$status[$row['Variable_name']] = $row['Value'];
			}
			mysqli_free_result($result);

			$feature_validate_password = 'n/a';
			$feature_validate_password_value = isset($status['validate_password_policy'])?' (Policy '.$status['validate_password_policy'].')':'';
			if (version_compare($status['version'],'5.7') >= 0){
				$result = mysqli_query($link,"select * from information_schema.plugins where PLUGIN_NAME='validate_password' AND PLUGIN_STATUS='ACTIVE';");
				$feature_validate_password = (mysqli_num_rows($result))?'On':'Off';
				mysqli_free_result($result);
			}
			$performance_schema = isset($status['performance_schema'])?ucfirst(strtolower($status['performance_schema'])):'n/a';
			$ssl_availability = isset($status['have_ssl'])?ucfirst(strtolower($status['have_ssl'])):'n/a';
			$general_log = isset($status['general_log'])?ucfirst(strtolower($status['general_log'])):'n/a';
			$slow_query_log = isset($status['slow_query_log'])?ucfirst(strtolower($status['slow_query_log'])):'n/a';
?>
		<div class="w3-col l6 m12 s12 flex-item w3-section">
			<div class="w3-card w3-white w3-round w3-padding flex-content">
				<header class="w3-container w3-text-grey w3-large w3-padding">Información general</header>
				<div class="w3-responsive">
				<table class="w3-table w3-striped w3-small w3-bordered">
					<tbody>
						<tr><td>Servidor</td><td><strong><?php echo $status['hostname']; ?></strong></td></tr>
						<tr><td>Socket</td><td><?php echo $status['socket']; ?></td></tr>
						<tr><td>Versión</td><td><?php echo $status['version_comment']; ?> <?php echo $status['version']; ?></td></tr>
						<tr><td>Puerto</td><td><?php echo $status['port']; ?></td></tr>
						<tr><td>Compilado para</td><td><?php echo $status['version_compile_os']; ?> (<?php echo $status['version_compile_machine']; ?>)</td></tr>
						<tr><td>En ejecución desde</td><td><?php echo date('Y/m/d h:i:s',$now - $status['Uptime']); ?> (<?php echo floor($status['Uptime']/86400).' días, '.floor($status['Uptime']%86400/3600).' horas y '.floor($status['Uptime']%86400%3600/60). ' minutos'; ?>)</td></tr>
					</tbody>
				</table>
				</div>
			</div>
		</div>
	</div>
	<div class="w3-row-padding w3-stretch flex">
		<div class="w3-col l6 m12 s12 flex-item w3-section">
			<div class="w3-card w3-white w3-round w3-padding flex-content">
				<header class="w3-container w3-text-grey w3-large w3-padding">Configuración</header>
				<div class="w3-responsive">
				<table class="w3-table w3-striped w3-small w3-bordered">
					<tbody>
						<tr><td>Password validation <a title="Documentación oficial" target=_blank rel="noopener noreferrer" href="https://dev.mysql.com/doc/refman/<?php preg_match('/\d+\.\d+/',$status['version'], $match); echo $match[0]; ?>/en/validate-password.html">[?]</a></td><td><?php echo write_circle($feature_validate_password)." $feature_validate_password$feature_validate_password_value"; ?></td></tr>
						<tr><td>Perfomance schema <a title="Documentación oficial" target=_blank rel="noopener noreferrer" href="https://dev.mysql.com/doc/refman/<?php preg_match('/\d+\.\d+/',$status['version'], $match); echo $match[0]; ?>/en/performance-schema.html">[?]</a></td><td><?php echo write_circle($performance_schema)." $performance_schema"; ?> </td></tr>
						<tr><td>SSL availability</td><td><?php echo write_circle($ssl_availability)." $ssl_availability"; ?> </td></tr>
						<tr><td>Error log</td><td><?php echo $status['log_error']; ?> </td></tr>
						<tr><td>General log</td><td><?php echo write_circle($general_log)." $general_log"; ?> </td></tr>
						<tr><td>Slow query log</td><td><?php echo write_circle($slow_query_log)." $slow_query_log"; ?> </td></tr>
						<tr><td>collation_server</td><td><?php echo $status['collation_server']; ?></td></tr>
						<tr><td>collation_database</td><td><?php echo $status['collation_database']; ?></td></tr>
						<tr><td>collation_connection</td><td><?php echo $status['collation_connection']; ?></td></tr>
						<tr><td>init_connect</td><td><?php echo $status['init_connect']; ?></td></tr>
						<tr><td>character_set_connection</td><td><?php echo $status['character_set_connection']; ?></td></tr>
						<tr><td>character_set_database</td><td><?php echo $status['character_set_database']; ?></td></tr>
						<tr><td>default_storage_engine</td><td><?php echo $status['default_storage_engine']; ?></td></tr>
						<tr><td>default_tmp_storage_engine</td><td><?php echo $status['default_tmp_storage_engine']; ?></td></tr>
						<tr>
							<td>Storage engines</td>
							<td>
<?php
			$result = mysqli_query($link,"SHOW ENGINES");
			while ($row = mysqli_fetch_assoc($result)) {
				echo '<span title="'.$row['Comment'].'" class="w3-text-'.(($row['Support'] == 'NO')?'red':(($row['Support'] == 'DEFAULT')?'blue':'green')).'">'.(($row['Support'] == 'NO')?'-':(($row['Support'] == 'DEFAULT')?'+':'+')).''.$row['Engine'].'</span> ';
			}
			mysqli_free_result($result);
?>
							</td>
						</tr>
					</tbody>
				</table>
				</div>
				<div class="w3-section w3-tiny w3-panel w3-pale-blue w3-leftbar w3-border-blue w3-padding">La variable [<strong>init_connect</strong>] NO se aplica si el usuario tiene el rol <strong>SUPER</strong>.</div>
			</div>
		</div>
		<div class="w3-col l6 m12 s12 flex-item w3-section">
			<div class="w3-card w3-white w3-round w3-padding flex-content">
				<header class="w3-container w3-text-grey w3-large w3-padding">Estadísticas de ejecución</header>
				<div class="w3-row w3-small flex">
<?php
			$stats = array(
				array(
					"test" => "Active connections",
					"percent" => $status["Threads_connected"]/$status["max_connections"],
					"limit" => ">85"),
				array(
					"test" => "Aborted connections",
					"percent" => ($status["Connections"])?$status["Aborted_connects"]/$status["Connections"]:0,
					"limit" => ">5"),
				array(
					"test" => "Max. concurrent connections",
					"percent" => $status["Max_used_connections"]/$status["max_connections"],
					"limit" => ">85"),
				array(
					"test" => "Slow queries",
					"percent" => ($status["Queries"])?$status["Slow_queries"]/$status["Queries"]:0,
					"limit" => ">5"),
				array(
					"test" => "Key Read Efficiency",
					"percent" => ($status["Key_read_requests"])?1-($status["Key_reads"]/$status["Key_read_requests"]):0,
					"limit" => "<90"),
				array(
					"test" => "Key Write Efficiency",
					"percent" => ($status["Key_write_requests"])?$status["Key_writes"]/$status["Key_write_requests"]:0,
					"limit" => "<90"),
				array(
					"test" => "InnoDB Read buffer efficiency",
					"percent" => ($status["Innodb_buffer_pool_read_requests"])?($status["Innodb_buffer_pool_read_requests"]-$status["Innodb_buffer_pool_reads"])/$status["Innodb_buffer_pool_read_requests"]:0,
					"limit" => "<90"),
				array(
					"test" => "InnoDB Write log efficiency",
					"percent" => ($status["Innodb_log_write_requests"])?($status["Innodb_log_write_requests"]-$status["Innodb_log_writes"])/$status["Innodb_log_write_requests"]:0,
					"limit" => "<90"),
				array(
					"test" => "Query cache efficiency",
					"percent" => ($status["Com_select"] + $status["Qcache_hits"])?$status["Qcache_hits"]/($status["Com_select"]+$status["Qcache_hits"]):0,
					"limit" => "<20"),
				array(
					"test" => "Open files",
					"percent" => ($status["open_files_limit"])?$status["Open_files"]/$status["open_files_limit"]:0,
					"limit" => ">85"),
				array(
					"test" => "Thread cache hit rate",
					"percent" => ($status["Connections"])?$status["Threads_created"]/$status["Connections"]:0,
					"limit" => ">50"),
					
				array(
					"test" => "InnoDB buffer usage",
					"percent" => ($status["Innodb_buffer_pool_pages_total"])?$status["Innodb_buffer_pool_pages_free"]/$status["Innodb_buffer_pool_pages_total"]:0,
					"limit" => null),
				array(
					"test" => "Temporary tables created on disk",
					"percent" => ($status["Created_tmp_tables"])?$status["Created_tmp_disk_tables"]/$status["Created_tmp_tables"]:0,
					"limit" => ">25"),
				);

			usort($stats,"sort_stats");
			
			foreach($stats as $key=>$item){
				$background = 'green';
				$percent = round($item['percent']*100,2);
				if (isset($item['limit'])){
					$operator = substr($item['limit'],0,1);
					$limit = substr($item['limit'],1);
					switch($operator){
						case ">":
							if ($percent > $limit) $background = 'red';
							break;
						case "<":
							if ($percent < $limit) $background = 'red';
							break;
						default:
							$background = 'orange';
					}
				}
?>
					<div class="w3-col l4 m4 s6 flex-item flex-content rrc-donut">
						<div class="w3-panel">
							<svg id="donutStats<?php echo $host.$key; ?>"></svg><br/>
							<?php echo $item['test']; ?>
						</div>		
					</div>
					<script>graph_donutValue('donutStats<?php echo $host.$key; ?>','<?php echo $percent; ?>',{graphSize:100,color:'<?php echo $background; ?>'})</script>
<?php
			}
?>
				</div>
			</div>
		</div>
	</div>
	<div class="w3-row-padding w3-stretch flex">
		<div class="w3-col l12 m12 s12 flex-item w3-section">
			<div class="w3-card w3-white w3-round w3-padding flex-content">
				<header class="w3-container w3-text-grey w3-large w3-padding">Bases de datos</header>
				<div class="w3-responsive">
				<table class="w3-table w3-striped w3-small w3-bordered">
					<thead>
						<tr>
							<th>Nombre</th>
							<th>Conjunto de caracteres</th>
							<th>Cotejamiento</th>
							<th class='w3-right-align'>Tamaño</th>
							<th class='w3-right-align'>Residuo</th>
							<th class='w3-right-align'>%Residuo</th>
						</tr>
					</thead>
					<tbody>
<?php
			$result = mysqli_query($link,"SELECT SCHEMA_NAME, DEFAULT_CHARACTER_SET_NAME, DEFAULT_COLLATION_NAME FROM information_schema.SCHEMATA ORDER BY SCHEMA_NAME");
			while ($row = mysqli_fetch_assoc($result)) {
				echo "<tr>";
				echo "<td>".$row['SCHEMA_NAME']."</td>";
				echo "<td>".$row['DEFAULT_CHARACTER_SET_NAME']."</td>";
				echo "<td>".$row['DEFAULT_COLLATION_NAME']."</td>";
				$result2 = mysqli_query($link,"SELECT table_schema, sum(data_length+index_length), SUM(data_free) FROM information_schema.TABLES WHERE table_schema = '".$row['SCHEMA_NAME']."' GROUP BY table_schema");
				$row2 = mysqli_fetch_array($result2);
				echo "<td class='w3-right-align'>".formatMBytes($row2[1])."</td>";
				echo "<td class='w3-right-align'>".formatMBytes($row2[2])."</td>";
				echo "<td class='w3-right-align'>".round((($row2[2])?(($row2[2]*100)/($row2[1]+$row2[2])):20),2)."%</td>";
				mysqli_free_result($result2);
				echo "</tr>";
			}
			mysqli_free_result($result);
?>
					</tbody>
				</table>
				</div>
			</div>
		</div>
	</div>
	<div class="w3-row-padding w3-stretch flex">
		<div class="w3-col l12 m12 s12 flex-item w3-section">
			<div class="w3-card w3-white w3-round w3-padding flex-content">
				<header class="w3-container w3-text-grey w3-large w3-padding">Usuarios</header>
				<div class="w3-responsive">
				<table class="w3-table w3-striped w3-small w3-bordered">
				<thead>
					<tr>
						<th>Tipo</th>
						<th>Usuario</th>
						<th>Host</th>
						<th>Base de datos</th>
						<th>Tabla.Columna</th>
						<th>Privilegios</th>
					</tr>
				</thead>
				<tbody>
<!-- GLOBALES !-->
<?php
			$result = mysqli_query($link,"SELECT User,Host,Select_priv,Insert_priv,Update_priv,Delete_priv,Create_priv,Drop_priv,Reload_priv,Shutdown_priv,Process_priv,File_priv,Grant_priv,References_priv,Index_priv,Alter_priv,Show_db_priv,Super_priv,Create_tmp_table_priv,Lock_tables_priv,Execute_priv,Repl_slave_priv,Repl_client_priv,Create_view_priv,Show_view_priv,Create_routine_priv,Alter_routine_priv,Create_user_priv FROM mysql.user ORDER BY User, Host");
			while ($row = mysqli_fetch_assoc($result)) {
				echo "<tr>";
				echo "<td>GLOBAL</td>";
				echo "<td>".$row['User']."</td>";
				echo "<td>".$row['Host']."</td>";
				echo "<td>*</td>";
				echo "<td>*</td>";
				$privileges="";
				$fields = mysqli_fetch_fields($result);
				foreach($fields as $meta){
					if (($meta->name != "User") && ($meta->name != "Host") && ($meta->name != "Db")){
						if ($row[$meta->name] == "Y"){
							if (trim($privileges) == "")
								$privileges = str_replace("_priv", "", $meta->name);
							else
								$privileges = $privileges.', '.str_replace("_priv", "", $meta->name);
						}
					}
				}
				echo "<td>$privileges</td>";
				echo "</tr>";
			}
			mysqli_free_result($result);
?>
<!-- BASES DE DATOS !-->
<?php
			$result = mysqli_query($link,"SELECT User, Host, Db, Select_priv, Insert_priv, Update_priv, Delete_priv, Create_priv, Drop_priv, Grant_priv, References_priv, Index_priv, Alter_priv, Create_tmp_table_priv, Lock_tables_priv, Create_view_priv, Show_view_priv, Create_routine_priv, Alter_routine_priv, Execute_priv FROM mysql.db ORDER BY User, Host, Db");
			while ($row = mysqli_fetch_assoc($result)) {
				echo "<tr>";
				echo "<td>DATABASE</td>";
				echo "<td>".$row['User']."</td>";
				echo "<td>".$row['Host']."</td>";
				echo "<td>".$row['Db']."</td>";
				echo "<td>*</td>";
				$privileges="";
				$fields = mysqli_fetch_fields($result);
				foreach($fields as $meta){
					if (($meta->name != "User") && ($meta->name != "Host") && ($meta->name != "Db")){
						if ($row[$meta->name] == "Y"){
							if (trim($privileges) == "")
								$privileges = str_replace("_priv", "", $meta->name);
							else
								$privileges = $privileges.', '.str_replace("_priv", "", $meta->name);
						}
					}
				}
				echo "<td>$privileges</td>";
				echo "</tr>";
			}
			mysqli_free_result($result);
?>
<!-- TABLAS !-->
<?php
			$result = mysqli_query($link,"SELECT User, Host, Db, Table_name, Table_priv FROM mysql.tables_priv ORDER BY User, Host, Db, Table_name");
			while ($row = mysqli_fetch_assoc($result)) {
				echo "<tr>";
				echo "<td>TABLE</td>";
				echo "<td>".$row['User']."</td>";
				echo "<td>".$row['Host']."</td>";
				echo "<td>".$row['Db']."</td>";
				echo "<td>".$row['Table_name'].".*</td>";
				echo "<td>".str_replace(",", ", ", $row['Table_priv'])."</td>";
				echo "</tr>";
			}
			mysqli_free_result($result);
?>
<!-- COLUMNAS !-->
<?php
			$result = mysqli_query($link,"SELECT User, Host, Db, CONCAT(Table_name,\".\",Column_name) AS TableColumn_name, Column_priv FROM mysql.columns_priv ORDER BY User, Host, Db, Table_name, Column_name");
			while ($row = mysqli_fetch_assoc($result)) {
				echo "<tr>";
				echo "<td>COLUMN</td>";
				echo "<td>".$row['User']."</td>";
				echo "<td>".$row['Host']."</td>";
				echo "<td>".$row['Db']."</td>";
				echo "<td>".$row['TableColumn_name']."</td>";
				echo "<td>".str_replace(",", ", ", $row['Column_priv'])."</td>";
				echo "</tr>";
			}
			mysqli_free_result($result);
?>
					</tbody>
				</table>
				</div>
			</div>
		</div>
	</div>
<?php
			mysqli_close($link);
		}
	}
	
	mysqlSummary();
?>
</div>
</body>
</html>
