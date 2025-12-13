function grid_dte_recep_sii_tipo_dte_getValue(seqRow) {
  seqRow = scSeqNormalize(seqRow);
  return $("#id_sc_field_tipo_dte" + seqRow).html();
}

function grid_dte_recep_sii_tipo_dte_setValue(newValue, seqRow) {
  seqRow = scSeqNormalize(seqRow);
  $("#id_sc_field_tipo_dte" + seqRow).html(newValue);
}

function grid_dte_recep_sii_folio_dte_getValue(seqRow) {
  seqRow = scSeqNormalize(seqRow);
  return $("#id_sc_field_folio_dte" + seqRow).html();
}

function grid_dte_recep_sii_folio_dte_setValue(newValue, seqRow) {
  seqRow = scSeqNormalize(seqRow);
  $("#id_sc_field_folio_dte" + seqRow).html(newValue);
}

function grid_dte_recep_sii_fecha_emision_getValue(seqRow) {
  seqRow = scSeqNormalize(seqRow);
  return $("#id_sc_field_fecha_emision" + seqRow).html();
}

function grid_dte_recep_sii_fecha_emision_setValue(newValue, seqRow) {
  seqRow = scSeqNormalize(seqRow);
  $("#id_sc_field_fecha_emision" + seqRow).html(newValue);
}

function grid_dte_recep_sii_rut_emisor_getValue(seqRow) {
  seqRow = scSeqNormalize(seqRow);
  return $("#id_sc_field_rut_emisor" + seqRow).html();
}

function grid_dte_recep_sii_rut_emisor_setValue(newValue, seqRow) {
  seqRow = scSeqNormalize(seqRow);
  $("#id_sc_field_rut_emisor" + seqRow).html(newValue);
}

function grid_dte_recep_sii_razon_social_getValue(seqRow) {
  seqRow = scSeqNormalize(seqRow);
  return $("#id_sc_field_razon_social" + seqRow).html();
}

function grid_dte_recep_sii_razon_social_setValue(newValue, seqRow) {
  seqRow = scSeqNormalize(seqRow);
  $("#id_sc_field_razon_social" + seqRow).html(newValue);
}

function grid_dte_recep_sii_monto_total_getValue(seqRow) {
  seqRow = scSeqNormalize(seqRow);
  return $("#id_sc_field_monto_total" + seqRow).html();
}

function grid_dte_recep_sii_monto_total_setValue(newValue, seqRow) {
  seqRow = scSeqNormalize(seqRow);
  $("#id_sc_field_monto_total" + seqRow).html(newValue);
}

function grid_dte_recep_sii_fecha_hora_recepcion_getValue(seqRow) {
  seqRow = scSeqNormalize(seqRow);
  return $("#id_sc_field_fecha_hora_recepcion" + seqRow).html();
}

function grid_dte_recep_sii_fecha_hora_recepcion_setValue(newValue, seqRow) {
  seqRow = scSeqNormalize(seqRow);
  $("#id_sc_field_fecha_hora_recepcion" + seqRow).html(newValue);
}

function grid_dte_recep_sii_trackid_getValue(seqRow) {
  seqRow = scSeqNormalize(seqRow);
  return $("#id_sc_field_trackid" + seqRow).html();
}

function grid_dte_recep_sii_trackid_setValue(newValue, seqRow) {
  seqRow = scSeqNormalize(seqRow);
  $("#id_sc_field_trackid" + seqRow).html(newValue);
}

function grid_dte_recep_sii_getValue(field, seqRow) {
  seqRow = scSeqNormalize(seqRow);
  if ("tipo_dte" == field) {
    return grid_dte_recep_sii_tipo_dte_getValue(seqRow);
  }
  if ("folio_dte" == field) {
    return grid_dte_recep_sii_folio_dte_getValue(seqRow);
  }
  if ("fecha_emision" == field) {
    return grid_dte_recep_sii_fecha_emision_getValue(seqRow);
  }
  if ("rut_emisor" == field) {
    return grid_dte_recep_sii_rut_emisor_getValue(seqRow);
  }
  if ("razon_social" == field) {
    return grid_dte_recep_sii_razon_social_getValue(seqRow);
  }
  if ("monto_total" == field) {
    return grid_dte_recep_sii_monto_total_getValue(seqRow);
  }
  if ("fecha_hora_recepcion" == field) {
    return grid_dte_recep_sii_fecha_hora_recepcion_getValue(seqRow);
  }
  if ("trackid" == field) {
    return grid_dte_recep_sii_trackid_getValue(seqRow);
  }
}

function grid_dte_recep_sii_setValue(field, newValue, seqRow) {
  seqRow = scSeqNormalize(seqRow);
  if ("tipo_dte" == field) {
    grid_dte_recep_sii_tipo_dte_setValue(newValue, seqRow);
  }
  if ("folio_dte" == field) {
    grid_dte_recep_sii_folio_dte_setValue(newValue, seqRow);
  }
  if ("fecha_emision" == field) {
    grid_dte_recep_sii_fecha_emision_setValue(newValue, seqRow);
  }
  if ("rut_emisor" == field) {
    grid_dte_recep_sii_rut_emisor_setValue(newValue, seqRow);
  }
  if ("razon_social" == field) {
    grid_dte_recep_sii_razon_social_setValue(newValue, seqRow);
  }
  if ("monto_total" == field) {
    grid_dte_recep_sii_monto_total_setValue(newValue, seqRow);
  }
  if ("fecha_hora_recepcion" == field) {
    grid_dte_recep_sii_fecha_hora_recepcion_setValue(newValue, seqRow);
  }
  if ("trackid" == field) {
    grid_dte_recep_sii_trackid_setValue(newValue, seqRow);
  }
}

function scJQAddEvents(seqRow) {
  seqRow = scSeqNormalize(seqRow);
}

function scSeqNormalize(seqRow) {
  var newSeqRow = seqRow.toString();
  if ("" == newSeqRow) {
    return "";
  }
  if ("_" != newSeqRow.substr(0, 1)) {
    return "_" + newSeqRow;
  }
  return newSeqRow;
}
function ajax_navigate(opc, parm)
{
    scAjaxProcOn();
    $.ajax({
      type: "POST",
      url: "grid_dte_recep_sii.php",
      data: "nmgp_opcao=ajax_navigate&script_case_init=" + document.F4.script_case_init.value + "&script_case_session=" + document.F4.script_case_session.value + "&opc=" + opc  + "&parm=" + parm,
      success: function(jsonNavigate) {
        var i, oResp;
        eval("oResp = " + jsonNavigate);
        if (oResp["redirInfo"]) {
           scAjaxRedir(oResp);
        }
        if (oResp["setValue"]) {
          for (i = 0; i < oResp["setValue"].length; i++) {
               $("#" + oResp["setValue"][i]["field"]).html(oResp["setValue"][i]["value"]);
          }
        }
        if (oResp["setVar"]) {
          for (i = 0; i < oResp["setVar"].length; i++) {
               eval (oResp["setVar"][i]["var"] + ' = \"' + oResp["setVar"][i]["value"] + '\"');
          }
        }
        if (oResp["setDisplay"]) {
          for (i = 0; i < oResp["setDisplay"].length; i++) {
               document.getElementById(oResp["setDisplay"][i]["field"]).style.display = oResp["setDisplay"][i]["value"];
          }
        }
        if (oResp["setDisabled"]) {
          for (i = 0; i < oResp["setDisabled"].length; i++) {
               document.getElementById(oResp["setDisabled"][i]["field"]).disabled = oResp["setDisabled"][i]["value"];
          }
        }
        if (oResp["setClass"]) {
          for (i = 0; i < oResp["setClass"].length; i++) {
               document.getElementById(oResp["setClass"][i]["field"]).className = oResp["setClass"][i]["value"];
          }
        }
        if (oResp["setSrc"]) {
          for (i = 0; i < oResp["setSrc"].length; i++) {
               document.getElementById(oResp["setSrc"][i]["field"]).src = oResp["setSrc"][i]["value"];
          }
        }
        if (oResp["redirInfo"]) {
           scAjaxRedir(oResp);
        }
        if (oResp["htmOutput"]) {
           scAjaxShowDebug(oResp);
        }
        if (!SC_Link_View)
        {
            if (Qsearch_ok)
            {
                scQSInitVal = $("#SC_fast_search_top").val();
                scQSInit = true;
                scQuickSearchInit(false, '');
                $('#SC_fast_search_top').listen();
                scQuickSearchKeyUp('top', null);
                scQSInit = false;
            }
            SC_init_jquery();
            tb_init('a.thickbox, area.thickbox, input.thickbox');
        }
        scAjaxProcOff();
      }
    });
}
