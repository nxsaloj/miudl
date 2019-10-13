import Vue from 'vue'
import axios from 'axios'
import Swal from 'sweetalert2'
import paginador from '../../components/paginador.vue'
import toastel from '../../components/toastel.vue'
import vSelect from 'vue-select'

Vue.component('v-select', vSelect)

if(document.getElementsByClassName("vue-puestos").length > 0){
    new Vue({
        el: ".vue-puestos",
        components: {
            paginador,
            toastel
        },
        data :
        {
            api_url:'/api/v1',
            url:'/rrhh/puestos/',
            show:false,
            showextra:false,
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
            puestos:[]
        },
        methods: {
            //Métodos HTTP
            limpiar(){
                this.filtro = "";
                this.getPuestosTrabajo();
            },
            getPuestosTrabajo: function(filter=null,loading=null) {
                if(loading) loading(true);
                var url = this.api_url+this.url;
                axios.get(url, {
                    params: this.getParametros(filter, (loading != null))
                }).then(response => {
                    var data = response.data;
                    if(data)
                    {
                        this.puestos =  data.data;
                        this.pagination = data;
                        delete this.pagination.data;
                        if(loading) loading(false);
                    }
                });
            },
            eliminarPuestoTrabajo: function(puesto,$event=null) {
                var url = this.api_url+this.url+puesto.id;

                axios.delete(url+(($event)? "?flash=true":"")).then(response => {
                    var data = response.data;
                    if($event) window.location = data.data.Url.replace("api/v1/","");
                    else
                    {
                        this.getPuestosTrabajo();
                        this.showToast("Eliminado",data.meta.msj,"success");
                    }
                });
            },
            //Funciones
            getParametros:function(){
                var params = {'orderby': this.getOrderBy()};
                if(this.pagination.current_page > 0) params['page'] = this.pagination.current_page;
                if(this.filtro) params['filtro'] = this.filtro;
                return params;
            },
            confirmDialog(puesto, $event=null)
            {
                Swal({
                    title: 'Confirmación',
                    text: "¿Desea eliminar el puesto "+puesto.Nombre+"?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Eliminar',
                    cancelButtonText: 'Cancelar',
                    animation: false
                  }).then((result) => {
                    if (result.value) {
                        this.eliminarPuestoTrabajo(puesto, $event);
                    }
                  })
            },
            showToast(title, text="El puesto se eliminó", icon,color){
                $.toast({
                    heading: title,
                    text: text,
                    position: String("top-right"),
                    stack: false,
                    loader: false,
                    icon:icon,
                    showHideTransition: 'slide',
                    bgColor: color? color:'#33AA66',
                });
            },
            cambiarPagina: function(){
                this.getPuestosTrabajo();
            },
            toggleOrderBy:function(field)
            {
                this.orderby.type = (field == this.orderby.field)? !this.orderby.type:true;
                this.orderby.field = field;
                this.getPuestosTrabajo();
            },
            getOrderBy:function(){
                return [this.orderby.field, this.orderby.type];
            },
            session_keep:function(action){
                this.showextra = true;
                setTimeout(function() {
                    $("#extra-action").val(action);
                    $(".submit").trigger( "click" );
                }, 50);
            }
        },
        created: function () {
            if(document.getElementsByClassName("vue-puestos-create").length <= 0 && document.getElementsByClassName("vue-puestos-detail").length <= 0) this.getPuestosTrabajo();
        }
    });
}
