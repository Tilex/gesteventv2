/**
 * Created by Octaedra on 12/01/2017.
 */
function BaseUrl() {
    return "/gestEventv2";
}
var Projet = {
    affiche: function(id){
         var li=document.getElementById(id);
         $("#p" + id).show();
        $.ajax({
            type:"POST",
            data:"&id="+id,
            dataType:"html",
            url:BaseUrl()+'/acteur/getProjetParActeur',
            success:function (response) {
                $("#p" + id).html(response);
            }
        });
    },
    ferme: function (id) {
         var li=document.getElementById(id);
         $("#p" + id).hide();
/*
        li.hide();
*/

    }
};