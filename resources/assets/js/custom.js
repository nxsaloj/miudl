
$(function() {
    'use strict';


    $(document).ready(function() {
        $("body").on('keydown', 'input:not(.ignore-keyup), textarea:not(.ignore-keyup)', function(e) {
            var self = $(this)
              , form = self.parents('form:eq(0)')
              , focusable
              , next
              ;

            if(self.parent().is(":not(.bs-searchbox)"))
                if (e.keyCode == 13) {
                    focusable = form.find('input,a,select,button,textarea').filter(':visible');
                    next = focusable.eq(focusable.index(this)+1);
                    if (next.length) {
                        next.focus();
                    } else {
                        form.submit();
                    }
                    return false;
                }
        });

        Grid.init();
    });



    window.onload = function (event) {flexFont();}
    window.onresize = function (event) {flexFont();   };

    $(".og-grid a").hover(function(){
        flexFont();
    });
    /*$(document).arrive('.dropify', function() {
        //$('.dropify').dropify();
        $('.dropify').dropify({
            messages: {
                'default': 'Presione o arrastre el archivo aquí',
                'replace': 'Presiono o arrastre para reemplazar',
                'remove':  'Remover',
                'error':   'Ooops, algo no funcionó bien.'
            }
        });
    });*/
    //Grid.init();
});
var items = [];
var flexFont = function flexFont() {
    var divs = document.getElementsByClassName("flexFont");
    for (var i = 0; i < divs.length; i++) {
        var relFontsize = divs[i].offsetWidth * 0.13;
        divs[i].style.fontSize = relFontsize + 'px';
    }
};
$(document).arrive('.flexFont', function() {
    flexFont();
});

$(document).arrive('.og-grid', {fireOnAttributesModification: true}, function(e) {
    //Grid.init();
});

$(document).arrive('.og-grid li', {fireOnAttributesModification: true}, function(e) {
    var $newElem = $(this);
    //setTimeout( function(){Grid.addItems($newElem)} , 200 );
    if(checkLoaded())
    {
        Grid.addItems($newElem);
    }
    else items.push($newElem);
});

window.addEventListener('load', function () {
    for(var i = 0; i < items.length; i++)
    {
        Grid.addItems(items[i]);
    }
}, false);

function checkLoaded() {
    return document.readyState === "complete" || document.readyState === "interactive";
}

var getWinSize = function() {
    return { width : $( window ).width(), height : $( window ).height() };
}
new ResizeSensor($("body"), function() {
    $('.container-scroller').css("height",(getWinSize().height-61)+"px");
});
$('.container-scroller').css("height",(getWinSize().height-61)+"px");
