import Vue from 'vue'
import axios from 'axios'
import Swal from 'sweetalert2'
import paginador from '../../components/paginador.vue'
import toastel from '../../components/toastel.vue'
import vSelect from 'vue-select'

Vue.component('v-select', vSelect)

if(document.getElementsByClassName("vue-cursos").length > 0){
    var vue_emp = new Vue({
        el: ".vue-cursos",
        components: {
            paginador,
            toastel
        },
        data :
        {
            api_url:'/api/v1',
            url:'/educacion/cursos/',
            show:false,
            showextra:false,
            cursos:[],
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
            filtro:null
        },
        methods: {
            //Métodos HTTP
            getCursos: function() {
                var url = this.api_url+this.url;
                axios.get(url, {
                    params: this.getParametros()
                  }).then(response => {
                    var data = response.data;
                    if(data)
                    {
                        this.cursos =  data.data;
                        this.pagination = data;
                        delete this.pagination.data;
                    }
                });
            },
            eliminarCurso: function(curso,$event=null) {
                var url = this.api_url+this.url+curso.id;

                axios.delete(url+(($event)? "?flash=true":"")).then(response => {
                    var data = response.data;
                    if($event) window.location = data.data.Url.replace("api/v1/","");
                    else
                    {
                        this.getCursos();
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
            confirmDialog(curso, $event=null)
            {
                Swal({
                    title: 'Confirmación',
                    text: "¿Desea eliminar el curso "+curso.Nombre+"?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Eliminar',
                    cancelButtonText: 'Cancelar',
                    animation: false
                  }).then((result) => {
                    if (result.value) {
                        this.eliminarCurso(curso,$event);
                    }
                  })
            },
            showToast(title, text="El curso se eliminó", icon,color){
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
                this.getCursos();
            },
            toggleOrderBy:function(field)
            {
                this.orderby.type = (field == this.orderby.field)? !this.orderby.type:true;
                this.orderby.field = field;
                this.getCursos();
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
            if(document.getElementsByClassName("vue-cursos-detail").length <= 0) this.getCursos();
        }
    });
}
