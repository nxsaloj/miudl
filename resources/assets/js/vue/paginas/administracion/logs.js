import Vue from 'vue'
import axios from 'axios'
import Swal from 'sweetalert2'
import paginador from '../../components/paginador.vue'
import toastel from '../../components/toastel.vue'
import vSelect from 'vue-select'
import VModal from 'vue-js-modal'
import Autocomplete from 'v-autocomplete'
import errorComponent from '../../components/errors'
import 'v-autocomplete/dist/v-autocomplete.css'

Vue.use(VModal, { dynamic: true })
Vue.use(Autocomplete)
Vue.component('v-select', vSelect)

if(document.getElementsByClassName("vue-logs-admin").length > 0){

    new Vue({
        el: ".vue-logs-admin",
        components: {
            paginador,
            toastel,
            errorComponent
            //Autocomplete
        },
        directives: {
            init: {
                bind: function(el, binding, vnode) {
                vnode.context[binding.arg] = binding.value;
                }
            }
        },
        data :
        {
            allow_save:true,
            saving:false,
            current_page:1,
            api_url:'/api/v1',
            url:'/administracion/logs/',
            show:false,
            showextra:false,
            auto:true,
            pagination:{
                "current_page":0,
                "last_page":0,
                "to":0,
                "per_page":0,
                "total":0
            },
            orderby:{
                "field":'Nombre',
                "type":true
            },
            filtro:"",
            fecha:null,
            error:null,
            logs:[]
        },
        methods: {
            limpiar(){
                this.filtro = "";
                this.getLogs();
            },
            initDetalle(){
                this.lotes = null;
            },
            getLogs: function(filter=null, por_pagina=false, target=null) {
                var url = this.api_url+this.url;
                axios.get(url, {
                    params: this.getParametros(filter, por_pagina)
                  }).then(response => {
                    var data = response.data;
                    if(data)
                    {
                        this.logs = data.data;
                        this.pagination = data;
                        delete this.pagination.data;
                    }
                });
            },
            getHora:function(pos=null){
                var today = new Date();
                var hh = today.getHours();
                var MM = today.getMinutes(); //January is 0!

                var hs = ""+hh;
                var ms = ""+MM;

                var hora = ((hs.length==1)? "0"+hh:hh)+":"+((ms.length==1)? "0"+MM:MM);
                return (pos)? ((pos == 1)? hh:MM):hora;
            },
            getFecha:function(_y=0,_m=0,_d=0){
                var today = new Date();
                var dd = today.getDate()+_d;
                var mm = today.getMonth()+1+_m; //January is 0!

                var yyyy = today.getFullYear()+_y;
                //if(dd<10){dd='0'+dd} if(mm<10){mm='0'+mm} today = dd+'/'+mm+'/'+yyyy;
                if(dd<10){dd='0'+dd} if(mm<10){mm='0'+mm} today = yyyy+'-'+mm+'-'+dd;
                //console.log(today);
                return today;
            },
            getParametros:function(filter=null,por_pagina=false){
                var params = {'orderby': this.getOrderBy()};
                if(por_pagina) params['por_pagina'] = -1;
                if(this.pagination.current_page > 0) params['page'] = this.pagination.current_page;

                if(filter) params['filtro'] = filter;
                else if(this.filtro) params['filtro'] = this.filtro;

                return params;
            },
            getParametrosSelect:function(filter=null,por_pagina=false){
                var params = {'orderby':  [this.orderby.field, this.orderby.type]};
                if(por_pagina) params['por_pagina'] = -1;
                if(filter) params['filtro'] = filter;
                return params;
            },
            cambiarPagina: function(){
                this.getLogs();
            },
            toggleOrderBy:function(field)
            {
                this.orderby.type = (field == this.orderby.field)? !this.orderby.type:true;
                this.orderby.field = field;
                this.getLogs();
            },
            getOrderBy:function(){
                return [this.orderby.field, this.orderby.type];
            },
        },
        created: function () {
            if(document.getElementsByClassName("vue-logs-admin-detail").length <= 0 && document.getElementsByClassName("vue-logs-admin-create").length <= 0)
            {
                this.create_devolucion = false;
                this.getLogs();
            }
            if(document.getElementsByClassName("vue-logs-admin-create").length > 0)
            {
                this.create_devolucion = true;
                this.getLogs();
            }
        },
    });

}
