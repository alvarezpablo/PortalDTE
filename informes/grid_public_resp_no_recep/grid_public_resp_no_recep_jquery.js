function grid_public_resp_no_recep_rut_responde_getValue(seqRow) {
  seqRow = scSeqNormalize(seqRow);
  return $("#id_sc_field_rut_responde" + seqRow).html();
}

function grid_public_resp_no_recep_rut_responde_setValue(newValue, seqRow) {
  seqRow = scSeqNormalize(seqRow);
  $("#id_sc_field_rut_responde" + seqRow).html(newValue);
}

function grid_public_resp_no_recep_nom_contacto_getValue(seqRow) {
  seqRow = scSeqNormalize(seqRow);
  return $("#id_sc_field_nom_contacto" + seqRow).html();
}

function grid_public_resp_no_recep_nom_contacto_setValue(newValue, seqRow) {
  seqRow = scSeqNormalize(seqRow);
  $("#id_sc_field_nom_contacto" + seqRow).html(newValue);
}

function grid_public_resp_no_recep_tipo_docu_getValue(seqRow) {
  seqRow = scSeqNormalize(seqRow);
  return $("#id_sc_field_tipo_docu" + seqRow).html();
}

function grid_public_resp_no_recep_tipo_docu_setValue(newValue, seqRow) {
  seqRow = scSeqNormalize(seqRow);
  $("#id_sc_field_tipo_docu" + seqRow).html(newValue);
}

function grid_public_resp_no_recep_folio_dte_getValue(seqRow) {
  seqRow = scSeqNormalize(seqRow);
  return $("#id_sc_field_folio_dte" + seqRow).html();
}

function grid_public_resp_no_recep_folio_dte_setValue(newValue, seqRow) {
  seqRow = scSeqNormalize(seqRow);
  $("#id_sc_field_folio_dte" + seqRow).html(newValue);
}

function grid_public_resp_no_recep_fec_emision_getValue(seqRow) {
  seqRow = scSeqNormalize(seqRow);
  return $("#id_sc_field_fec_emision" + seqRow).html();
}

function grid_public_resp_no_recep_fec_emision_setValue(newValue, seqRow) {
  seqRow = scSeqNormalize(seqRow);
  $("#id_sc_field_fec_emision" + seqRow).html(newValue);
}

function grid_public_resp_no_recep_mensaje_getValue(seqRow) {
  seqRow = scSeqNormalize(seqRow);
  return $("#id_sc_field_mensaje" + seqRow).html();
}

function grid_public_resp_no_recep_mensaje_setValue(newValue, seqRow) {
  seqRow = scSeqNormalize(seqRow);
  $("#id_sc_field_mensaje" + seqRow).html(newValue);
}

function grid_public_resp_no_recep_motivo_rechazo_getValue(seqRow) {
  seqRow = scSeqNormalize(seqRow);
  return $("#id_sc_field_motivo_rechazo" + seqRow).html();
}

function grid_public_resp_no_recep_motivo_rechazo_setValue(newValue, seqRow) {
  seqRow = scSeqNormalize(seqRow);
  $("#id_sc_field_motivo_rechazo" + seqRow).html(newValue);
}

function grid_public_resp_no_recep_pdf_getValue(seqRow) {
  seqRow = scSeqNormalize(seqRow);
  return $("#id_sc_field_pdf" + seqRow).find("img").attr("src");
}

function grid_public_resp_no_recep_pdf_setValue(newValue, seqRow) {
  seqRow = scSeqNormalize(seqRow);
  $("#id_sc_field_pdf" + seqRow).find("img").attr("src", newValue);
}

function grid_public_resp_no_recep_getValue(field, seqRow) {
  seqRow = scSeqNormalize(seqRow);
  if ("rut_responde" == field) {
    return grid_public_resp_no_recep_rut_responde_getValue(seqRow);
  }
  if ("nom_contacto" == field) {
    return grid_public_resp_no_recep_nom_contacto_getValue(seqRow);
  }
  if ("tipo_docu" == field) {
    return grid_public_resp_no_recep_tipo_docu_getValue(seqRow);
  }
  if ("folio_dte" == field) {
    return grid_public_resp_no_recep_folio_dte_getValue(seqRow);
  }
  if ("fec_emision" == field) {
    return grid_public_resp_no_recep_fec_emision_getValue(seqRow);
  }
  if ("mensaje" == field) {
    return grid_public_resp_no_recep_mensaje_getValue(seqRow);
  }
  if ("motivo_rechazo" == field) {
    return grid_public_resp_no_recep_motivo_rechazo_getValue(seqRow);
  }
  if ("pdf" == field) {
    return grid_public_resp_no_recep_pdf_getValue(seqRow);
  }
}

function grid_public_resp_no_recep_setValue(field, newValue, seqRow) {
  seqRow = scSeqNormalize(seqRow);
  if ("rut_responde" == field) {
    grid_public_resp_no_recep_rut_responde_setValue(newValue, seqRow);
  }
  if ("nom_contacto" == field) {
    grid_public_resp_no_recep_nom_contacto_setValue(newValue, seqRow);
  }
  if ("tipo_docu" == field) {
    grid_public_resp_no_recep_tipo_docu_setValue(newValue, seqRow);
  }
  if ("folio_dte" == field) {
    grid_public_resp_no_recep_folio_dte_setValue(newValue, seqRow);
  }
  if ("fec_emision" == field) {
    grid_public_resp_no_recep_fec_emision_setValue(newValue, seqRow);
  }
  if ("mensaje" == field) {
    grid_public_resp_no_recep_mensaje_setValue(newValue, seqRow);
  }
  if ("motivo_rechazo" == field) {
    grid_public_resp_no_recep_motivo_rechazo_setValue(newValue, seqRow);
  }
  if ("pdf" == field) {
    grid_public_resp_no_recep_pdf_setValue(newValue, seqRow);
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
      url: "grid_public_resp_no_recep.php",
      data: "nmgp_opcao=ajax_navigate&script_case_init=" + document.F4.script_case_init.value + "&script_case_session=" + document.F4.script_case_session.value + "&opc=" + opc  + "&parm=" + parm,
      success: function(jsonNavigate) {
        var i, oResp;
        eval("oResp = " + jsonNavigate);
        document.getElementById('nmsc_iframe_liga_A_grid_public_resp_no_recep').src = 'NM_Blank_Page.htm';
        document.getElementById('nmsc_iframe_liga_E_grid_public_resp_no_recep').src = 'NM_Blank_Page.htm';
        document.getElementById('nmsc_iframe_liga_D_grid_public_resp_no_recep').src = 'NM_Blank_Page.htm';
        document.getElementById('nmsc_iframe_liga_B_grid_public_resp_no_recep').src = 'NM_Blank_Page.htm';
        document.getElementById('nmsc_iframe_liga_A_grid_public_resp_no_recep').style.height = '0px';
        document.getElementById('nmsc_iframe_liga_E_grid_public_resp_no_recep').style.height = '0px';
        document.getElementById('nmsc_iframe_liga_D_grid_public_resp_no_recep').style.height = '0px';
        document.getElementById('nmsc_iframe_liga_B_grid_public_resp_no_recep').style.height = '0px';
        document.getElementById('nmsc_iframe_liga_A_grid_public_resp_no_recep').style.width  = '0px';
        document.getElementById('nmsc_iframe_liga_E_grid_public_resp_no_recep').style.width  = '0px';
        document.getElementById('nmsc_iframe_liga_D_grid_public_resp_no_recep').style.width  = '0px';
        document.getElementById('nmsc_iframe_liga_B_grid_public_resp_no_recep').style.width  = '0px';
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
            SC_init_jquery();
            tb_init('a.thickbox, area.thickbox, input.thickbox');
        }
        scAjaxProcOff();
      }
    });
}
