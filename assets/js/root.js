function HOME(subpasta = 'iowa') {
    var url_atual = window.location.href;
    var pedacos = url_atual.split('/');

    if(subpasta != '')
    {
        var home = pedacos[0]+'//'+pedacos[2]+'/'+subpasta;
    }
    else
    {
        var home = pedacos[0]+'//'+pedacos[2];
    }


    return home;
};