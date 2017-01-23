/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */


$("#table_cmd").sortable({
    axis: "y",
    cursor: "move",
    items: ".cmd",
    placeholder: "ui-state-highlight",
    tolerance: "intersect",
    forcePlaceholderSize: true
});

// Charge les infos de l'utilisateur et la liste des "Listes Wunderlist"
$('.eqLogicAttr[data-l1key=id]').on('change', function() {
    // Masque les infos
    $("#userDetails").hide(50);
    //Appel en Ajax la fonction pour récupérer les infos stockées en DB
    $.ajax({
        type: 'POST',
        url: 'plugins/jeeWunderlist/core/ajax/jeeWunderlist.ajax.php',
        data: {
            action: 'getUserDetails',
            id: $(".li_eqLogic.active").attr('data-eqLogic_id')
        },
        dataType: 'json',
        error: function(request, status, error) {
            handleAjaxError(request, status, error, $('#div_myhomeShowDebug'));
        },
        success: function(data) {
            console.log(data);
            if (data.state == 'ok') {
                $("#userAvatar").attr('src', data.result.userAvatar);
                $("#userName").text(data.result.userDetails.name);
                $("#userEmail").text(data.result.userDetails.email);
                $('#userLists').empty();
                $.each(data.result.userLists, function(i, item) {
                     $('#userLists')
                         .append($("<option></option>")
                         .attr("value",item.id)
                         .text(item.title));
                });
                console.log(data.result.listId);
                // On sélectionne la liste suavegardée
                if (data.result.listId != '')
                {
                  $('#userLists option[value=' + data.result.listId + ']').attr('selected','selected');
                }
                $("#userDetails").show(100);
            }
        }
    });

});

/*
 * Fonction pour l'ajout de commande, appellé automatiquement par plugin.template
 */
function addCmdToTable(_cmd) {


    if (!isset(_cmd)) {
        var _cmd = {
            configuration: {}
        };
    }
    if (!isset(_cmd.configuration)) {
        _cmd.configuration = {};
    }
    var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
    tr += '<td>';
    tr += '<span class="cmdAttr" data-l1key="id" style="display:none;"></span>';
    tr += '<input class="cmdAttr form-control input-sm" data-l1key="name" style="width : 140px;" placeholder="{{Nom}}">';
    tr += '</td>';
    // tr += '<td>';
    // tr += '<span class="type" type="' + init(_cmd.type) + '">' + jeedom.cmd.availableType() + '</span>';
    // tr += '<span class="subType" subType="' + init(_cmd.subType) + '"></span>';
    // tr += '</td>';
    tr += '<td>';
    if (is_numeric(_cmd.id)) {
        tr += '<a class="btn btn-default btn-xs cmdAction expertModeVisible" data-action="configure"><i class="fa fa-cogs"></i></a> ';
        tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fa fa-rss"></i> {{Tester}}</a>';
    }
    //tr += '<i class="fa fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i>';
    tr += '</td>';
    tr += '</tr>';
    $('#table_cmd tbody').append(tr);
    $('#table_cmd tbody tr:last').setValues(_cmd, '.cmdAttr');
    if (isset(_cmd.type)) {
        $('#table_cmd tbody tr:last .cmdAttr[data-l1key=type]').value(init(_cmd.type));
    }
    jeedom.cmd.changeType($('#table_cmd tbody tr:last'), init(_cmd.subType));
}
