/**
 * Created by Octaedra on 13/01/2017.
 */
function BaseUrl() {
    return "/gestEventv2";
}
var Projet = {
    affiche: function(id,id2){
        var li=document.getElementById(id,id2);
        $("#"+ id + id2).show();
        $.ajax({
            type:"POST",
            data:"&id="+ id + "&id2=" + id2,
            dataType:"html",
            url:BaseUrl()+'/recapJournee/getInfosProjet',
            success:function (response) {
                $("#" + id + id2 ).html(response);
            }
        });
    },
    ferme: function (id,id2) {
        var li=document.getElementById(id,id2);
        $("#"+ id + id2 ).hide();
        /*
         li.hide();
         */

    },

};/**
 * Created by Octaedra on 25/01/2017.
 */
