<?php
	require '../../plugins/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php';
	$fichero_subido = $_POST['doc'];
	$objPHPExcel = PHPExcel_IOFactory::load($fichero_subido);
	$sheetNames = $objPHPExcel->getSheetNames();
	$list = '';
	$sheet = '';
	for ($i=0; $i < count($sheetNames); $i++) {
		$checked = ($i == $_POST['sheet']) ? "checked":"";
		$sheet.='<div class="input-group mar-btm">
				<span class="input-group-addon">
					<input  id="radio'.$i.'" class="magic-radio sheet" '.$checked.'  name="sheet" type="radio" value="'.$i.'">
					<label for="radio'.$i.'"></label>
				</span>
				<span class="form-control">'.$sheetNames[$i].'</span>
			</div>';
	}
	$objPHPExcel->setActiveSheetIndex($_POST['sheet']);
	$numColumnI = $objPHPExcel->setActiveSheetIndex($_POST['sheet'])->getHighestColumn();
	$numColumnI = PHPExcel_Cell::columnIndexFromString($numColumnI);
	$numRows = $objPHPExcel->setActiveSheetIndex($_POST['sheet'])->getHighestRow();
	for ($i = 0; $i < $numColumnI; $i++) {
		$cell =$objPHPExcel->getActiveSheet()->getCellByColumnAndRow($i, 1);
		$value= $cell->getValue();
		$array[] = $value;
	}
	$list.= '<div class="col-md-12">Solo puede seleccionar 3 campos <br><br></div><div class="col-md-12">'.$sheet.'</div><div class="col-md-6"><label>Lista de columnas del Documento</label>';
	for ($i=0; $i < count($array) ; $i++) {
		$list.= '<span class="listTag" id="listTag'.$i.'">'.$array[$i].'</span>';
	}
	$list.= '</div><div class="col-md-6 form-group"><label >Listas de Campos</label>';
	for ($i=0; $i < count($array) ; $i++) {
		$list.= '<select class="form-control marB15 campos" attr="'.$i.'" attr2="0">
			<option value="">Seleccione...</option>
			<option value="Rut">Rut</option>
			<option value="Codigo">Codigo de Area</option>
			<option value="Fono">Telefono</option>
		</select>';
	}
	$list.= "</div><div class='col-md-12'><button type='button' id='procesar' class='btn btn-primary' url='".$fichero_subido."'> Procesar </button></div>";
	echo json_encode($arr = [1,$list]);
 ?>