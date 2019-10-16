import Vue from 'vue'
import axios from 'axios'
import Swal from 'sweetalert2'
import paginador from '../../components/paginador.vue'
import toastel from '../../components/toastel.vue'
import vSelect from 'vue-select'

Vue.component('v-select', vSelect)

if(document.getElementsByClassName("vue-estudiantes").length > 0){
    var vue_emp = new Vue({
        el: ".vue-estudiantes",
        components: {
            paginador,
            toastel
        },
        data :
        {
            api_url:'/api/v1',
            url:'/educacion/estudiantes/',
            show:false,
            showextra:false,
            estudiante: (document.getElementById("estudianteedit"))? JSON.parse($("#estudianteedit").val()):null,
            estudiantes:[],
            carreras:(document.getElementById("carrerashidden"))? $("input[name='carrerashidden[]']").map(function(){return JSON.parse($(this).val());}).get():[],
            carreras_options:[],
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
            detalle_tmp:null,
        },
        methods: {
            //Métodos HTTP
            getEstudiantes: function() {
                var url = this.api_url+this.url;
                axios.get(url, {
                    params: this.getParametros()
                  }).then(response => {
                    var data = response.data;
                    if(data)
                    {
                        this.estudiantes =  data.data;
                        this.pagination = data;
                        delete this.pagination.data;
                    }
                });
            },
            getCarrerasAsociadas: function() {
                var url = this.api_url+this.url+this.estudiante.id+"/carreras";
                axios.get(url).then(response => {
                    var data = response.data;
                    if(data)
                    {
                        this.carreras =  data.data;
                    }
                });
            },
            eliminarEstudiante: function(estudiante,$event=null) {
                var url = this.api_url+this.url+estudiante.id;

                axios.delete(url+(($event)? "?flash=true":"")).then(response => {
                    var data = response.data;
                    if($event) window.location = data.data.Url.replace("api/v1/","");
                    else
                    {
                        this.getEstudiantes();
                        this.showToast("Eliminado",data.meta.msj,"success");
                    }
                });
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
            confirmDialog(estudiante, $event=null)
            {
                Swal({
                    title: 'Confirmación',
                    text: "¿Desea eliminar el estudiante "+estudiante.Nombre+"?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Eliminar',
                    cancelButtonText: 'Cancelar',
                    animation: false
                  }).then((result) => {
                    if (result.value) {
                        this.eliminarEstudiante(estudiante,$event);
                    }
                  })
            },
            showToast(title, text="El estudiante se eliminó", icon,color){
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
                this.getEstudiantes();
            },
            toggleOrderBy:function(field)
            {
                this.orderby.type = (field == this.orderby.field)? !this.orderby.type:true;
                this.orderby.field = field;
                this.getEstudiantes();
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
            },
            /**/
            getCarreras: function(filter, loading) {
                this.carreras_options =[];
                if(filter)
                {
                    loading(true);
                    var url = this.api_url+'/educacion/carreras';
                    axios.get(url, {
                        params: {"filtro":filter,"por_pagina":-1}
                    }).then(response => {
                        var data = response.data;
                        if(data)
                        {
                            loading(false);
                            this.carreras_options =  data.data;
                        }
                    });
                }
            },
            agregarCarrera:function() {
                if (this.detalle_tmp)
                {
                    var detalle_ = JSON.parse(JSON.stringify(this.detalle_tmp));
                    detalle_['Ciclo'] = 1;
                    if(!this.carreras.find(d => d.id === detalle_.id)) {
                        this.carreras.push(detalle_);
                    }
                    this.detalle_tmp = null;
                }
            },
            quitarDetalleDialog(detalle){
                Swal({
                    title: 'Confirmación',
                    text: "¿Desea quitar el detalle?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Quitar',
                    cancelButtonText: 'Cancelar',
                    animation: false
                }).then((result) => {
                    if (result.value) {
                        this.quitarDetalle(detalle);
                    }
                })
            },
            quitarDetalle:function(detalle){
                this.carreras.splice(this.carreras.indexOf(detalle), 1);
                this.$forceUpdate();
            },
        },
        created: function () {
            if(document.getElementsByClassName("vue-estudiantes-detail").length <= 0) this.getEstudiantes();
            if(document.getElementsByClassName("vue-estudiantes-detail").length > 0)
            {
                if(this.estudiante) this.getCarrerasAsociadas(this.estudiante.id);
            }
        },
    });
}
