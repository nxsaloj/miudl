import Vue from 'vue'
//import notification from './notification'
import moment from 'moment'

Vue.filter('formatDate', function(value, format=null) {
    if (value) {
        return moment(String(value)).format((format)? format:'DD/MM/YYYY hh:mm')
    }
});

Vue.filter('formatTime', function(value, format=null) {
    if (value) {
        //if(!format) return "jejeje";
        let datos = value.split(":");
        return (((+datos[0]) > 0)? datos[0]+"d ":"")+(((+datos[1]) > 0)? datos[1]+"h ":"0h ")+(((+datos[2]) > 0)? datos[2]+"m":"0m");
    }
});

Vue.filter('formatEDM', function(value, format=null) {
    if (value) {
        return String(value).replace("'","-");
    }
});

Vue.filter('capitalize', function (value) {
    if (!value) return ''
    value = value.toString()
    return value.charAt(0).toUpperCase() + value.slice(1)
});