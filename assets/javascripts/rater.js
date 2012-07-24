$(document).ready(function() {
    $("a[href*=ratings_rpc]").bind("click", function(){
        
        par = this.href
        var params = par.split('/');
        params.reverse();
        var vote = params[4];
        var id_num = params[3];
        var ip_num = params[2];
        var units = params[1];    

        $(this).parent().parent().html('<div class="loading"><div>');
        $('#unit_long'+id_num.replace('idnum-','')).load('/ratings/ratings_rpc/index/'+vote+'/'+id_num+'/'+ip_num+'/'+units);
            
        return false;            
    });
}); 