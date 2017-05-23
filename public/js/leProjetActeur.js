/**
 * Created by Octaedra on 13/01/2017.
 */
function BaseUrl() {
    return "/gestEventv2";
}
var Projet = {
    affiche: function(id){
        var li=document.getElementById(id);
        $("#" + id).show();
        $.ajax({
            type:"POST",
            data:"&id="+id,
            dataType:"html",
            url:BaseUrl()+'/projet/getProjetParActeur',
            success:function (response) {
                $("#" + id).html(response);
            }
        });
    },
    ferme: function (id) {
        var li=document.getElementById(id);
        $("#" + id).hide();
        /*
         li.hide();
         */

    }
};/**
 * Created by Octaedra on 31/01/2017.
 */
