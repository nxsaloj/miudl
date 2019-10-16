import Vue from 'vue'
import axios from 'axios'
import Swal from 'sweetalert2'
import paginador from '../../components/paginador.vue'
import toastel from '../../components/toastel.vue'
import vSelect from 'vue-select'

Vue.component('v-select', vSelect)

if(document.getElementsByClassName("vue-inscripcion").length > 0){
    var vue_emp = new Vue({
        el: ".vue-inscripcion",
        components: {
            paginador,
            toastel
        },
        data :
        {
            api_url:'/api/v1',
            url:'/administracion/inscripcion/',
            show:false,
            loading:false,
            saving:false,
            showextra:false,
            cursos:[],
            inscripciones:[],
            carreras_options:[],
            estudiantes_options:[],
            cursos_options:[],
            centrosuniversitarios_options:[],
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
            carrera:(document.getElementById("carrerahidden"))? JSON.parse($('input#carrerahidden').val()):null,
            centrouniversitario:(document.getElementById("centrouniversitariohidden"))? JSON.parse($('input#centrouniversitariohidden').val()):null,
            tipo_inscripcion:'inscripcion',
            curso_tmp:null,
            estudiante:null
        },
        watch:{
            grado (val) {
                this.grados = [];
            }
        },
        methods: {
            //Métodos HTTP
            getInscripciones: function() {
                var url = this.api_url+this.url;
                axios.get(url, {
                    params: this.getParametros()
                  }).then(response => {
                    var data = response.data;
                    if(data)
                    {
                        this.inscripciones =  data.data;
                        this.pagination = data;
                        delete this.pagination.data;
                    }
                });
            },
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
            getCursos: function(filter, loading) {
                this.cursos_options =[];
                if(filter)
                {
                    loading(true);
                    var url = this.api_url+'/educacion/cursos';
                    axios.get(url, {
                        params: {"filtro":filter,"por_pagina":-1}
                    }).then(response => {
                        var data = response.data;
                        if(data)
                        {
                            loading(false);
                            this.cursos_options =  data.data;
                        }
                    });
                }
            },
            getEstudiantes: function(filter, loading) {
                this.estudiantes_options =[];
                if(filter)
                {
                    loading(true);
                    var url = this.api_url+'/educacion/estudiantes';
                    axios.get(url, {
                        params: {"filtro":filter,"por_pagina":-1}
                    }).then(response => {
                        var data = response.data;
                        if(data)
                        {
                            loading(false);
                            this.estudiantes_options =  data.data;
                        }
                    });
                }
            },
            getCentrosUniversitarios: function(filter, loading) {
                this.centrosuniversitarios_options =[];
                if(filter)
                {
                    loading(true);
                    var url = this.api_url+'/educacion/centrosuniversitarios';
                    axios.get(url, {
                        params: {"filtro":filter,"por_pagina":-1}
                    }).then(response => {
                        var data = response.data;
                        if(data)
                        {
                            loading(false);
                            this.centrosuniversitarios_options =  data.data;
                        }
                    });
                }
            },
            //Funciones
            limpiar(){
                this.filtro = "";
                this.filtrogrado = null;
                this.show = false;
            },
            getParametros:function(){
                var params = {'orderby': this.getOrderBy()};
                if(this.pagination.current_page > 0) params['page'] = this.pagination.current_page;
                if(this.filtro) params['filtro'] = this.filtro;
                if(this.filtrocarrera) params['filtrocarrera'] = this.filtrocarrera;
                return params;
            },
            showToast(title, text="La inscripción se creó", icon,color){
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
            imprimirFormulario(filter=null, por_pagina=false, target=null) {
                if (this.loading == false) {
                    this.loading = true;
                    var url = this.api_url + this.url + 'imprimir_formulario';
                    axios.get(url, {
                        params: {"tipo_inscripcion":this.tipo_inscripcion,"carrera": this.carrera, "centrouniversitario": this.centrouniversitario},
                        responseType: 'blob',
                        timeout: 400000
                    }).then((response) => {
                        //console.log(response.data);
                        const url = window.URL.createObjectURL(new Blob([response.data]));
                        const link = document.createElement('a');
                        link.href = url;
                        link.setAttribute('download', 'Formulario-' + this.$options.filters.formatDate(new Date(), 'YYYY-MM-DD-hh-mm-ss') + '.pdf');
                        document.body.appendChild(link);
                        link.click();
                        this.loading = false;
                    }).catch(error => {
                        this.loading = false;
                        this.showToast("Alerta", "Ocurrió un error al generar el reporte");
                    });
                }
            },
            /**/
            agregarCurso:function() {
                if (this.curso_tmp)
                {
                    var detalle_ = JSON.parse(JSON.stringify(this.curso_tmp));
                    detalle_['Ciclo'] = 1;
                    if(!this.cursos.find(d => d.id === detalle_.id)) {
                        this.cursos.push(detalle_);
                    }
                    this.curso_tmp = null;
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
                this.cursos.splice(this.cursos.indexOf(detalle), 1);
                this.$forceUpdate();
            },
        },
        created: function () {
            if(document.getElementsByClassName("vue-inscripcion-create").length <= 0 && document.getElementsByClassName("vue-inscripcion-print").length <= 0) this.getInscripciones();
        }
    });
}
