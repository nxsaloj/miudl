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
            showextra:false,
            inscripcion:[],
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
                        this.inscripcion =  data.data;
                        this.pagination = data;
                        delete this.pagination.data;
                    }
                });
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
            }
        },
        created: function () {
            if(document.getElementsByClassName("vue-inscripcion-detail").length <= 0) this.getInscripciones();
        }
    });
}
