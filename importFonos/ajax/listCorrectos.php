<?php
	include("../../class/db/DB.php");
	$operation = new Db();
	$rows = $operation -> select("SELECT Rut, Fono, FechaRegistro FROM fonos_correctos");

	$lista = "<table class='table table-striped TableEmpresas'>
					<thead>
						<tr>
							<th>Rut</th>
							<th>Telefono</th>
							<th>Fecha de Registro</th>
						</tr>
					</thead>
					<tbody>";

	for ($i=0; $i < count($rows) ; $i++) {
		$lista.= '<tr>
				<td>'.$rows[$i]['Rut'].'</td>
				<td>'.$rows[$i]['Fono'].'</td>
				<td>'.$rows[$i]['FechaRegistro'].'</td>
			</tr>';
	}

	$lista.="</tbody></table>";

	echo $lista;

 ?>