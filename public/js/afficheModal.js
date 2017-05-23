function BaseUrl() {
    return "/gestEventv2";
}
var Modal = {
    modification: function(id){
        $('#'+ id).modal({show:true});
        $.ajax({
            type: "POST",
            data: "&id="+ id,
            dataType: "html",
            url: BaseUrl()+'/projet/edit/'+id,
            success: function (response) {

                $("#modalBody"+ id).html(response);
            }
        })
    },
    modificationTache: function(id){
        $('#'+ id).modal({show:true});
        $.ajax({
            type: "POST",
            data: "&id="+ id,
            dataType: "html",
            url: BaseUrl()+'/tache/editAdmin/'+id,
            success: function (response) {

                $("#modalBody"+ id).html(response);
            }
        })
    },
    modificationCategorie: function(){
        $('#i').modal({show:true});
        $.ajax({
            type: "POST",
            data: "&id=",
            dataType: "html",
            url: BaseUrl()+'/tache/createCategorie/',
            success: function (response) {

                $("#modalBody").html(response);
            }
        })
    },
    modificationActeur: function(id){
        $('#i'+ id).modal({show:true});
        $.ajax({
            type: "POST",
            data: "&id=",
            dataType: "html",
            url: BaseUrl()+'/acteur/editActeur/'+id,
            success: function (response) {

                $("#modalBody"+ id).html(response);
            }
        })
    },
    creationProjet: function(){
        $('#j').modal({show:true});
        $.ajax({
            type: "POST",
            data: "&id=",
            dataType: "html",
            url: BaseUrl()+'/projet/createProjet/',
            success: function (response) {

                $("#modalBody").html(response);
            }
        })
    },
    creationActeur: function(){
        $('#j').modal({show:true});
        $.ajax({
            type: "POST",
            data: "&id=",
            dataType: "html",
            url: BaseUrl()+'/acteur/createActeur/',
            success: function (response) {

                $("#modalBody").html(response);
            }
        })
    },
    afficheProjet: function(id){
        $('#k'+id).modal({show:true});
        $.ajax({
            type: "POST",
            data: "&id="+ id,
            dataType: "html",
            url: BaseUrl()+'/recapJournee/getInfosProjet/'+id,
            success: function (response) {
                $("#modalBody"+ id).html(response);
            }
        })
    },
    afficheTache: function(id){
        $('#k'+id).modal({show:true});
        $.ajax({
            type: "POST",
            data: "&id="+ id,
            dataType: "html",
            url: BaseUrl()+'/recapJournee/getInfosTache/'+id,
            success: function (response) {
                $("#modalBody"+ id).html(response);
            }
        })
    }
};
