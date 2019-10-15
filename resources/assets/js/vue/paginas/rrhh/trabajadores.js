import Vue from 'vue'
import axios from 'axios'
import Swal from 'sweetalert2'
import paginador from '../../components/paginador.vue'
import toastel from '../../components/toastel.vue'
import vSelect from 'vue-select'

Vue.component('v-select', vSelect)

if(document.getElementsByClassName("vue-trabajadores").length > 0){
    var vue_emp = new Vue({
        el: ".vue-trabajadores",
        components: {
            paginador,
            toastel
        },
        data :
        {
            api_url:'/api/v1',
            url:'/rrhh/trabajadores/',
            show:false,
            showextra:false,
            trabajadores:[],
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
            filtro:null,
            filtroproyecto:"",
            filtropuesto:"",
            puestos:[],
            puesto: (document.getElementById("puestohidden"))? {"id":$('input[name=puesto]').val(),"Nombre":$('input#puestohidden').val()}:null
        },
        methods: {
            //Métodos HTTP
            getTrabajadores: function() {
                var url = this.api_url+this.url;
                axios.get(url, {
                    params: this.getParametros()
                  }).then(response => {
                    var data = response.data;
                    if(data)
                    {
                        this.trabajadores =  data.data;
                        this.pagination = data;
                        delete this.pagination.data;
                    }
                });
            },
            eliminarEmpleado: function(trabajador,$event=null) {
                var url = this.api_url+this.url+trabajador.id;

                axios.delete(url+(($event)? "?flash=true":"")).then(response => {
                    var data = response.data;
                    if($event) window.location = data.data.Url.replace("api/v1/","");
                    else
                    {
                        this.getTrabajadores();
                        this.showToast("Eliminado",data.meta.msj,"success");
                    }
                });
            },
            getPuestosTrabajo: function(filter, loading) {
                this.puestos =[];
                if(filter)
                {
                    loading(true);
                    var url = this.api_url+'/rrhh/puestos';
                    axios.get(url, {
                        params: {"filtro":filter,"por_pagina":-1}
                    }).then(response => {
                        var data = response.data;
                        if(data)
                        {
                            loading(false);
                            this.puestos =  data.data;
                        }
                    });
                }
            },
            setPuesto(){
                var id = (this.puesto)? this.puesto.id:null;
                $("input[name='puesto']").val(id);
                this.puestos =[];
            },
            //Funciones
            limpiar(){
                this.filtro = "";
                this.filtroproyecto = "";
                this.filtrodepartamento = "";
            },
            getParametros:function(){
                var params = {'orderby': this.getOrderBy()};
                if(this.pagination.current_page > 0) params['page'] = this.pagination.current_page;
                if(this.filtro) params['filtro'] = this.filtro;
                return params;
            },
            confirmDialog(trabajador, $event=null)
            {
                Swal({
                    title: 'Confirmación',
                    text: "¿Desea eliminar al trabajador "+trabajador.Nombre+"?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Eliminar',
                    cancelButtonText: 'Cancelar',
                    animation: false
                  }).then((result) => {
                    if (result.value) {
                        this.eliminarEmpleado(trabajador,$event);
                    }
                  })
            },
            showToast(title, text="El trabajador se eliminó", icon,color){
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
                this.getTrabajadores();
            },
            toggleOrderBy:function(field)
            {
                this.orderby.type = (field == this.orderby.field)? !this.orderby.type:true;
                this.orderby.field = field;
                this.getTrabajadores();
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
            if(document.getElementsByClassName("vue-trabajadores-detail").length <= 0) this.getTrabajadores();
        }
    });
}
