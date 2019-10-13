var customtools = function(){
    var childs = 2;
    var init = function(_params, _func, _childs=2)
    {
        childs = _childs;
        for(var i = 0; i < _params.length; i++)
        {
            var funcion = _func;
            let obj = _params[i];
            $(document).arrive(obj+'.selectpicker+button+.dropdown-menu .bs-searchbox input', function(el) {
                event(funcion,el,obj);
            });
        }
    }
    var addOption = function(target='.selectpicker',valores=null, id="CuentaContable_id",text="Nombre",extra=null)
    {
        $(target).children('option:not(:nth-child(1),:nth-child('+childs+'))').remove().end();
        if((valores)? (valores.length > 0):false )
        {        
            valores.forEach(element => {
                $(target).append($("<option></option>").attr("value",element[id]).text(element[text]+((extra)? " ("+element[extra]+")":""))); 
            });    
        }
        $(target).selectpicker('refresh');     
    };
     
    var delay = (function(){
        var timer = 0;
        return function(callback, ms){
            clearTimeout (timer);
            timer = setTimeout(callback, ms);
        };
    })(); 
   
    var event = (function(vuerepo, el, target=null){
            $(el).on('keyup',function(event){
                var buscar = true;
                //if ( event.which == 13 || event.which == 37 || event.which == 38 || event.which == 39 || event.which == 40 ) {
                if (!String.fromCharCode(event.which).match(/[a-zA-Z0-9 ]/)) {
                    buscar = false;
                }
                if(buscar)
                {
                    var valor = $(el).val();
                    if(valor)
                    {                
                        delay(function(){
                            vuerepo(valor, -1, target);
                        }, 700 );
                    }
                    else addOption(target);
                }
            });
    });
    return {
        init:init,
        addOption: addOption,
        event:event
    }
}();

module.exports = customtools;