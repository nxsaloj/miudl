import Vue from 'vue'
import axios from 'axios'
import Swal from 'sweetalert2'
import paginador from '../../components/paginador.vue'
import toastel from '../../components/toastel.vue'
import vSelect from 'vue-select'

Vue.component('v-select', vSelect)

if(document.getElementsByClassName("vue-centrosuniversitarios").length > 0){
    new Vue({
        el: ".vue-centrosuniversitarios",
        components: {
            paginador,
            toastel
        },
        data :
        {
            api_url:'/api/v1',
            url:'/educacion/centrosuniversitarios/',
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
            centrosuniversitarios:[]
        },
        methods: {
            //Métodos HTTP
            limpiar(){
                this.filtro = "";
                this.getCentrosUniversitariosTrabajo();
            },
            getCentrosUniversitariosTrabajo: function(filter=null,loading=null) {
                if(loading) loading(true);
                var url = this.api_url+this.url;
                axios.get(url, {
                    params: this.getParametros(filter, (loading != null))
                }).then(response => {
                    var data = response.data;
                    if(data)
                    {
                        this.centrosuniversitarios =  data.data;
                        this.pagination = data;
                        delete this.pagination.data;
                        if(loading) loading(false);
                    }
                });
            },
            eliminarCentroUniversitarioTrabajo: function(centrouniversitario,$event=null) {
                var url = this.api_url+this.url+centrouniversitario.id;

                axios.delete(url+(($event)? "?flash=true":"")).then(response => {
                    var data = response.data;
                    if($event) window.location = data.data.Url.replace("api/v1/","");
                    else
                    {
                        this.getCentrosUniversitariosTrabajo();
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
            confirmDialog(centrouniversitario, $event=null)
            {
                Swal({
                    title: 'Confirmación',
                    text: "¿Desea eliminar el centrouniversitario "+centrouniversitario.Nombre+"?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Eliminar',
                    cancelButtonText: 'Cancelar',
                    animation: false
                  }).then((result) => {
                    if (result.value) {
                        this.eliminarCentroUniversitarioTrabajo(centrouniversitario, $event);
                    }
                  })
            },
            showToast(title, text="El centrouniversitario se eliminó", icon,color){
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
                this.getCentrosUniversitariosTrabajo();
            },
            toggleOrderBy:function(field)
            {
                this.orderby.type = (field == this.orderby.field)? !this.orderby.type:true;
                this.orderby.field = field;
                this.getCentrosUniversitariosTrabajo();
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
            if(document.getElementsByClassName("vue-centrosuniversitarios-create").length <= 0 && document.getElementsByClassName("vue-centrosuniversitarios-detail").length <= 0) this.getCentrosUniversitariosTrabajo();
        }
    });
}
