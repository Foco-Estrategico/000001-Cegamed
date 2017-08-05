<?php
	include("../../class/db/DB.php");
	$operation = new Db();
	$rows = $operation -> select("SELECT IdFonosIncorrectos, Rut, Fono FROM fonos_incorrectos");

	$lista = "<table class='table table-striped TableEmpresas'>
					<thead>
						<tr>
							<th>Rut</th>
							<th>Telefono</th>
							<th></th>
						</tr>
					</thead>
					<tbody>";

	for ($i=0; $i < count($rows) ; $i++) {
		$lista.= '<tr>
				<td>'.$rows[$i]['Rut'].'</td>
				<td>'.$rows[$i]['Fono'].'</td>
				<td style="text-align: right;">
					<a attr="'.$rows[$i]['IdFonosIncorrectos'].'" class="btn btn-success edit">Editar</a>
					<button type="button" class="btn btn-danger unlink" attr="'.$rows[$i]['IdFonosIncorrectos'].'">Eliminar</button>
				</td>
			</tr>';
	}

	$lista.="</tbody></table>";

	echo $lista;

 ?>