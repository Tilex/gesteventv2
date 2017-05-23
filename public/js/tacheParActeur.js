/**
 * Created by Octaedra on 12/01/2017.
 */
function BaseUrl() {
    return "/gestEventv2";
}
var TacheParActeur = {
    affiche: function(id){
        var li=document.getElementById(id);
        $("#t" + id).show();
        $.ajax({
            type:"POST",
            data:"&id="+id,
            dataType:"html",
            url:BaseUrl()+'/acteur/getTacheParActeur',
            success:function (response) {
                $("#t" + id).html(response);
            }
        });
    },
    ferme: function (id) {
        var li=document.getElementById(id);
        $("#t" + id).hide();
        /*
         li.hide();
         */

    }
};
