MagicToolbox_option = false;
MagicToolbox_links = [];

function MagicToolbox_clickElement(el) {
    var event;
    if (document.createEvent) {
        event = document.createEvent('MouseEvents');
        event.initEvent('click', true, true);
        el.dispatchEvent(event);
    } else {
        event = document.createEventObject();
        event.eventType = 'MouseEvents';
        el.fireEvent('onclick', event);
    }
    return event;
}

function MagicToolbox_findOption(opt_name) {
    var selects = document.getElementsByTagName('select');
    for(var i=0, l=selects.length; i<l; i++) {
        if(selects[i].getAttribute('optitle') == opt_name) {
            MagicToolbox_option = selects[i]; 
            break;
        }
    }
    var a = false, lnks = document.getElementsByTagName('a');
    for(var i=0, l=lnks.length; i<l; i++) {
        if(/MagicZoom/.test(lnks[i].className)) {
            a = lnks[i];
            break;
        }

    }
    if(a) {
        for(var i=0, l=lnks.length; i<l; i++) {
            if(lnks[i].rel == a.id){
                MagicToolbox_links.push(lnks[i]);
            }
        }
    }
    if(MagicToolbox_option) {
        var f = function() {
            var t, v = MagicToolbox_option.value;
            for(var i=0, l=MagicToolbox_option.options.length; i<l; i++) {
                if(MagicToolbox_option.options[i].value == v) {
                    t =  MagicToolbox_option.options[i].text;
                    t = t.replace(/\s(\+|\-)\$[0-9]+(\.[0-9]+)?$/g, '');
                    break;
                }
            }
            for(var i=0, l=MagicToolbox_links.length; i<l; i++) {
                if(MagicToolbox_links[i].getAttribute('title') == t) {
                    MagicToolbox_clickElement(MagicToolbox_links[i]);
                    break;
                }
            }
        }

        MagicZoom_addEventListener(MagicToolbox_option, 'change', f);


    }
}

function MagicToolbox_changeOption(a) {
    if(!MagicToolbox_option) return;
    var txt = a.getAttribute('title');
    for(var i=0, l=MagicToolbox_option.options.length; i<l; i++) {
        var t = MagicToolbox_option.options[i].text;
        t = t.replace(/\s(\+|\-)\$[0-9]+(\.[0-9]+)?$/g, '');
        if(t == txt) {
            MagicToolbox_option.value = MagicToolbox_option.options[i].value;
            break;
        }
    }
}