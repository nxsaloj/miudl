import Vue from 'vue'
import axios from 'axios'
import Swal from 'sweetalert2'
import paginador from '../../components/paginador.vue'
import toastel from '../../components/toastel.vue'
import vSelect from 'vue-select'

Vue.component('v-select', vSelect)

if(document.getElementsByClassName("vue-carreras").length > 0){
    var vue_emp = new Vue({
        el: ".vue-carreras",
        components: {
            paginador,
            toastel
        },
        data :
        {
            api_url:'/api/v1',
            url:'/educacion/carreras/',
            show:false,
            showextra:false,
            carreras:[],
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
            filtrofacultad:"",
            facultades:[],
            facultad: (document.getElementById("facultadhidden"))? {"id":$('input[name=facultad]').val(),"Nombre":$('input#facultadhidden').val()}:null
        },
        methods: {
            //Métodos HTTP
            getCarreras: function() {
                var url = this.api_url+this.url;
                axios.get(url, {
                    params: this.getParametros()
                  }).then(response => {
                    var data = response.data;
                    if(data)
                    {
                        this.carreras =  data.data;
                        this.pagination = data;
                        delete this.pagination.data;
                    }
                });
            },
            eliminarCarrera: function(carrera,$event=null) {
                var url = this.api_url+this.url+carrera.id;

                axios.delete(url+(($event)? "?flash=true":"")).then(response => {
                    var data = response.data;
                    if($event) window.location = data.data.Url.replace("api/v1/","");
                    else
                    {
                        this.getCarreras();
                        this.showToast("Eliminado",data.meta.msj,"success");
                    }
                });
            },
            getFacultades: function(filter, loading) {
                this.facultades =[];
                if(filter)
                {
                    loading(true);
                    var url = this.api_url+'/educacion/facultades';
                    axios.get(url, {
                        params: {"filtro":filter,"por_pagina":-1}
                    }).then(response => {
                        var data = response.data;
                        if(data)
                        {
                            loading(false);
                            this.facultades =  data.data;
                        }
                    });
                }
            },
            setFacultad(){
                var id = (this.facultad)? this.facultad.id:null;
                $("input[name='facultad']").val(id);
                this.facultades =[];
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
            confirmDialog(carrera, $event=null)
            {
                Swal({
                    title: 'Confirmación',
                    text: "¿Desea eliminar la carrera "+carrera.Nombre+"?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Eliminar',
                    cancelButtonText: 'Cancelar',
                    animation: false
                  }).then((result) => {
                    if (result.value) {
                        this.eliminarCarrera(carrera,$event);
                    }
                  })
            },
            showToast(title, text="La carrera se eliminó", icon,color){
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
                this.getCarreras();
            },
            toggleOrderBy:function(field)
            {
                this.orderby.type = (field == this.orderby.field)? !this.orderby.type:true;
                this.orderby.field = field;
                this.getCarreras();
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
            if(document.getElementsByClassName("vue-carreras-detail").length <= 0) this.getCarreras();
        }
    });
}
