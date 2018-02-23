<?php
if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
sendVarToJS('eqType', 'jeeWunderlist');
$eqLogics = eqLogic::byType('jeeWunderlist');

?>

    <div class="row row-overflow">
        <div class="col-lg-2 col-md-3 col-sm-4">
            <div class="bs-sidebar">
                <ul id="ul_eqLogic" class="nav nav-list bs-sidenav">
                    <a class="btn btn-default eqLogicAction" style="width : 100%;margin-top : 5px;margin-bottom: 5px;" data-action="add"><i class="fa fa-plus-circle"></i> {{Ajouter un jeeWunderlist}}</a>
                    <li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%" /></li>
                    <?php
                      foreach ($eqLogics as $eqLogic) {
                          echo '<li class="cursor li_eqLogic" data-eqLogic_id="'.$eqLogic->getId().'"><a>'.$eqLogic->getHumanName(true).'</a></li>';
                      }
                      ?>
                </ul>
            </div>
        </div>

        <div class="col-lg-10 col-md-9 col-sm-8 eqLogicThumbnailDisplay" style="border-left: solid 1px #EEE; padding-left: 25px;">
            <legend><i class="fa fa-cog"></i> {{Gestion}}</legend>
            <div class="eqLogicThumbnailContainer">

                <div class="cursor eqLogicAction" data-action="gotoPluginConf" style="background-color : #ffffff; height : 140px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;">
                    <center>
                        <i class="fa fa-wrench" style="font-size : 5em;color:#767676;"></i>
                    </center>
                    <span style="font-size : 1.1em;position:relative; top : 23px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#767676"><center>{{Configuration}}</center></span>
                </div>
            </div>

            <legend><i class="fa fa-table"></i> {{Mes listes}}</legend>
            <div class="eqLogicThumbnailContainer">
                <div class="cursor eqLogicAction" data-action="add" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;">
                    <center>
                        <i class="fa fa-plus-circle" style="font-size : 7em;color:#3498db;"></i>
                    </center>
                    <span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>Ajouter</center></span>
                </div>

                <?php
                  foreach ($eqLogics as $eqLogic) {
                      echo '<div class="eqLogicDisplayCard cursor" data-eqLogic_id="'.$eqLogic->getId().'" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >';
                      echo '<center>';
                      echo '<img src="plugins/jeeWunderlist/doc/images/jeewunderlist_icon.png" height="105" width="95" />';
                      echo '</center>';
                      echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>'.$eqLogic->getHumanName(true, true).'</center></span>';
                      echo '</div>';
                  }
                ?>
            </div>
        </div>

        <div class="col-lg-10 col-md-9 col-sm-8 eqLogic" style="border-left: solid 1px #EEE; padding-left: 25px;display: none;">
            <a class="btn btn-success eqLogicAction pull-right" data-action="save"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
            <a class="btn btn-danger eqLogicAction pull-right" data-action="remove"><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>

            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-tachometer"></i> {{Equipement}}</a></li>
                <li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-list-alt"></i> {{Commandes}}</a></li>
            </ul>

            <div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
                <div role="tabpanel" class="tab-pane active" id="eqlogictab">
                    <form class="form-horizontal">
                        <fieldset>
                            <legend><i class="fa fa-arrow-circle-left eqLogicAction cursor" data-action="returnToThumbnailDisplay"></i> {{Général}} <i class='fa fa-cogs eqLogicAction pull-right cursor expertModeVisible' data-action='configure'></i></legend>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">{{Nom de l'équipement jeeWunderlist}}</label>
                                <div class="col-sm-3">
                                    <input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
                                    <input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement jeeWunderlist}}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">{{Objet parent}}</label>
                                <div class="col-sm-3">
                                    <select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
                        <option value="">{{Aucun}}</option>
                        <?php
                          foreach (object::all() as $object) {
                              echo '<option value="'.$object->getId().'">'.$object->getName().'</option>';
                          }
                        ?>
                   </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"></label>
                                <div class="col-sm-9">
                                    <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
                                    <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">{{Client ID}}</label>
                                <div class="col-sm-3">
                                    <input type="text" class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="clientId" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">{{Client Secret}}</label>
                                <div class="col-sm-5">
                                    <input type="text" class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="clientSecret" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">{{Access Token}}</label>
                                <div class="col-sm-5">
                                    <input type="text" class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="accessToken" />
                                </div>
                            </div>

                            <!--  User -->
                            <div id="userDetails" class="form-group" style="display: none;">
                                <label class="col-sm-3 control-label">{{Utilisateur}}</label>
                                <div class="col-sm-9" style="margin-bottom: 15px;">
                                    <div class="media">
                                        <div class="media-left">
                                            <img id="userAvatar" class="media-object img-circle" alt="avatar" style="height: 64px; width: 64px;">
                                        </div>
                                        <div class="media-body">
                                            <h4 id="userName" class="media-heading"></h4>
                                            <a id="userEmail"></a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Liste -->
                                <label class="col-sm-3 control-label">{{Liste}}</label>
                                <div class="col-sm-3">
                                    <select id="userLists" class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="listId">
																    </select>
                                </div>
                            </div>

                        </fieldset>
                    </form>
                </div>
                <div role="tabpanel" class="tab-pane" id="commandtab">
                    <table id="table_cmd" class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th>{{Nom}}</th>
                                <th>{{Action}}</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <?php include_file('desktop', 'jeeWunderlist', 'js', 'jeeWunderlist'); ?>
    <?php include_file('core', 'plugin.template', 'js'); ?>
