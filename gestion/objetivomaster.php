<?php

// idObjetivo
// nombre
// comentarios
// duracion
// formatoDuracion
// fechaInicio
// fechFin
// proyecto
// tipo

?>
<?php if ($objetivo->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $objetivo->TableCaption() ?></h4> -->
<table id="tbl_objetivomaster" class="table table-bordered table-striped ewViewTable">
<?php echo $objetivo->TableCustomInnerHtml ?>
	<tbody>
<?php if ($objetivo->idObjetivo->Visible) { // idObjetivo ?>
		<tr id="r_idObjetivo">
			<td><?php echo $objetivo->idObjetivo->FldCaption() ?></td>
			<td<?php echo $objetivo->idObjetivo->CellAttributes() ?>>
<span id="el_objetivo_idObjetivo">
<span<?php echo $objetivo->idObjetivo->ViewAttributes() ?>>
<?php echo $objetivo->idObjetivo->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($objetivo->nombre->Visible) { // nombre ?>
		<tr id="r_nombre">
			<td><?php echo $objetivo->nombre->FldCaption() ?></td>
			<td<?php echo $objetivo->nombre->CellAttributes() ?>>
<span id="el_objetivo_nombre">
<span<?php echo $objetivo->nombre->ViewAttributes() ?>>
<?php echo $objetivo->nombre->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($objetivo->comentarios->Visible) { // comentarios ?>
		<tr id="r_comentarios">
			<td><?php echo $objetivo->comentarios->FldCaption() ?></td>
			<td<?php echo $objetivo->comentarios->CellAttributes() ?>>
<span id="el_objetivo_comentarios">
<span<?php echo $objetivo->comentarios->ViewAttributes() ?>>
<?php echo $objetivo->comentarios->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($objetivo->duracion->Visible) { // duracion ?>
		<tr id="r_duracion">
			<td><?php echo $objetivo->duracion->FldCaption() ?></td>
			<td<?php echo $objetivo->duracion->CellAttributes() ?>>
<span id="el_objetivo_duracion">
<span<?php echo $objetivo->duracion->ViewAttributes() ?>>
<?php echo $objetivo->duracion->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($objetivo->formatoDuracion->Visible) { // formatoDuracion ?>
		<tr id="r_formatoDuracion">
			<td><?php echo $objetivo->formatoDuracion->FldCaption() ?></td>
			<td<?php echo $objetivo->formatoDuracion->CellAttributes() ?>>
<span id="el_objetivo_formatoDuracion">
<span<?php echo $objetivo->formatoDuracion->ViewAttributes() ?>>
<?php echo $objetivo->formatoDuracion->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($objetivo->fechaInicio->Visible) { // fechaInicio ?>
		<tr id="r_fechaInicio">
			<td><?php echo $objetivo->fechaInicio->FldCaption() ?></td>
			<td<?php echo $objetivo->fechaInicio->CellAttributes() ?>>
<span id="el_objetivo_fechaInicio">
<span<?php echo $objetivo->fechaInicio->ViewAttributes() ?>>
<?php echo $objetivo->fechaInicio->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($objetivo->fechFin->Visible) { // fechFin ?>
		<tr id="r_fechFin">
			<td><?php echo $objetivo->fechFin->FldCaption() ?></td>
			<td<?php echo $objetivo->fechFin->CellAttributes() ?>>
<span id="el_objetivo_fechFin">
<span<?php echo $objetivo->fechFin->ViewAttributes() ?>>
<?php echo $objetivo->fechFin->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($objetivo->proyecto->Visible) { // proyecto ?>
		<tr id="r_proyecto">
			<td><?php echo $objetivo->proyecto->FldCaption() ?></td>
			<td<?php echo $objetivo->proyecto->CellAttributes() ?>>
<span id="el_objetivo_proyecto">
<span<?php echo $objetivo->proyecto->ViewAttributes() ?>>
<?php echo $objetivo->proyecto->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($objetivo->tipo->Visible) { // tipo ?>
		<tr id="r_tipo">
			<td><?php echo $objetivo->tipo->FldCaption() ?></td>
			<td<?php echo $objetivo->tipo->CellAttributes() ?>>
<span id="el_objetivo_tipo">
<span<?php echo $objetivo->tipo->ViewAttributes() ?>>
<?php echo $objetivo->tipo->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
