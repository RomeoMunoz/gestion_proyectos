<?php include_once "usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($actividad_grid)) $actividad_grid = new cactividad_grid();

// Page init
$actividad_grid->Page_Init();

// Page main
$actividad_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$actividad_grid->Page_Render();
?>
<?php if ($actividad->Export == "") { ?>
<script type="text/javascript">

// Form object
var factividadgrid = new ew_Form("factividadgrid", "grid");
factividadgrid.FormKeyCountName = '<?php echo $actividad_grid->FormKeyCountName ?>';

// Validate form
factividadgrid.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
		var checkrow = (gridinsert) ? !this.EmptyRow(infix) : true;
		if (checkrow) {
			addcnt++;
			elm = this.GetElements("x" + infix + "_avance");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $actividad->avance->FldCaption(), $actividad->avance->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_nombre");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $actividad->nombre->FldCaption(), $actividad->nombre->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_duracion");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $actividad->duracion->FldCaption(), $actividad->duracion->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_duracion");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($actividad->duracion->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tipoDuracion");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $actividad->tipoDuracion->FldCaption(), $actividad->tipoDuracion->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_fechaInicio");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($actividad->fechaInicio->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_fechaFin");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($actividad->fechaFin->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_predecesora");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($actividad->predecesora->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_estatus");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $actividad->estatus->FldCaption(), $actividad->estatus->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
factividadgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "avance", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nombre", false)) return false;
	if (ew_ValueChanged(fobj, infix, "duracion", false)) return false;
	if (ew_ValueChanged(fobj, infix, "tipoDuracion", false)) return false;
	if (ew_ValueChanged(fobj, infix, "fechaInicio", false)) return false;
	if (ew_ValueChanged(fobj, infix, "fechaFin", false)) return false;
	if (ew_ValueChanged(fobj, infix, "predecesora", false)) return false;
	if (ew_ValueChanged(fobj, infix, "recurso", false)) return false;
	if (ew_ValueChanged(fobj, infix, "estatus", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Resultado", false)) return false;
	return true;
}

// Form_CustomValidate event
factividadgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
factividadgrid.ValidateRequired = true;
<?php } else { ?>
factividadgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
factividadgrid.Lists["x_avance"] = {"LinkField":"x_idAvance","Ajax":true,"AutoFill":false,"DisplayFields":["x_cantidad","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
factividadgrid.Lists["x_tipoDuracion"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
factividadgrid.Lists["x_tipoDuracion"].Options = <?php echo json_encode($actividad->tipoDuracion->Options()) ?>;
factividadgrid.Lists["x_recurso"] = {"LinkField":"x_idUsuario","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombres","x_apellidos","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
factividadgrid.Lists["x_estatus"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
factividadgrid.Lists["x_estatus"].Options = <?php echo json_encode($actividad->estatus->Options()) ?>;
factividadgrid.Lists["x_Resultado"] = {"LinkField":"x_idResultado","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
</script>
<?php } ?>
<?php
if ($actividad->CurrentAction == "gridadd") {
	if ($actividad->CurrentMode == "copy") {
		$bSelectLimit = $actividad_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$actividad_grid->TotalRecs = $actividad->SelectRecordCount();
			$actividad_grid->Recordset = $actividad_grid->LoadRecordset($actividad_grid->StartRec-1, $actividad_grid->DisplayRecs);
		} else {
			if ($actividad_grid->Recordset = $actividad_grid->LoadRecordset())
				$actividad_grid->TotalRecs = $actividad_grid->Recordset->RecordCount();
		}
		$actividad_grid->StartRec = 1;
		$actividad_grid->DisplayRecs = $actividad_grid->TotalRecs;
	} else {
		$actividad->CurrentFilter = "0=1";
		$actividad_grid->StartRec = 1;
		$actividad_grid->DisplayRecs = $actividad->GridAddRowCount;
	}
	$actividad_grid->TotalRecs = $actividad_grid->DisplayRecs;
	$actividad_grid->StopRec = $actividad_grid->DisplayRecs;
} else {
	$bSelectLimit = $actividad_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($actividad_grid->TotalRecs <= 0)
			$actividad_grid->TotalRecs = $actividad->SelectRecordCount();
	} else {
		if (!$actividad_grid->Recordset && ($actividad_grid->Recordset = $actividad_grid->LoadRecordset()))
			$actividad_grid->TotalRecs = $actividad_grid->Recordset->RecordCount();
	}
	$actividad_grid->StartRec = 1;
	$actividad_grid->DisplayRecs = $actividad_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$actividad_grid->Recordset = $actividad_grid->LoadRecordset($actividad_grid->StartRec-1, $actividad_grid->DisplayRecs);

	// Set no record found message
	if ($actividad->CurrentAction == "" && $actividad_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$actividad_grid->setWarningMessage(ew_DeniedMsg());
		if ($actividad_grid->SearchWhere == "0=101")
			$actividad_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$actividad_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$actividad_grid->RenderOtherOptions();
?>
<?php $actividad_grid->ShowPageHeader(); ?>
<?php
$actividad_grid->ShowMessage();
?>
<?php if ($actividad_grid->TotalRecs > 0 || $actividad->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid">
<div id="factividadgrid" class="ewForm form-inline">
<?php if ($actividad_grid->ShowOtherOptions) { ?>
<div class="panel-heading ewGridUpperPanel">
<?php
	foreach ($actividad_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_actividad" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_actividadgrid" class="table ewTable">
<?php echo $actividad->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$actividad_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$actividad_grid->RenderListOptions();

// Render list options (header, left)
$actividad_grid->ListOptions->Render("header", "left");
?>
<?php if ($actividad->idActividad->Visible) { // idActividad ?>
	<?php if ($actividad->SortUrl($actividad->idActividad) == "") { ?>
		<th data-name="idActividad"><div id="elh_actividad_idActividad" class="actividad_idActividad"><div class="ewTableHeaderCaption"><?php echo $actividad->idActividad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idActividad"><div><div id="elh_actividad_idActividad" class="actividad_idActividad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $actividad->idActividad->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($actividad->idActividad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($actividad->idActividad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($actividad->avance->Visible) { // avance ?>
	<?php if ($actividad->SortUrl($actividad->avance) == "") { ?>
		<th data-name="avance"><div id="elh_actividad_avance" class="actividad_avance"><div class="ewTableHeaderCaption"><?php echo $actividad->avance->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="avance"><div><div id="elh_actividad_avance" class="actividad_avance">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $actividad->avance->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($actividad->avance->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($actividad->avance->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($actividad->nombre->Visible) { // nombre ?>
	<?php if ($actividad->SortUrl($actividad->nombre) == "") { ?>
		<th data-name="nombre"><div id="elh_actividad_nombre" class="actividad_nombre"><div class="ewTableHeaderCaption"><?php echo $actividad->nombre->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nombre"><div><div id="elh_actividad_nombre" class="actividad_nombre">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $actividad->nombre->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($actividad->nombre->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($actividad->nombre->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($actividad->duracion->Visible) { // duracion ?>
	<?php if ($actividad->SortUrl($actividad->duracion) == "") { ?>
		<th data-name="duracion"><div id="elh_actividad_duracion" class="actividad_duracion"><div class="ewTableHeaderCaption"><?php echo $actividad->duracion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="duracion"><div><div id="elh_actividad_duracion" class="actividad_duracion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $actividad->duracion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($actividad->duracion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($actividad->duracion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($actividad->tipoDuracion->Visible) { // tipoDuracion ?>
	<?php if ($actividad->SortUrl($actividad->tipoDuracion) == "") { ?>
		<th data-name="tipoDuracion"><div id="elh_actividad_tipoDuracion" class="actividad_tipoDuracion"><div class="ewTableHeaderCaption"><?php echo $actividad->tipoDuracion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tipoDuracion"><div><div id="elh_actividad_tipoDuracion" class="actividad_tipoDuracion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $actividad->tipoDuracion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($actividad->tipoDuracion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($actividad->tipoDuracion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($actividad->fechaInicio->Visible) { // fechaInicio ?>
	<?php if ($actividad->SortUrl($actividad->fechaInicio) == "") { ?>
		<th data-name="fechaInicio"><div id="elh_actividad_fechaInicio" class="actividad_fechaInicio"><div class="ewTableHeaderCaption"><?php echo $actividad->fechaInicio->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fechaInicio"><div><div id="elh_actividad_fechaInicio" class="actividad_fechaInicio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $actividad->fechaInicio->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($actividad->fechaInicio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($actividad->fechaInicio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($actividad->fechaFin->Visible) { // fechaFin ?>
	<?php if ($actividad->SortUrl($actividad->fechaFin) == "") { ?>
		<th data-name="fechaFin"><div id="elh_actividad_fechaFin" class="actividad_fechaFin"><div class="ewTableHeaderCaption"><?php echo $actividad->fechaFin->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fechaFin"><div><div id="elh_actividad_fechaFin" class="actividad_fechaFin">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $actividad->fechaFin->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($actividad->fechaFin->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($actividad->fechaFin->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($actividad->predecesora->Visible) { // predecesora ?>
	<?php if ($actividad->SortUrl($actividad->predecesora) == "") { ?>
		<th data-name="predecesora"><div id="elh_actividad_predecesora" class="actividad_predecesora"><div class="ewTableHeaderCaption"><?php echo $actividad->predecesora->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="predecesora"><div><div id="elh_actividad_predecesora" class="actividad_predecesora">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $actividad->predecesora->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($actividad->predecesora->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($actividad->predecesora->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($actividad->recurso->Visible) { // recurso ?>
	<?php if ($actividad->SortUrl($actividad->recurso) == "") { ?>
		<th data-name="recurso"><div id="elh_actividad_recurso" class="actividad_recurso"><div class="ewTableHeaderCaption"><?php echo $actividad->recurso->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="recurso"><div><div id="elh_actividad_recurso" class="actividad_recurso">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $actividad->recurso->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($actividad->recurso->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($actividad->recurso->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($actividad->estatus->Visible) { // estatus ?>
	<?php if ($actividad->SortUrl($actividad->estatus) == "") { ?>
		<th data-name="estatus"><div id="elh_actividad_estatus" class="actividad_estatus"><div class="ewTableHeaderCaption"><?php echo $actividad->estatus->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="estatus"><div><div id="elh_actividad_estatus" class="actividad_estatus">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $actividad->estatus->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($actividad->estatus->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($actividad->estatus->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($actividad->Resultado->Visible) { // Resultado ?>
	<?php if ($actividad->SortUrl($actividad->Resultado) == "") { ?>
		<th data-name="Resultado"><div id="elh_actividad_Resultado" class="actividad_Resultado"><div class="ewTableHeaderCaption"><?php echo $actividad->Resultado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Resultado"><div><div id="elh_actividad_Resultado" class="actividad_Resultado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $actividad->Resultado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($actividad->Resultado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($actividad->Resultado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$actividad_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$actividad_grid->StartRec = 1;
$actividad_grid->StopRec = $actividad_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($actividad_grid->FormKeyCountName) && ($actividad->CurrentAction == "gridadd" || $actividad->CurrentAction == "gridedit" || $actividad->CurrentAction == "F")) {
		$actividad_grid->KeyCount = $objForm->GetValue($actividad_grid->FormKeyCountName);
		$actividad_grid->StopRec = $actividad_grid->StartRec + $actividad_grid->KeyCount - 1;
	}
}
$actividad_grid->RecCnt = $actividad_grid->StartRec - 1;
if ($actividad_grid->Recordset && !$actividad_grid->Recordset->EOF) {
	$actividad_grid->Recordset->MoveFirst();
	$bSelectLimit = $actividad_grid->UseSelectLimit;
	if (!$bSelectLimit && $actividad_grid->StartRec > 1)
		$actividad_grid->Recordset->Move($actividad_grid->StartRec - 1);
} elseif (!$actividad->AllowAddDeleteRow && $actividad_grid->StopRec == 0) {
	$actividad_grid->StopRec = $actividad->GridAddRowCount;
}

// Initialize aggregate
$actividad->RowType = EW_ROWTYPE_AGGREGATEINIT;
$actividad->ResetAttrs();
$actividad_grid->RenderRow();
if ($actividad->CurrentAction == "gridadd")
	$actividad_grid->RowIndex = 0;
if ($actividad->CurrentAction == "gridedit")
	$actividad_grid->RowIndex = 0;
while ($actividad_grid->RecCnt < $actividad_grid->StopRec) {
	$actividad_grid->RecCnt++;
	if (intval($actividad_grid->RecCnt) >= intval($actividad_grid->StartRec)) {
		$actividad_grid->RowCnt++;
		if ($actividad->CurrentAction == "gridadd" || $actividad->CurrentAction == "gridedit" || $actividad->CurrentAction == "F") {
			$actividad_grid->RowIndex++;
			$objForm->Index = $actividad_grid->RowIndex;
			if ($objForm->HasValue($actividad_grid->FormActionName))
				$actividad_grid->RowAction = strval($objForm->GetValue($actividad_grid->FormActionName));
			elseif ($actividad->CurrentAction == "gridadd")
				$actividad_grid->RowAction = "insert";
			else
				$actividad_grid->RowAction = "";
		}

		// Set up key count
		$actividad_grid->KeyCount = $actividad_grid->RowIndex;

		// Init row class and style
		$actividad->ResetAttrs();
		$actividad->CssClass = "";
		if ($actividad->CurrentAction == "gridadd") {
			if ($actividad->CurrentMode == "copy") {
				$actividad_grid->LoadRowValues($actividad_grid->Recordset); // Load row values
				$actividad_grid->SetRecordKey($actividad_grid->RowOldKey, $actividad_grid->Recordset); // Set old record key
			} else {
				$actividad_grid->LoadDefaultValues(); // Load default values
				$actividad_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$actividad_grid->LoadRowValues($actividad_grid->Recordset); // Load row values
		}
		$actividad->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($actividad->CurrentAction == "gridadd") // Grid add
			$actividad->RowType = EW_ROWTYPE_ADD; // Render add
		if ($actividad->CurrentAction == "gridadd" && $actividad->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$actividad_grid->RestoreCurrentRowFormValues($actividad_grid->RowIndex); // Restore form values
		if ($actividad->CurrentAction == "gridedit") { // Grid edit
			if ($actividad->EventCancelled) {
				$actividad_grid->RestoreCurrentRowFormValues($actividad_grid->RowIndex); // Restore form values
			}
			if ($actividad_grid->RowAction == "insert")
				$actividad->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$actividad->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($actividad->CurrentAction == "gridedit" && ($actividad->RowType == EW_ROWTYPE_EDIT || $actividad->RowType == EW_ROWTYPE_ADD) && $actividad->EventCancelled) // Update failed
			$actividad_grid->RestoreCurrentRowFormValues($actividad_grid->RowIndex); // Restore form values
		if ($actividad->RowType == EW_ROWTYPE_EDIT) // Edit row
			$actividad_grid->EditRowCnt++;
		if ($actividad->CurrentAction == "F") // Confirm row
			$actividad_grid->RestoreCurrentRowFormValues($actividad_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$actividad->RowAttrs = array_merge($actividad->RowAttrs, array('data-rowindex'=>$actividad_grid->RowCnt, 'id'=>'r' . $actividad_grid->RowCnt . '_actividad', 'data-rowtype'=>$actividad->RowType));

		// Render row
		$actividad_grid->RenderRow();

		// Render list options
		$actividad_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($actividad_grid->RowAction <> "delete" && $actividad_grid->RowAction <> "insertdelete" && !($actividad_grid->RowAction == "insert" && $actividad->CurrentAction == "F" && $actividad_grid->EmptyRow())) {
?>
	<tr<?php echo $actividad->RowAttributes() ?>>
<?php

// Render list options (body, left)
$actividad_grid->ListOptions->Render("body", "left", $actividad_grid->RowCnt);
?>
	<?php if ($actividad->idActividad->Visible) { // idActividad ?>
		<td data-name="idActividad"<?php echo $actividad->idActividad->CellAttributes() ?>>
<?php if ($actividad->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="actividad" data-field="x_idActividad" name="o<?php echo $actividad_grid->RowIndex ?>_idActividad" id="o<?php echo $actividad_grid->RowIndex ?>_idActividad" value="<?php echo ew_HtmlEncode($actividad->idActividad->OldValue) ?>">
<?php } ?>
<?php if ($actividad->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $actividad_grid->RowCnt ?>_actividad_idActividad" class="form-group actividad_idActividad">
<span<?php echo $actividad->idActividad->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $actividad->idActividad->EditValue ?></p></span>
</span>
<input type="hidden" data-table="actividad" data-field="x_idActividad" name="x<?php echo $actividad_grid->RowIndex ?>_idActividad" id="x<?php echo $actividad_grid->RowIndex ?>_idActividad" value="<?php echo ew_HtmlEncode($actividad->idActividad->CurrentValue) ?>">
<?php } ?>
<?php if ($actividad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $actividad_grid->RowCnt ?>_actividad_idActividad" class="actividad_idActividad">
<span<?php echo $actividad->idActividad->ViewAttributes() ?>>
<?php echo $actividad->idActividad->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="actividad" data-field="x_idActividad" name="x<?php echo $actividad_grid->RowIndex ?>_idActividad" id="x<?php echo $actividad_grid->RowIndex ?>_idActividad" value="<?php echo ew_HtmlEncode($actividad->idActividad->FormValue) ?>">
<input type="hidden" data-table="actividad" data-field="x_idActividad" name="o<?php echo $actividad_grid->RowIndex ?>_idActividad" id="o<?php echo $actividad_grid->RowIndex ?>_idActividad" value="<?php echo ew_HtmlEncode($actividad->idActividad->OldValue) ?>">
<?php } ?>
<a id="<?php echo $actividad_grid->PageObjName . "_row_" . $actividad_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($actividad->avance->Visible) { // avance ?>
		<td data-name="avance"<?php echo $actividad->avance->CellAttributes() ?>>
<?php if ($actividad->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $actividad_grid->RowCnt ?>_actividad_avance" class="form-group actividad_avance">
<select data-table="actividad" data-field="x_avance" data-value-separator="<?php echo ew_HtmlEncode(is_array($actividad->avance->DisplayValueSeparator) ? json_encode($actividad->avance->DisplayValueSeparator) : $actividad->avance->DisplayValueSeparator) ?>" id="x<?php echo $actividad_grid->RowIndex ?>_avance" name="x<?php echo $actividad_grid->RowIndex ?>_avance"<?php echo $actividad->avance->EditAttributes() ?>>
<?php
if (is_array($actividad->avance->EditValue)) {
	$arwrk = $actividad->avance->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($actividad->avance->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $actividad->avance->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($actividad->avance->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($actividad->avance->CurrentValue) ?>" selected><?php echo $actividad->avance->CurrentValue ?></option>
<?php
    }
}
if (@$emptywrk) $actividad->avance->OldValue = "";
?>
</select>
<?php
$sSqlWrk = "SELECT `idAvance`, `cantidad` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `actividad_avance_porcentaje`";
$sWhereWrk = "";
$actividad->avance->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$actividad->avance->LookupFilters += array("f0" => "`idAvance` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$actividad->Lookup_Selecting($actividad->avance, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $actividad->avance->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x<?php echo $actividad_grid->RowIndex ?>_avance" id="s_x<?php echo $actividad_grid->RowIndex ?>_avance" value="<?php echo $actividad->avance->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="actividad" data-field="x_avance" name="o<?php echo $actividad_grid->RowIndex ?>_avance" id="o<?php echo $actividad_grid->RowIndex ?>_avance" value="<?php echo ew_HtmlEncode($actividad->avance->OldValue) ?>">
<?php } ?>
<?php if ($actividad->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $actividad_grid->RowCnt ?>_actividad_avance" class="form-group actividad_avance">
<select data-table="actividad" data-field="x_avance" data-value-separator="<?php echo ew_HtmlEncode(is_array($actividad->avance->DisplayValueSeparator) ? json_encode($actividad->avance->DisplayValueSeparator) : $actividad->avance->DisplayValueSeparator) ?>" id="x<?php echo $actividad_grid->RowIndex ?>_avance" name="x<?php echo $actividad_grid->RowIndex ?>_avance"<?php echo $actividad->avance->EditAttributes() ?>>
<?php
if (is_array($actividad->avance->EditValue)) {
	$arwrk = $actividad->avance->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($actividad->avance->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $actividad->avance->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($actividad->avance->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($actividad->avance->CurrentValue) ?>" selected><?php echo $actividad->avance->CurrentValue ?></option>
<?php
    }
}
if (@$emptywrk) $actividad->avance->OldValue = "";
?>
</select>
<?php
$sSqlWrk = "SELECT `idAvance`, `cantidad` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `actividad_avance_porcentaje`";
$sWhereWrk = "";
$actividad->avance->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$actividad->avance->LookupFilters += array("f0" => "`idAvance` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$actividad->Lookup_Selecting($actividad->avance, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $actividad->avance->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x<?php echo $actividad_grid->RowIndex ?>_avance" id="s_x<?php echo $actividad_grid->RowIndex ?>_avance" value="<?php echo $actividad->avance->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($actividad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $actividad_grid->RowCnt ?>_actividad_avance" class="actividad_avance">
<span<?php echo $actividad->avance->ViewAttributes() ?>>
<?php echo $actividad->avance->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="actividad" data-field="x_avance" name="x<?php echo $actividad_grid->RowIndex ?>_avance" id="x<?php echo $actividad_grid->RowIndex ?>_avance" value="<?php echo ew_HtmlEncode($actividad->avance->FormValue) ?>">
<input type="hidden" data-table="actividad" data-field="x_avance" name="o<?php echo $actividad_grid->RowIndex ?>_avance" id="o<?php echo $actividad_grid->RowIndex ?>_avance" value="<?php echo ew_HtmlEncode($actividad->avance->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($actividad->nombre->Visible) { // nombre ?>
		<td data-name="nombre"<?php echo $actividad->nombre->CellAttributes() ?>>
<?php if ($actividad->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $actividad_grid->RowCnt ?>_actividad_nombre" class="form-group actividad_nombre">
<input type="text" data-table="actividad" data-field="x_nombre" name="x<?php echo $actividad_grid->RowIndex ?>_nombre" id="x<?php echo $actividad_grid->RowIndex ?>_nombre" size="25" maxlength="128" placeholder="<?php echo ew_HtmlEncode($actividad->nombre->getPlaceHolder()) ?>" value="<?php echo $actividad->nombre->EditValue ?>"<?php echo $actividad->nombre->EditAttributes() ?>>
</span>
<input type="hidden" data-table="actividad" data-field="x_nombre" name="o<?php echo $actividad_grid->RowIndex ?>_nombre" id="o<?php echo $actividad_grid->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($actividad->nombre->OldValue) ?>">
<?php } ?>
<?php if ($actividad->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $actividad_grid->RowCnt ?>_actividad_nombre" class="form-group actividad_nombre">
<input type="text" data-table="actividad" data-field="x_nombre" name="x<?php echo $actividad_grid->RowIndex ?>_nombre" id="x<?php echo $actividad_grid->RowIndex ?>_nombre" size="25" maxlength="128" placeholder="<?php echo ew_HtmlEncode($actividad->nombre->getPlaceHolder()) ?>" value="<?php echo $actividad->nombre->EditValue ?>"<?php echo $actividad->nombre->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($actividad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $actividad_grid->RowCnt ?>_actividad_nombre" class="actividad_nombre">
<span<?php echo $actividad->nombre->ViewAttributes() ?>>
<?php echo $actividad->nombre->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="actividad" data-field="x_nombre" name="x<?php echo $actividad_grid->RowIndex ?>_nombre" id="x<?php echo $actividad_grid->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($actividad->nombre->FormValue) ?>">
<input type="hidden" data-table="actividad" data-field="x_nombre" name="o<?php echo $actividad_grid->RowIndex ?>_nombre" id="o<?php echo $actividad_grid->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($actividad->nombre->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($actividad->duracion->Visible) { // duracion ?>
		<td data-name="duracion"<?php echo $actividad->duracion->CellAttributes() ?>>
<?php if ($actividad->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $actividad_grid->RowCnt ?>_actividad_duracion" class="form-group actividad_duracion">
<input type="text" data-table="actividad" data-field="x_duracion" name="x<?php echo $actividad_grid->RowIndex ?>_duracion" id="x<?php echo $actividad_grid->RowIndex ?>_duracion" size="3" placeholder="<?php echo ew_HtmlEncode($actividad->duracion->getPlaceHolder()) ?>" value="<?php echo $actividad->duracion->EditValue ?>"<?php echo $actividad->duracion->EditAttributes() ?>>
</span>
<input type="hidden" data-table="actividad" data-field="x_duracion" name="o<?php echo $actividad_grid->RowIndex ?>_duracion" id="o<?php echo $actividad_grid->RowIndex ?>_duracion" value="<?php echo ew_HtmlEncode($actividad->duracion->OldValue) ?>">
<?php } ?>
<?php if ($actividad->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $actividad_grid->RowCnt ?>_actividad_duracion" class="form-group actividad_duracion">
<input type="text" data-table="actividad" data-field="x_duracion" name="x<?php echo $actividad_grid->RowIndex ?>_duracion" id="x<?php echo $actividad_grid->RowIndex ?>_duracion" size="3" placeholder="<?php echo ew_HtmlEncode($actividad->duracion->getPlaceHolder()) ?>" value="<?php echo $actividad->duracion->EditValue ?>"<?php echo $actividad->duracion->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($actividad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $actividad_grid->RowCnt ?>_actividad_duracion" class="actividad_duracion">
<span<?php echo $actividad->duracion->ViewAttributes() ?>>
<?php echo $actividad->duracion->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="actividad" data-field="x_duracion" name="x<?php echo $actividad_grid->RowIndex ?>_duracion" id="x<?php echo $actividad_grid->RowIndex ?>_duracion" value="<?php echo ew_HtmlEncode($actividad->duracion->FormValue) ?>">
<input type="hidden" data-table="actividad" data-field="x_duracion" name="o<?php echo $actividad_grid->RowIndex ?>_duracion" id="o<?php echo $actividad_grid->RowIndex ?>_duracion" value="<?php echo ew_HtmlEncode($actividad->duracion->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($actividad->tipoDuracion->Visible) { // tipoDuracion ?>
		<td data-name="tipoDuracion"<?php echo $actividad->tipoDuracion->CellAttributes() ?>>
<?php if ($actividad->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $actividad_grid->RowCnt ?>_actividad_tipoDuracion" class="form-group actividad_tipoDuracion">
<select data-table="actividad" data-field="x_tipoDuracion" data-value-separator="<?php echo ew_HtmlEncode(is_array($actividad->tipoDuracion->DisplayValueSeparator) ? json_encode($actividad->tipoDuracion->DisplayValueSeparator) : $actividad->tipoDuracion->DisplayValueSeparator) ?>" id="x<?php echo $actividad_grid->RowIndex ?>_tipoDuracion" name="x<?php echo $actividad_grid->RowIndex ?>_tipoDuracion"<?php echo $actividad->tipoDuracion->EditAttributes() ?>>
<?php
if (is_array($actividad->tipoDuracion->EditValue)) {
	$arwrk = $actividad->tipoDuracion->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($actividad->tipoDuracion->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $actividad->tipoDuracion->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($actividad->tipoDuracion->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($actividad->tipoDuracion->CurrentValue) ?>" selected><?php echo $actividad->tipoDuracion->CurrentValue ?></option>
<?php
    }
}
if (@$emptywrk) $actividad->tipoDuracion->OldValue = "";
?>
</select>
</span>
<input type="hidden" data-table="actividad" data-field="x_tipoDuracion" name="o<?php echo $actividad_grid->RowIndex ?>_tipoDuracion" id="o<?php echo $actividad_grid->RowIndex ?>_tipoDuracion" value="<?php echo ew_HtmlEncode($actividad->tipoDuracion->OldValue) ?>">
<?php } ?>
<?php if ($actividad->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $actividad_grid->RowCnt ?>_actividad_tipoDuracion" class="form-group actividad_tipoDuracion">
<select data-table="actividad" data-field="x_tipoDuracion" data-value-separator="<?php echo ew_HtmlEncode(is_array($actividad->tipoDuracion->DisplayValueSeparator) ? json_encode($actividad->tipoDuracion->DisplayValueSeparator) : $actividad->tipoDuracion->DisplayValueSeparator) ?>" id="x<?php echo $actividad_grid->RowIndex ?>_tipoDuracion" name="x<?php echo $actividad_grid->RowIndex ?>_tipoDuracion"<?php echo $actividad->tipoDuracion->EditAttributes() ?>>
<?php
if (is_array($actividad->tipoDuracion->EditValue)) {
	$arwrk = $actividad->tipoDuracion->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($actividad->tipoDuracion->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $actividad->tipoDuracion->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($actividad->tipoDuracion->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($actividad->tipoDuracion->CurrentValue) ?>" selected><?php echo $actividad->tipoDuracion->CurrentValue ?></option>
<?php
    }
}
if (@$emptywrk) $actividad->tipoDuracion->OldValue = "";
?>
</select>
</span>
<?php } ?>
<?php if ($actividad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $actividad_grid->RowCnt ?>_actividad_tipoDuracion" class="actividad_tipoDuracion">
<span<?php echo $actividad->tipoDuracion->ViewAttributes() ?>>
<?php echo $actividad->tipoDuracion->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="actividad" data-field="x_tipoDuracion" name="x<?php echo $actividad_grid->RowIndex ?>_tipoDuracion" id="x<?php echo $actividad_grid->RowIndex ?>_tipoDuracion" value="<?php echo ew_HtmlEncode($actividad->tipoDuracion->FormValue) ?>">
<input type="hidden" data-table="actividad" data-field="x_tipoDuracion" name="o<?php echo $actividad_grid->RowIndex ?>_tipoDuracion" id="o<?php echo $actividad_grid->RowIndex ?>_tipoDuracion" value="<?php echo ew_HtmlEncode($actividad->tipoDuracion->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($actividad->fechaInicio->Visible) { // fechaInicio ?>
		<td data-name="fechaInicio"<?php echo $actividad->fechaInicio->CellAttributes() ?>>
<?php if ($actividad->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $actividad_grid->RowCnt ?>_actividad_fechaInicio" class="form-group actividad_fechaInicio">
<input type="text" data-table="actividad" data-field="x_fechaInicio" data-format="7" name="x<?php echo $actividad_grid->RowIndex ?>_fechaInicio" id="x<?php echo $actividad_grid->RowIndex ?>_fechaInicio" size="30" placeholder="<?php echo ew_HtmlEncode($actividad->fechaInicio->getPlaceHolder()) ?>" value="<?php echo $actividad->fechaInicio->EditValue ?>"<?php echo $actividad->fechaInicio->EditAttributes() ?>>
<?php if (!$actividad->fechaInicio->ReadOnly && !$actividad->fechaInicio->Disabled && !isset($actividad->fechaInicio->EditAttrs["readonly"]) && !isset($actividad->fechaInicio->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("factividadgrid", "x<?php echo $actividad_grid->RowIndex ?>_fechaInicio", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<input type="hidden" data-table="actividad" data-field="x_fechaInicio" name="o<?php echo $actividad_grid->RowIndex ?>_fechaInicio" id="o<?php echo $actividad_grid->RowIndex ?>_fechaInicio" value="<?php echo ew_HtmlEncode($actividad->fechaInicio->OldValue) ?>">
<?php } ?>
<?php if ($actividad->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $actividad_grid->RowCnt ?>_actividad_fechaInicio" class="form-group actividad_fechaInicio">
<input type="text" data-table="actividad" data-field="x_fechaInicio" data-format="7" name="x<?php echo $actividad_grid->RowIndex ?>_fechaInicio" id="x<?php echo $actividad_grid->RowIndex ?>_fechaInicio" size="30" placeholder="<?php echo ew_HtmlEncode($actividad->fechaInicio->getPlaceHolder()) ?>" value="<?php echo $actividad->fechaInicio->EditValue ?>"<?php echo $actividad->fechaInicio->EditAttributes() ?>>
<?php if (!$actividad->fechaInicio->ReadOnly && !$actividad->fechaInicio->Disabled && !isset($actividad->fechaInicio->EditAttrs["readonly"]) && !isset($actividad->fechaInicio->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("factividadgrid", "x<?php echo $actividad_grid->RowIndex ?>_fechaInicio", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($actividad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $actividad_grid->RowCnt ?>_actividad_fechaInicio" class="actividad_fechaInicio">
<span<?php echo $actividad->fechaInicio->ViewAttributes() ?>>
<?php echo $actividad->fechaInicio->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="actividad" data-field="x_fechaInicio" name="x<?php echo $actividad_grid->RowIndex ?>_fechaInicio" id="x<?php echo $actividad_grid->RowIndex ?>_fechaInicio" value="<?php echo ew_HtmlEncode($actividad->fechaInicio->FormValue) ?>">
<input type="hidden" data-table="actividad" data-field="x_fechaInicio" name="o<?php echo $actividad_grid->RowIndex ?>_fechaInicio" id="o<?php echo $actividad_grid->RowIndex ?>_fechaInicio" value="<?php echo ew_HtmlEncode($actividad->fechaInicio->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($actividad->fechaFin->Visible) { // fechaFin ?>
		<td data-name="fechaFin"<?php echo $actividad->fechaFin->CellAttributes() ?>>
<?php if ($actividad->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $actividad_grid->RowCnt ?>_actividad_fechaFin" class="form-group actividad_fechaFin">
<input type="text" data-table="actividad" data-field="x_fechaFin" data-format="7" name="x<?php echo $actividad_grid->RowIndex ?>_fechaFin" id="x<?php echo $actividad_grid->RowIndex ?>_fechaFin" size="30" placeholder="<?php echo ew_HtmlEncode($actividad->fechaFin->getPlaceHolder()) ?>" value="<?php echo $actividad->fechaFin->EditValue ?>"<?php echo $actividad->fechaFin->EditAttributes() ?>>
<?php if (!$actividad->fechaFin->ReadOnly && !$actividad->fechaFin->Disabled && !isset($actividad->fechaFin->EditAttrs["readonly"]) && !isset($actividad->fechaFin->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("factividadgrid", "x<?php echo $actividad_grid->RowIndex ?>_fechaFin", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<input type="hidden" data-table="actividad" data-field="x_fechaFin" name="o<?php echo $actividad_grid->RowIndex ?>_fechaFin" id="o<?php echo $actividad_grid->RowIndex ?>_fechaFin" value="<?php echo ew_HtmlEncode($actividad->fechaFin->OldValue) ?>">
<?php } ?>
<?php if ($actividad->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $actividad_grid->RowCnt ?>_actividad_fechaFin" class="form-group actividad_fechaFin">
<input type="text" data-table="actividad" data-field="x_fechaFin" data-format="7" name="x<?php echo $actividad_grid->RowIndex ?>_fechaFin" id="x<?php echo $actividad_grid->RowIndex ?>_fechaFin" size="30" placeholder="<?php echo ew_HtmlEncode($actividad->fechaFin->getPlaceHolder()) ?>" value="<?php echo $actividad->fechaFin->EditValue ?>"<?php echo $actividad->fechaFin->EditAttributes() ?>>
<?php if (!$actividad->fechaFin->ReadOnly && !$actividad->fechaFin->Disabled && !isset($actividad->fechaFin->EditAttrs["readonly"]) && !isset($actividad->fechaFin->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("factividadgrid", "x<?php echo $actividad_grid->RowIndex ?>_fechaFin", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($actividad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $actividad_grid->RowCnt ?>_actividad_fechaFin" class="actividad_fechaFin">
<span<?php echo $actividad->fechaFin->ViewAttributes() ?>>
<?php echo $actividad->fechaFin->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="actividad" data-field="x_fechaFin" name="x<?php echo $actividad_grid->RowIndex ?>_fechaFin" id="x<?php echo $actividad_grid->RowIndex ?>_fechaFin" value="<?php echo ew_HtmlEncode($actividad->fechaFin->FormValue) ?>">
<input type="hidden" data-table="actividad" data-field="x_fechaFin" name="o<?php echo $actividad_grid->RowIndex ?>_fechaFin" id="o<?php echo $actividad_grid->RowIndex ?>_fechaFin" value="<?php echo ew_HtmlEncode($actividad->fechaFin->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($actividad->predecesora->Visible) { // predecesora ?>
		<td data-name="predecesora"<?php echo $actividad->predecesora->CellAttributes() ?>>
<?php if ($actividad->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $actividad_grid->RowCnt ?>_actividad_predecesora" class="form-group actividad_predecesora">
<input type="text" data-table="actividad" data-field="x_predecesora" name="x<?php echo $actividad_grid->RowIndex ?>_predecesora" id="x<?php echo $actividad_grid->RowIndex ?>_predecesora" size="5" maxlength="5" placeholder="<?php echo ew_HtmlEncode($actividad->predecesora->getPlaceHolder()) ?>" value="<?php echo $actividad->predecesora->EditValue ?>"<?php echo $actividad->predecesora->EditAttributes() ?>>
</span>
<input type="hidden" data-table="actividad" data-field="x_predecesora" name="o<?php echo $actividad_grid->RowIndex ?>_predecesora" id="o<?php echo $actividad_grid->RowIndex ?>_predecesora" value="<?php echo ew_HtmlEncode($actividad->predecesora->OldValue) ?>">
<?php } ?>
<?php if ($actividad->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $actividad_grid->RowCnt ?>_actividad_predecesora" class="form-group actividad_predecesora">
<input type="text" data-table="actividad" data-field="x_predecesora" name="x<?php echo $actividad_grid->RowIndex ?>_predecesora" id="x<?php echo $actividad_grid->RowIndex ?>_predecesora" size="5" maxlength="5" placeholder="<?php echo ew_HtmlEncode($actividad->predecesora->getPlaceHolder()) ?>" value="<?php echo $actividad->predecesora->EditValue ?>"<?php echo $actividad->predecesora->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($actividad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $actividad_grid->RowCnt ?>_actividad_predecesora" class="actividad_predecesora">
<span<?php echo $actividad->predecesora->ViewAttributes() ?>>
<?php echo $actividad->predecesora->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="actividad" data-field="x_predecesora" name="x<?php echo $actividad_grid->RowIndex ?>_predecesora" id="x<?php echo $actividad_grid->RowIndex ?>_predecesora" value="<?php echo ew_HtmlEncode($actividad->predecesora->FormValue) ?>">
<input type="hidden" data-table="actividad" data-field="x_predecesora" name="o<?php echo $actividad_grid->RowIndex ?>_predecesora" id="o<?php echo $actividad_grid->RowIndex ?>_predecesora" value="<?php echo ew_HtmlEncode($actividad->predecesora->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($actividad->recurso->Visible) { // recurso ?>
		<td data-name="recurso"<?php echo $actividad->recurso->CellAttributes() ?>>
<?php if ($actividad->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $actividad_grid->RowCnt ?>_actividad_recurso" class="form-group actividad_recurso">
<select data-table="actividad" data-field="x_recurso" data-value-separator="<?php echo ew_HtmlEncode(is_array($actividad->recurso->DisplayValueSeparator) ? json_encode($actividad->recurso->DisplayValueSeparator) : $actividad->recurso->DisplayValueSeparator) ?>" id="x<?php echo $actividad_grid->RowIndex ?>_recurso" name="x<?php echo $actividad_grid->RowIndex ?>_recurso"<?php echo $actividad->recurso->EditAttributes() ?>>
<?php
if (is_array($actividad->recurso->EditValue)) {
	$arwrk = $actividad->recurso->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($actividad->recurso->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $actividad->recurso->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($actividad->recurso->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($actividad->recurso->CurrentValue) ?>" selected><?php echo $actividad->recurso->CurrentValue ?></option>
<?php
    }
}
if (@$emptywrk) $actividad->recurso->OldValue = "";
?>
</select>
<?php
$sSqlWrk = "SELECT `idUsuario`, `nombres` AS `DispFld`, `apellidos` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `usuario`";
$sWhereWrk = "";
$lookuptblfilter = "`tipoUsuario` = 4";
ew_AddFilter($sWhereWrk, $lookuptblfilter);
if (!$GLOBALS["actividad"]->UserIDAllow("grid")) $sWhereWrk = $GLOBALS["usuario"]->AddUserIDFilter($sWhereWrk);
$actividad->recurso->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$actividad->recurso->LookupFilters += array("f0" => "`idUsuario` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$actividad->Lookup_Selecting($actividad->recurso, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $actividad->recurso->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x<?php echo $actividad_grid->RowIndex ?>_recurso" id="s_x<?php echo $actividad_grid->RowIndex ?>_recurso" value="<?php echo $actividad->recurso->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="actividad" data-field="x_recurso" name="o<?php echo $actividad_grid->RowIndex ?>_recurso" id="o<?php echo $actividad_grid->RowIndex ?>_recurso" value="<?php echo ew_HtmlEncode($actividad->recurso->OldValue) ?>">
<?php } ?>
<?php if ($actividad->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $actividad_grid->RowCnt ?>_actividad_recurso" class="form-group actividad_recurso">
<select data-table="actividad" data-field="x_recurso" data-value-separator="<?php echo ew_HtmlEncode(is_array($actividad->recurso->DisplayValueSeparator) ? json_encode($actividad->recurso->DisplayValueSeparator) : $actividad->recurso->DisplayValueSeparator) ?>" id="x<?php echo $actividad_grid->RowIndex ?>_recurso" name="x<?php echo $actividad_grid->RowIndex ?>_recurso"<?php echo $actividad->recurso->EditAttributes() ?>>
<?php
if (is_array($actividad->recurso->EditValue)) {
	$arwrk = $actividad->recurso->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($actividad->recurso->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $actividad->recurso->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($actividad->recurso->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($actividad->recurso->CurrentValue) ?>" selected><?php echo $actividad->recurso->CurrentValue ?></option>
<?php
    }
}
if (@$emptywrk) $actividad->recurso->OldValue = "";
?>
</select>
<?php
$sSqlWrk = "SELECT `idUsuario`, `nombres` AS `DispFld`, `apellidos` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `usuario`";
$sWhereWrk = "";
$lookuptblfilter = "`tipoUsuario` = 4";
ew_AddFilter($sWhereWrk, $lookuptblfilter);
if (!$GLOBALS["actividad"]->UserIDAllow("grid")) $sWhereWrk = $GLOBALS["usuario"]->AddUserIDFilter($sWhereWrk);
$actividad->recurso->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$actividad->recurso->LookupFilters += array("f0" => "`idUsuario` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$actividad->Lookup_Selecting($actividad->recurso, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $actividad->recurso->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x<?php echo $actividad_grid->RowIndex ?>_recurso" id="s_x<?php echo $actividad_grid->RowIndex ?>_recurso" value="<?php echo $actividad->recurso->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($actividad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $actividad_grid->RowCnt ?>_actividad_recurso" class="actividad_recurso">
<span<?php echo $actividad->recurso->ViewAttributes() ?>>
<?php echo $actividad->recurso->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="actividad" data-field="x_recurso" name="x<?php echo $actividad_grid->RowIndex ?>_recurso" id="x<?php echo $actividad_grid->RowIndex ?>_recurso" value="<?php echo ew_HtmlEncode($actividad->recurso->FormValue) ?>">
<input type="hidden" data-table="actividad" data-field="x_recurso" name="o<?php echo $actividad_grid->RowIndex ?>_recurso" id="o<?php echo $actividad_grid->RowIndex ?>_recurso" value="<?php echo ew_HtmlEncode($actividad->recurso->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($actividad->estatus->Visible) { // estatus ?>
		<td data-name="estatus"<?php echo $actividad->estatus->CellAttributes() ?>>
<?php if ($actividad->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $actividad_grid->RowCnt ?>_actividad_estatus" class="form-group actividad_estatus">
<select data-table="actividad" data-field="x_estatus" data-value-separator="<?php echo ew_HtmlEncode(is_array($actividad->estatus->DisplayValueSeparator) ? json_encode($actividad->estatus->DisplayValueSeparator) : $actividad->estatus->DisplayValueSeparator) ?>" id="x<?php echo $actividad_grid->RowIndex ?>_estatus" name="x<?php echo $actividad_grid->RowIndex ?>_estatus"<?php echo $actividad->estatus->EditAttributes() ?>>
<?php
if (is_array($actividad->estatus->EditValue)) {
	$arwrk = $actividad->estatus->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($actividad->estatus->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $actividad->estatus->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($actividad->estatus->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($actividad->estatus->CurrentValue) ?>" selected><?php echo $actividad->estatus->CurrentValue ?></option>
<?php
    }
}
if (@$emptywrk) $actividad->estatus->OldValue = "";
?>
</select>
</span>
<input type="hidden" data-table="actividad" data-field="x_estatus" name="o<?php echo $actividad_grid->RowIndex ?>_estatus" id="o<?php echo $actividad_grid->RowIndex ?>_estatus" value="<?php echo ew_HtmlEncode($actividad->estatus->OldValue) ?>">
<?php } ?>
<?php if ($actividad->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $actividad_grid->RowCnt ?>_actividad_estatus" class="form-group actividad_estatus">
<select data-table="actividad" data-field="x_estatus" data-value-separator="<?php echo ew_HtmlEncode(is_array($actividad->estatus->DisplayValueSeparator) ? json_encode($actividad->estatus->DisplayValueSeparator) : $actividad->estatus->DisplayValueSeparator) ?>" id="x<?php echo $actividad_grid->RowIndex ?>_estatus" name="x<?php echo $actividad_grid->RowIndex ?>_estatus"<?php echo $actividad->estatus->EditAttributes() ?>>
<?php
if (is_array($actividad->estatus->EditValue)) {
	$arwrk = $actividad->estatus->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($actividad->estatus->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $actividad->estatus->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($actividad->estatus->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($actividad->estatus->CurrentValue) ?>" selected><?php echo $actividad->estatus->CurrentValue ?></option>
<?php
    }
}
if (@$emptywrk) $actividad->estatus->OldValue = "";
?>
</select>
</span>
<?php } ?>
<?php if ($actividad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $actividad_grid->RowCnt ?>_actividad_estatus" class="actividad_estatus">
<span<?php echo $actividad->estatus->ViewAttributes() ?>>
<?php echo $actividad->estatus->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="actividad" data-field="x_estatus" name="x<?php echo $actividad_grid->RowIndex ?>_estatus" id="x<?php echo $actividad_grid->RowIndex ?>_estatus" value="<?php echo ew_HtmlEncode($actividad->estatus->FormValue) ?>">
<input type="hidden" data-table="actividad" data-field="x_estatus" name="o<?php echo $actividad_grid->RowIndex ?>_estatus" id="o<?php echo $actividad_grid->RowIndex ?>_estatus" value="<?php echo ew_HtmlEncode($actividad->estatus->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($actividad->Resultado->Visible) { // Resultado ?>
		<td data-name="Resultado"<?php echo $actividad->Resultado->CellAttributes() ?>>
<?php if ($actividad->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($actividad->Resultado->getSessionValue() <> "") { ?>
<span id="el<?php echo $actividad_grid->RowCnt ?>_actividad_Resultado" class="form-group actividad_Resultado">
<span<?php echo $actividad->Resultado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $actividad->Resultado->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $actividad_grid->RowIndex ?>_Resultado" name="x<?php echo $actividad_grid->RowIndex ?>_Resultado" value="<?php echo ew_HtmlEncode($actividad->Resultado->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $actividad_grid->RowCnt ?>_actividad_Resultado" class="form-group actividad_Resultado">
<select data-table="actividad" data-field="x_Resultado" data-value-separator="<?php echo ew_HtmlEncode(is_array($actividad->Resultado->DisplayValueSeparator) ? json_encode($actividad->Resultado->DisplayValueSeparator) : $actividad->Resultado->DisplayValueSeparator) ?>" id="x<?php echo $actividad_grid->RowIndex ?>_Resultado" name="x<?php echo $actividad_grid->RowIndex ?>_Resultado"<?php echo $actividad->Resultado->EditAttributes() ?>>
<?php
if (is_array($actividad->Resultado->EditValue)) {
	$arwrk = $actividad->Resultado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($actividad->Resultado->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $actividad->Resultado->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($actividad->Resultado->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($actividad->Resultado->CurrentValue) ?>" selected><?php echo $actividad->Resultado->CurrentValue ?></option>
<?php
    }
}
if (@$emptywrk) $actividad->Resultado->OldValue = "";
?>
</select>
<?php
$sSqlWrk = "SELECT `idResultado`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `resultado`";
$sWhereWrk = "";
$actividad->Resultado->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$actividad->Resultado->LookupFilters += array("f0" => "`idResultado` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$actividad->Lookup_Selecting($actividad->Resultado, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $actividad->Resultado->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x<?php echo $actividad_grid->RowIndex ?>_Resultado" id="s_x<?php echo $actividad_grid->RowIndex ?>_Resultado" value="<?php echo $actividad->Resultado->LookupFilterQuery() ?>">
</span>
<?php } ?>
<input type="hidden" data-table="actividad" data-field="x_Resultado" name="o<?php echo $actividad_grid->RowIndex ?>_Resultado" id="o<?php echo $actividad_grid->RowIndex ?>_Resultado" value="<?php echo ew_HtmlEncode($actividad->Resultado->OldValue) ?>">
<?php } ?>
<?php if ($actividad->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($actividad->Resultado->getSessionValue() <> "") { ?>
<span id="el<?php echo $actividad_grid->RowCnt ?>_actividad_Resultado" class="form-group actividad_Resultado">
<span<?php echo $actividad->Resultado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $actividad->Resultado->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $actividad_grid->RowIndex ?>_Resultado" name="x<?php echo $actividad_grid->RowIndex ?>_Resultado" value="<?php echo ew_HtmlEncode($actividad->Resultado->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $actividad_grid->RowCnt ?>_actividad_Resultado" class="form-group actividad_Resultado">
<select data-table="actividad" data-field="x_Resultado" data-value-separator="<?php echo ew_HtmlEncode(is_array($actividad->Resultado->DisplayValueSeparator) ? json_encode($actividad->Resultado->DisplayValueSeparator) : $actividad->Resultado->DisplayValueSeparator) ?>" id="x<?php echo $actividad_grid->RowIndex ?>_Resultado" name="x<?php echo $actividad_grid->RowIndex ?>_Resultado"<?php echo $actividad->Resultado->EditAttributes() ?>>
<?php
if (is_array($actividad->Resultado->EditValue)) {
	$arwrk = $actividad->Resultado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($actividad->Resultado->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $actividad->Resultado->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($actividad->Resultado->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($actividad->Resultado->CurrentValue) ?>" selected><?php echo $actividad->Resultado->CurrentValue ?></option>
<?php
    }
}
if (@$emptywrk) $actividad->Resultado->OldValue = "";
?>
</select>
<?php
$sSqlWrk = "SELECT `idResultado`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `resultado`";
$sWhereWrk = "";
$actividad->Resultado->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$actividad->Resultado->LookupFilters += array("f0" => "`idResultado` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$actividad->Lookup_Selecting($actividad->Resultado, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $actividad->Resultado->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x<?php echo $actividad_grid->RowIndex ?>_Resultado" id="s_x<?php echo $actividad_grid->RowIndex ?>_Resultado" value="<?php echo $actividad->Resultado->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php } ?>
<?php if ($actividad->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $actividad_grid->RowCnt ?>_actividad_Resultado" class="actividad_Resultado">
<span<?php echo $actividad->Resultado->ViewAttributes() ?>>
<?php echo $actividad->Resultado->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="actividad" data-field="x_Resultado" name="x<?php echo $actividad_grid->RowIndex ?>_Resultado" id="x<?php echo $actividad_grid->RowIndex ?>_Resultado" value="<?php echo ew_HtmlEncode($actividad->Resultado->FormValue) ?>">
<input type="hidden" data-table="actividad" data-field="x_Resultado" name="o<?php echo $actividad_grid->RowIndex ?>_Resultado" id="o<?php echo $actividad_grid->RowIndex ?>_Resultado" value="<?php echo ew_HtmlEncode($actividad->Resultado->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$actividad_grid->ListOptions->Render("body", "right", $actividad_grid->RowCnt);
?>
	</tr>
<?php if ($actividad->RowType == EW_ROWTYPE_ADD || $actividad->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
factividadgrid.UpdateOpts(<?php echo $actividad_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($actividad->CurrentAction <> "gridadd" || $actividad->CurrentMode == "copy")
		if (!$actividad_grid->Recordset->EOF) $actividad_grid->Recordset->MoveNext();
}
?>
<?php
	if ($actividad->CurrentMode == "add" || $actividad->CurrentMode == "copy" || $actividad->CurrentMode == "edit") {
		$actividad_grid->RowIndex = '$rowindex$';
		$actividad_grid->LoadDefaultValues();

		// Set row properties
		$actividad->ResetAttrs();
		$actividad->RowAttrs = array_merge($actividad->RowAttrs, array('data-rowindex'=>$actividad_grid->RowIndex, 'id'=>'r0_actividad', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($actividad->RowAttrs["class"], "ewTemplate");
		$actividad->RowType = EW_ROWTYPE_ADD;

		// Render row
		$actividad_grid->RenderRow();

		// Render list options
		$actividad_grid->RenderListOptions();
		$actividad_grid->StartRowCnt = 0;
?>
	<tr<?php echo $actividad->RowAttributes() ?>>
<?php

// Render list options (body, left)
$actividad_grid->ListOptions->Render("body", "left", $actividad_grid->RowIndex);
?>
	<?php if ($actividad->idActividad->Visible) { // idActividad ?>
		<td data-name="idActividad">
<?php if ($actividad->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_actividad_idActividad" class="form-group actividad_idActividad">
<span<?php echo $actividad->idActividad->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $actividad->idActividad->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="actividad" data-field="x_idActividad" name="x<?php echo $actividad_grid->RowIndex ?>_idActividad" id="x<?php echo $actividad_grid->RowIndex ?>_idActividad" value="<?php echo ew_HtmlEncode($actividad->idActividad->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="actividad" data-field="x_idActividad" name="o<?php echo $actividad_grid->RowIndex ?>_idActividad" id="o<?php echo $actividad_grid->RowIndex ?>_idActividad" value="<?php echo ew_HtmlEncode($actividad->idActividad->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($actividad->avance->Visible) { // avance ?>
		<td data-name="avance">
<?php if ($actividad->CurrentAction <> "F") { ?>
<span id="el$rowindex$_actividad_avance" class="form-group actividad_avance">
<select data-table="actividad" data-field="x_avance" data-value-separator="<?php echo ew_HtmlEncode(is_array($actividad->avance->DisplayValueSeparator) ? json_encode($actividad->avance->DisplayValueSeparator) : $actividad->avance->DisplayValueSeparator) ?>" id="x<?php echo $actividad_grid->RowIndex ?>_avance" name="x<?php echo $actividad_grid->RowIndex ?>_avance"<?php echo $actividad->avance->EditAttributes() ?>>
<?php
if (is_array($actividad->avance->EditValue)) {
	$arwrk = $actividad->avance->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($actividad->avance->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $actividad->avance->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($actividad->avance->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($actividad->avance->CurrentValue) ?>" selected><?php echo $actividad->avance->CurrentValue ?></option>
<?php
    }
}
if (@$emptywrk) $actividad->avance->OldValue = "";
?>
</select>
<?php
$sSqlWrk = "SELECT `idAvance`, `cantidad` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `actividad_avance_porcentaje`";
$sWhereWrk = "";
$actividad->avance->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$actividad->avance->LookupFilters += array("f0" => "`idAvance` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$actividad->Lookup_Selecting($actividad->avance, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $actividad->avance->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x<?php echo $actividad_grid->RowIndex ?>_avance" id="s_x<?php echo $actividad_grid->RowIndex ?>_avance" value="<?php echo $actividad->avance->LookupFilterQuery() ?>">
</span>
<?php } else { ?>
<span id="el$rowindex$_actividad_avance" class="form-group actividad_avance">
<span<?php echo $actividad->avance->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $actividad->avance->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="actividad" data-field="x_avance" name="x<?php echo $actividad_grid->RowIndex ?>_avance" id="x<?php echo $actividad_grid->RowIndex ?>_avance" value="<?php echo ew_HtmlEncode($actividad->avance->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="actividad" data-field="x_avance" name="o<?php echo $actividad_grid->RowIndex ?>_avance" id="o<?php echo $actividad_grid->RowIndex ?>_avance" value="<?php echo ew_HtmlEncode($actividad->avance->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($actividad->nombre->Visible) { // nombre ?>
		<td data-name="nombre">
<?php if ($actividad->CurrentAction <> "F") { ?>
<span id="el$rowindex$_actividad_nombre" class="form-group actividad_nombre">
<input type="text" data-table="actividad" data-field="x_nombre" name="x<?php echo $actividad_grid->RowIndex ?>_nombre" id="x<?php echo $actividad_grid->RowIndex ?>_nombre" size="25" maxlength="128" placeholder="<?php echo ew_HtmlEncode($actividad->nombre->getPlaceHolder()) ?>" value="<?php echo $actividad->nombre->EditValue ?>"<?php echo $actividad->nombre->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_actividad_nombre" class="form-group actividad_nombre">
<span<?php echo $actividad->nombre->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $actividad->nombre->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="actividad" data-field="x_nombre" name="x<?php echo $actividad_grid->RowIndex ?>_nombre" id="x<?php echo $actividad_grid->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($actividad->nombre->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="actividad" data-field="x_nombre" name="o<?php echo $actividad_grid->RowIndex ?>_nombre" id="o<?php echo $actividad_grid->RowIndex ?>_nombre" value="<?php echo ew_HtmlEncode($actividad->nombre->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($actividad->duracion->Visible) { // duracion ?>
		<td data-name="duracion">
<?php if ($actividad->CurrentAction <> "F") { ?>
<span id="el$rowindex$_actividad_duracion" class="form-group actividad_duracion">
<input type="text" data-table="actividad" data-field="x_duracion" name="x<?php echo $actividad_grid->RowIndex ?>_duracion" id="x<?php echo $actividad_grid->RowIndex ?>_duracion" size="3" placeholder="<?php echo ew_HtmlEncode($actividad->duracion->getPlaceHolder()) ?>" value="<?php echo $actividad->duracion->EditValue ?>"<?php echo $actividad->duracion->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_actividad_duracion" class="form-group actividad_duracion">
<span<?php echo $actividad->duracion->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $actividad->duracion->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="actividad" data-field="x_duracion" name="x<?php echo $actividad_grid->RowIndex ?>_duracion" id="x<?php echo $actividad_grid->RowIndex ?>_duracion" value="<?php echo ew_HtmlEncode($actividad->duracion->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="actividad" data-field="x_duracion" name="o<?php echo $actividad_grid->RowIndex ?>_duracion" id="o<?php echo $actividad_grid->RowIndex ?>_duracion" value="<?php echo ew_HtmlEncode($actividad->duracion->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($actividad->tipoDuracion->Visible) { // tipoDuracion ?>
		<td data-name="tipoDuracion">
<?php if ($actividad->CurrentAction <> "F") { ?>
<span id="el$rowindex$_actividad_tipoDuracion" class="form-group actividad_tipoDuracion">
<select data-table="actividad" data-field="x_tipoDuracion" data-value-separator="<?php echo ew_HtmlEncode(is_array($actividad->tipoDuracion->DisplayValueSeparator) ? json_encode($actividad->tipoDuracion->DisplayValueSeparator) : $actividad->tipoDuracion->DisplayValueSeparator) ?>" id="x<?php echo $actividad_grid->RowIndex ?>_tipoDuracion" name="x<?php echo $actividad_grid->RowIndex ?>_tipoDuracion"<?php echo $actividad->tipoDuracion->EditAttributes() ?>>
<?php
if (is_array($actividad->tipoDuracion->EditValue)) {
	$arwrk = $actividad->tipoDuracion->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($actividad->tipoDuracion->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $actividad->tipoDuracion->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($actividad->tipoDuracion->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($actividad->tipoDuracion->CurrentValue) ?>" selected><?php echo $actividad->tipoDuracion->CurrentValue ?></option>
<?php
    }
}
if (@$emptywrk) $actividad->tipoDuracion->OldValue = "";
?>
</select>
</span>
<?php } else { ?>
<span id="el$rowindex$_actividad_tipoDuracion" class="form-group actividad_tipoDuracion">
<span<?php echo $actividad->tipoDuracion->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $actividad->tipoDuracion->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="actividad" data-field="x_tipoDuracion" name="x<?php echo $actividad_grid->RowIndex ?>_tipoDuracion" id="x<?php echo $actividad_grid->RowIndex ?>_tipoDuracion" value="<?php echo ew_HtmlEncode($actividad->tipoDuracion->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="actividad" data-field="x_tipoDuracion" name="o<?php echo $actividad_grid->RowIndex ?>_tipoDuracion" id="o<?php echo $actividad_grid->RowIndex ?>_tipoDuracion" value="<?php echo ew_HtmlEncode($actividad->tipoDuracion->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($actividad->fechaInicio->Visible) { // fechaInicio ?>
		<td data-name="fechaInicio">
<?php if ($actividad->CurrentAction <> "F") { ?>
<span id="el$rowindex$_actividad_fechaInicio" class="form-group actividad_fechaInicio">
<input type="text" data-table="actividad" data-field="x_fechaInicio" data-format="7" name="x<?php echo $actividad_grid->RowIndex ?>_fechaInicio" id="x<?php echo $actividad_grid->RowIndex ?>_fechaInicio" size="30" placeholder="<?php echo ew_HtmlEncode($actividad->fechaInicio->getPlaceHolder()) ?>" value="<?php echo $actividad->fechaInicio->EditValue ?>"<?php echo $actividad->fechaInicio->EditAttributes() ?>>
<?php if (!$actividad->fechaInicio->ReadOnly && !$actividad->fechaInicio->Disabled && !isset($actividad->fechaInicio->EditAttrs["readonly"]) && !isset($actividad->fechaInicio->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("factividadgrid", "x<?php echo $actividad_grid->RowIndex ?>_fechaInicio", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_actividad_fechaInicio" class="form-group actividad_fechaInicio">
<span<?php echo $actividad->fechaInicio->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $actividad->fechaInicio->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="actividad" data-field="x_fechaInicio" name="x<?php echo $actividad_grid->RowIndex ?>_fechaInicio" id="x<?php echo $actividad_grid->RowIndex ?>_fechaInicio" value="<?php echo ew_HtmlEncode($actividad->fechaInicio->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="actividad" data-field="x_fechaInicio" name="o<?php echo $actividad_grid->RowIndex ?>_fechaInicio" id="o<?php echo $actividad_grid->RowIndex ?>_fechaInicio" value="<?php echo ew_HtmlEncode($actividad->fechaInicio->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($actividad->fechaFin->Visible) { // fechaFin ?>
		<td data-name="fechaFin">
<?php if ($actividad->CurrentAction <> "F") { ?>
<span id="el$rowindex$_actividad_fechaFin" class="form-group actividad_fechaFin">
<input type="text" data-table="actividad" data-field="x_fechaFin" data-format="7" name="x<?php echo $actividad_grid->RowIndex ?>_fechaFin" id="x<?php echo $actividad_grid->RowIndex ?>_fechaFin" size="30" placeholder="<?php echo ew_HtmlEncode($actividad->fechaFin->getPlaceHolder()) ?>" value="<?php echo $actividad->fechaFin->EditValue ?>"<?php echo $actividad->fechaFin->EditAttributes() ?>>
<?php if (!$actividad->fechaFin->ReadOnly && !$actividad->fechaFin->Disabled && !isset($actividad->fechaFin->EditAttrs["readonly"]) && !isset($actividad->fechaFin->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("factividadgrid", "x<?php echo $actividad_grid->RowIndex ?>_fechaFin", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_actividad_fechaFin" class="form-group actividad_fechaFin">
<span<?php echo $actividad->fechaFin->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $actividad->fechaFin->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="actividad" data-field="x_fechaFin" name="x<?php echo $actividad_grid->RowIndex ?>_fechaFin" id="x<?php echo $actividad_grid->RowIndex ?>_fechaFin" value="<?php echo ew_HtmlEncode($actividad->fechaFin->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="actividad" data-field="x_fechaFin" name="o<?php echo $actividad_grid->RowIndex ?>_fechaFin" id="o<?php echo $actividad_grid->RowIndex ?>_fechaFin" value="<?php echo ew_HtmlEncode($actividad->fechaFin->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($actividad->predecesora->Visible) { // predecesora ?>
		<td data-name="predecesora">
<?php if ($actividad->CurrentAction <> "F") { ?>
<span id="el$rowindex$_actividad_predecesora" class="form-group actividad_predecesora">
<input type="text" data-table="actividad" data-field="x_predecesora" name="x<?php echo $actividad_grid->RowIndex ?>_predecesora" id="x<?php echo $actividad_grid->RowIndex ?>_predecesora" size="5" maxlength="5" placeholder="<?php echo ew_HtmlEncode($actividad->predecesora->getPlaceHolder()) ?>" value="<?php echo $actividad->predecesora->EditValue ?>"<?php echo $actividad->predecesora->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_actividad_predecesora" class="form-group actividad_predecesora">
<span<?php echo $actividad->predecesora->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $actividad->predecesora->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="actividad" data-field="x_predecesora" name="x<?php echo $actividad_grid->RowIndex ?>_predecesora" id="x<?php echo $actividad_grid->RowIndex ?>_predecesora" value="<?php echo ew_HtmlEncode($actividad->predecesora->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="actividad" data-field="x_predecesora" name="o<?php echo $actividad_grid->RowIndex ?>_predecesora" id="o<?php echo $actividad_grid->RowIndex ?>_predecesora" value="<?php echo ew_HtmlEncode($actividad->predecesora->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($actividad->recurso->Visible) { // recurso ?>
		<td data-name="recurso">
<?php if ($actividad->CurrentAction <> "F") { ?>
<span id="el$rowindex$_actividad_recurso" class="form-group actividad_recurso">
<select data-table="actividad" data-field="x_recurso" data-value-separator="<?php echo ew_HtmlEncode(is_array($actividad->recurso->DisplayValueSeparator) ? json_encode($actividad->recurso->DisplayValueSeparator) : $actividad->recurso->DisplayValueSeparator) ?>" id="x<?php echo $actividad_grid->RowIndex ?>_recurso" name="x<?php echo $actividad_grid->RowIndex ?>_recurso"<?php echo $actividad->recurso->EditAttributes() ?>>
<?php
if (is_array($actividad->recurso->EditValue)) {
	$arwrk = $actividad->recurso->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($actividad->recurso->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $actividad->recurso->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($actividad->recurso->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($actividad->recurso->CurrentValue) ?>" selected><?php echo $actividad->recurso->CurrentValue ?></option>
<?php
    }
}
if (@$emptywrk) $actividad->recurso->OldValue = "";
?>
</select>
<?php
$sSqlWrk = "SELECT `idUsuario`, `nombres` AS `DispFld`, `apellidos` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `usuario`";
$sWhereWrk = "";
$lookuptblfilter = "`tipoUsuario` = 4";
ew_AddFilter($sWhereWrk, $lookuptblfilter);
if (!$GLOBALS["actividad"]->UserIDAllow("grid")) $sWhereWrk = $GLOBALS["usuario"]->AddUserIDFilter($sWhereWrk);
$actividad->recurso->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$actividad->recurso->LookupFilters += array("f0" => "`idUsuario` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$actividad->Lookup_Selecting($actividad->recurso, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $actividad->recurso->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x<?php echo $actividad_grid->RowIndex ?>_recurso" id="s_x<?php echo $actividad_grid->RowIndex ?>_recurso" value="<?php echo $actividad->recurso->LookupFilterQuery() ?>">
</span>
<?php } else { ?>
<span id="el$rowindex$_actividad_recurso" class="form-group actividad_recurso">
<span<?php echo $actividad->recurso->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $actividad->recurso->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="actividad" data-field="x_recurso" name="x<?php echo $actividad_grid->RowIndex ?>_recurso" id="x<?php echo $actividad_grid->RowIndex ?>_recurso" value="<?php echo ew_HtmlEncode($actividad->recurso->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="actividad" data-field="x_recurso" name="o<?php echo $actividad_grid->RowIndex ?>_recurso" id="o<?php echo $actividad_grid->RowIndex ?>_recurso" value="<?php echo ew_HtmlEncode($actividad->recurso->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($actividad->estatus->Visible) { // estatus ?>
		<td data-name="estatus">
<?php if ($actividad->CurrentAction <> "F") { ?>
<span id="el$rowindex$_actividad_estatus" class="form-group actividad_estatus">
<select data-table="actividad" data-field="x_estatus" data-value-separator="<?php echo ew_HtmlEncode(is_array($actividad->estatus->DisplayValueSeparator) ? json_encode($actividad->estatus->DisplayValueSeparator) : $actividad->estatus->DisplayValueSeparator) ?>" id="x<?php echo $actividad_grid->RowIndex ?>_estatus" name="x<?php echo $actividad_grid->RowIndex ?>_estatus"<?php echo $actividad->estatus->EditAttributes() ?>>
<?php
if (is_array($actividad->estatus->EditValue)) {
	$arwrk = $actividad->estatus->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($actividad->estatus->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $actividad->estatus->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($actividad->estatus->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($actividad->estatus->CurrentValue) ?>" selected><?php echo $actividad->estatus->CurrentValue ?></option>
<?php
    }
}
if (@$emptywrk) $actividad->estatus->OldValue = "";
?>
</select>
</span>
<?php } else { ?>
<span id="el$rowindex$_actividad_estatus" class="form-group actividad_estatus">
<span<?php echo $actividad->estatus->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $actividad->estatus->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="actividad" data-field="x_estatus" name="x<?php echo $actividad_grid->RowIndex ?>_estatus" id="x<?php echo $actividad_grid->RowIndex ?>_estatus" value="<?php echo ew_HtmlEncode($actividad->estatus->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="actividad" data-field="x_estatus" name="o<?php echo $actividad_grid->RowIndex ?>_estatus" id="o<?php echo $actividad_grid->RowIndex ?>_estatus" value="<?php echo ew_HtmlEncode($actividad->estatus->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($actividad->Resultado->Visible) { // Resultado ?>
		<td data-name="Resultado">
<?php if ($actividad->CurrentAction <> "F") { ?>
<?php if ($actividad->Resultado->getSessionValue() <> "") { ?>
<span id="el$rowindex$_actividad_Resultado" class="form-group actividad_Resultado">
<span<?php echo $actividad->Resultado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $actividad->Resultado->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $actividad_grid->RowIndex ?>_Resultado" name="x<?php echo $actividad_grid->RowIndex ?>_Resultado" value="<?php echo ew_HtmlEncode($actividad->Resultado->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_actividad_Resultado" class="form-group actividad_Resultado">
<select data-table="actividad" data-field="x_Resultado" data-value-separator="<?php echo ew_HtmlEncode(is_array($actividad->Resultado->DisplayValueSeparator) ? json_encode($actividad->Resultado->DisplayValueSeparator) : $actividad->Resultado->DisplayValueSeparator) ?>" id="x<?php echo $actividad_grid->RowIndex ?>_Resultado" name="x<?php echo $actividad_grid->RowIndex ?>_Resultado"<?php echo $actividad->Resultado->EditAttributes() ?>>
<?php
if (is_array($actividad->Resultado->EditValue)) {
	$arwrk = $actividad->Resultado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($actividad->Resultado->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $actividad->Resultado->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($actividad->Resultado->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($actividad->Resultado->CurrentValue) ?>" selected><?php echo $actividad->Resultado->CurrentValue ?></option>
<?php
    }
}
if (@$emptywrk) $actividad->Resultado->OldValue = "";
?>
</select>
<?php
$sSqlWrk = "SELECT `idResultado`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `resultado`";
$sWhereWrk = "";
$actividad->Resultado->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$actividad->Resultado->LookupFilters += array("f0" => "`idResultado` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$actividad->Lookup_Selecting($actividad->Resultado, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $actividad->Resultado->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x<?php echo $actividad_grid->RowIndex ?>_Resultado" id="s_x<?php echo $actividad_grid->RowIndex ?>_Resultado" value="<?php echo $actividad->Resultado->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_actividad_Resultado" class="form-group actividad_Resultado">
<span<?php echo $actividad->Resultado->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $actividad->Resultado->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="actividad" data-field="x_Resultado" name="x<?php echo $actividad_grid->RowIndex ?>_Resultado" id="x<?php echo $actividad_grid->RowIndex ?>_Resultado" value="<?php echo ew_HtmlEncode($actividad->Resultado->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="actividad" data-field="x_Resultado" name="o<?php echo $actividad_grid->RowIndex ?>_Resultado" id="o<?php echo $actividad_grid->RowIndex ?>_Resultado" value="<?php echo ew_HtmlEncode($actividad->Resultado->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$actividad_grid->ListOptions->Render("body", "right", $actividad_grid->RowCnt);
?>
<script type="text/javascript">
factividadgrid.UpdateOpts(<?php echo $actividad_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($actividad->CurrentMode == "add" || $actividad->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $actividad_grid->FormKeyCountName ?>" id="<?php echo $actividad_grid->FormKeyCountName ?>" value="<?php echo $actividad_grid->KeyCount ?>">
<?php echo $actividad_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($actividad->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $actividad_grid->FormKeyCountName ?>" id="<?php echo $actividad_grid->FormKeyCountName ?>" value="<?php echo $actividad_grid->KeyCount ?>">
<?php echo $actividad_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($actividad->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="factividadgrid">
</div>
<?php

// Close recordset
if ($actividad_grid->Recordset)
	$actividad_grid->Recordset->Close();
?>
<?php if ($actividad_grid->ShowOtherOptions) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php
	foreach ($actividad_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($actividad_grid->TotalRecs == 0 && $actividad->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($actividad_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($actividad->Export == "") { ?>
<script type="text/javascript">
factividadgrid.Init();
</script>
<?php } ?>
<?php
$actividad_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$actividad_grid->Page_Terminate();
?>
