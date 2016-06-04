<?php

// nombre
// fechaInicio
// fechaFin
// usuarioLider
// usuarioEncargado
// cliente
// estatus

?>
<?php if ($proyecto->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $proyecto->TableCaption() ?></h4> -->
<table id="tbl_proyectomaster" class="table table-bordered table-striped ewViewTable">
<?php echo $proyecto->TableCustomInnerHtml ?>
	<tbody>
<?php if ($proyecto->nombre->Visible) { // nombre ?>
		<tr id="r_nombre">
			<td><?php echo $proyecto->nombre->FldCaption() ?></td>
			<td<?php echo $proyecto->nombre->CellAttributes() ?>>
<span id="el_proyecto_nombre">
<span<?php echo $proyecto->nombre->ViewAttributes() ?>>
<?php echo $proyecto->nombre->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($proyecto->fechaInicio->Visible) { // fechaInicio ?>
		<tr id="r_fechaInicio">
			<td><?php echo $proyecto->fechaInicio->FldCaption() ?></td>
			<td<?php echo $proyecto->fechaInicio->CellAttributes() ?>>
<span id="el_proyecto_fechaInicio">
<span<?php echo $proyecto->fechaInicio->ViewAttributes() ?>>
<?php echo $proyecto->fechaInicio->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($proyecto->fechaFin->Visible) { // fechaFin ?>
		<tr id="r_fechaFin">
			<td><?php echo $proyecto->fechaFin->FldCaption() ?></td>
			<td<?php echo $proyecto->fechaFin->CellAttributes() ?>>
<span id="el_proyecto_fechaFin">
<span<?php echo $proyecto->fechaFin->ViewAttributes() ?>>
<?php echo $proyecto->fechaFin->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($proyecto->usuarioLider->Visible) { // usuarioLider ?>
		<tr id="r_usuarioLider">
			<td><?php echo $proyecto->usuarioLider->FldCaption() ?></td>
			<td<?php echo $proyecto->usuarioLider->CellAttributes() ?>>
<span id="el_proyecto_usuarioLider">
<span<?php echo $proyecto->usuarioLider->ViewAttributes() ?>>
<?php echo $proyecto->usuarioLider->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($proyecto->usuarioEncargado->Visible) { // usuarioEncargado ?>
		<tr id="r_usuarioEncargado">
			<td><?php echo $proyecto->usuarioEncargado->FldCaption() ?></td>
			<td<?php echo $proyecto->usuarioEncargado->CellAttributes() ?>>
<span id="el_proyecto_usuarioEncargado">
<span<?php echo $proyecto->usuarioEncargado->ViewAttributes() ?>>
<?php echo $proyecto->usuarioEncargado->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($proyecto->cliente->Visible) { // cliente ?>
		<tr id="r_cliente">
			<td><?php echo $proyecto->cliente->FldCaption() ?></td>
			<td<?php echo $proyecto->cliente->CellAttributes() ?>>
<span id="el_proyecto_cliente">
<span<?php echo $proyecto->cliente->ViewAttributes() ?>>
<?php echo $proyecto->cliente->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($proyecto->estatus->Visible) { // estatus ?>
		<tr id="r_estatus">
			<td><?php echo $proyecto->estatus->FldCaption() ?></td>
			<td<?php echo $proyecto->estatus->CellAttributes() ?>>
<span id="el_proyecto_estatus">
<span<?php echo $proyecto->estatus->ViewAttributes() ?>>
<?php echo $proyecto->estatus->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
