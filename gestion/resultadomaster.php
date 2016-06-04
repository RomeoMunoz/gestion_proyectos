<?php

// idResultado
// objetivo
// nombre
// tiempoEstimado
// tiempoTipo
// fechaInicio
// fechaFin
// estatus

?>
<?php if ($resultado->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $resultado->TableCaption() ?></h4> -->
<table id="tbl_resultadomaster" class="table table-bordered table-striped ewViewTable">
<?php echo $resultado->TableCustomInnerHtml ?>
	<tbody>
<?php if ($resultado->idResultado->Visible) { // idResultado ?>
		<tr id="r_idResultado">
			<td><?php echo $resultado->idResultado->FldCaption() ?></td>
			<td<?php echo $resultado->idResultado->CellAttributes() ?>>
<span id="el_resultado_idResultado">
<span<?php echo $resultado->idResultado->ViewAttributes() ?>>
<?php echo $resultado->idResultado->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($resultado->objetivo->Visible) { // objetivo ?>
		<tr id="r_objetivo">
			<td><?php echo $resultado->objetivo->FldCaption() ?></td>
			<td<?php echo $resultado->objetivo->CellAttributes() ?>>
<span id="el_resultado_objetivo">
<span<?php echo $resultado->objetivo->ViewAttributes() ?>>
<?php echo $resultado->objetivo->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($resultado->nombre->Visible) { // nombre ?>
		<tr id="r_nombre">
			<td><?php echo $resultado->nombre->FldCaption() ?></td>
			<td<?php echo $resultado->nombre->CellAttributes() ?>>
<span id="el_resultado_nombre">
<span<?php echo $resultado->nombre->ViewAttributes() ?>>
<?php echo $resultado->nombre->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($resultado->tiempoEstimado->Visible) { // tiempoEstimado ?>
		<tr id="r_tiempoEstimado">
			<td><?php echo $resultado->tiempoEstimado->FldCaption() ?></td>
			<td<?php echo $resultado->tiempoEstimado->CellAttributes() ?>>
<span id="el_resultado_tiempoEstimado">
<span<?php echo $resultado->tiempoEstimado->ViewAttributes() ?>>
<?php echo $resultado->tiempoEstimado->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($resultado->tiempoTipo->Visible) { // tiempoTipo ?>
		<tr id="r_tiempoTipo">
			<td><?php echo $resultado->tiempoTipo->FldCaption() ?></td>
			<td<?php echo $resultado->tiempoTipo->CellAttributes() ?>>
<span id="el_resultado_tiempoTipo">
<span<?php echo $resultado->tiempoTipo->ViewAttributes() ?>>
<?php echo $resultado->tiempoTipo->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($resultado->fechaInicio->Visible) { // fechaInicio ?>
		<tr id="r_fechaInicio">
			<td><?php echo $resultado->fechaInicio->FldCaption() ?></td>
			<td<?php echo $resultado->fechaInicio->CellAttributes() ?>>
<span id="el_resultado_fechaInicio">
<span<?php echo $resultado->fechaInicio->ViewAttributes() ?>>
<?php echo $resultado->fechaInicio->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($resultado->fechaFin->Visible) { // fechaFin ?>
		<tr id="r_fechaFin">
			<td><?php echo $resultado->fechaFin->FldCaption() ?></td>
			<td<?php echo $resultado->fechaFin->CellAttributes() ?>>
<span id="el_resultado_fechaFin">
<span<?php echo $resultado->fechaFin->ViewAttributes() ?>>
<?php echo $resultado->fechaFin->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($resultado->estatus->Visible) { // estatus ?>
		<tr id="r_estatus">
			<td><?php echo $resultado->estatus->FldCaption() ?></td>
			<td<?php echo $resultado->estatus->CellAttributes() ?>>
<span id="el_resultado_estatus">
<span<?php echo $resultado->estatus->ViewAttributes() ?>>
<?php echo $resultado->estatus->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
